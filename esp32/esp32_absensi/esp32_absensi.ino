/**
 * LAKMUD V - ESP32 RFID Absensi Client Program v2.0
 *
 * BARU v2.0:
 * - Offline Queue: Simpan absensi ke flash NVS (Preferences) saat server mati
 * - Auto-sync   : Kirim antrian otomatis saat server kembali online (tiap 30
 * detik)
 * - Mode Antrian: Hold Button NEXT (26) >1.5 detik untuk masuk/keluar menu
 * antrian Di menu antrian, tekan singkat NEXT/PREV untuk lihat tiap entry
 *
 * Hardware Wiring:
 * - LCD I2C 16x2 : SDA -> GPIO 21, SCL -> GPIO 22
 * - RC522 RFID   :
 *     RST  -> GPIO 15 | MISO -> GPIO 4
 *     MOSI -> GPIO 5  | SCK  -> GPIO 18 | SDA (SS) -> GPIO 19
 * - Button <- (Prev) : GPIO 25 (INPUT_PULLUP)
 * - Button -> (Next) : GPIO 26 (INPUT_PULLUP)
 * - Buzzer           : GPIO 14 (Active HIGH)
 *
 * Required Libraries:
 * - LiquidCrystal_I2C (Frank de Brabander)
 * - MFRC522 (Github Community)
 * - ArduinoJson (Benoit Blanchon, v6/v7)
 * - Preferences (built-in ESP32 Arduino Core)
 */

#include <ArduinoJson.h>
#include <HTTPClient.h>
#include <LiquidCrystal_I2C.h>
#include <MFRC522.h>
#include <Preferences.h>
#include <SPI.h>
#include <WiFi.h>
#include <WiFiClientSecure.h>
#include <Wire.h>

// ─── WiFi & Server ─────────────────────────────────────────────────────────
const char *ssid = "LAKMUD";
const char *password = "12345678";
const char *serverUrl = "https://lakmud5.pacipnuippnukauman.online";

// ─── Pin Definitions ───────────────────────────────────────────────────────
#define PIN_BTN_PREV 25
#define PIN_BTN_NEXT 26
#define PIN_BUZZER 14
#define PIN_RFID_RST 15
#define PIN_RFID_SDA 19 // SS

#define SPI_SCK 18
#define SPI_MISO 4
#define SPI_MOSI 5

// ─── Constants ─────────────────────────────────────────────────────────────
#define HOLD_THRESHOLD_MS 1500 // Durasi tekan untuk mode switch
#define MAX_QUEUE 50           // Maks entri offline yang disimpan
#define SYNC_INTERVAL_MS 30000 // Auto-sync tiap 30 detik

// ─── Mode System ───────────────────────────────────────────────────────────
enum DeviceMode { MODE_ABSENSI, MODE_QUEUE_VIEW };
DeviceMode currentMode = MODE_ABSENSI;
int queueViewIndex = 0;

// ─── Materials ─────────────────────────────────────────────────────────────
struct Materi {
  int id;
  char nama[64];
};
Materi materiList[30];
int totalMateri = 0;
int currentMateriIndex = 0;

// ─── Offline Queue Entry ───────────────────────────────────────────────────
struct QueueEntry {
  int materiId;
  char uid[20];
};

// ─── Hardware ──────────────────────────────────────────────────────────────
LiquidCrystal_I2C lcd(0x27, 16, 2);
MFRC522 mfrc522(PIN_RFID_SDA, PIN_RFID_RST);
Preferences prefs;

// ───────────────────────────────────────────────────────────────────────────
// OFFLINE QUEUE HELPERS (NVS via Preferences)
// Data disimpan di namespace "absensi":
//   qcount       = jumlah entri
//   qN_mid (int) = materi_id entri ke-N
//   qN_uid (str) = rfid_uid   entri ke-N
// ───────────────────────────────────────────────────────────────────────────

int getQueueCount() {
  prefs.begin("absensi", true);
  int c = prefs.getInt("qcount", 0);
  prefs.end();
  return c;
}

bool getQueueEntry(int index, QueueEntry &out) {
  int total = getQueueCount();
  if (index < 0 || index >= total)
    return false;
  char km[12], ku[12];
  sprintf(km, "q%d_mid", index);
  sprintf(ku, "q%d_uid", index);
  prefs.begin("absensi", true);
  out.materiId = prefs.getInt(km, 0);
  prefs.getString(ku, out.uid, sizeof(out.uid));
  prefs.end();
  return (out.materiId > 0);
}

void enqueueEntry(int materiId, const char *uid) {
  prefs.begin("absensi", false);
  int count = prefs.getInt("qcount", 0);
  if (count >= MAX_QUEUE) {
    Serial.printf("[QUEUE] ⚠ Antrian penuh (%d)! Entry terbaru diabaikan.\n",
                  MAX_QUEUE);
    prefs.end();
    return;
  }
  char km[12], ku[12];
  sprintf(km, "q%d_mid", count);
  sprintf(ku, "q%d_uid", count);
  prefs.putInt(km, materiId);
  prefs.putString(ku, uid);
  prefs.putInt("qcount", count + 1);
  prefs.end();
  Serial.printf("[QUEUE] ✓ Tersimpan [%d]: materi_id=%d uid=%s\n", count,
                materiId, uid);
}

void dequeueFirst() {
  prefs.begin("absensi", false);
  int count = prefs.getInt("qcount", 0);
  if (count <= 0) {
    prefs.end();
    return;
  }
  // Geser semua entry satu posisi ke depan
  for (int i = 0; i < count - 1; i++) {
    char kmF[12], kuF[12], kmT[12], kuT[12];
    sprintf(kmF, "q%d_mid", i + 1);
    sprintf(kuF, "q%d_uid", i + 1);
    sprintf(kmT, "q%d_mid", i);
    sprintf(kuT, "q%d_uid", i);
    prefs.putInt(kmT, prefs.getInt(kmF, 0));
    char buf[20];
    prefs.getString(kuF, buf, sizeof(buf));
    prefs.putString(kuT, buf);
  }
  // Hapus slot terakhir
  char km[12], ku[12];
  sprintf(km, "q%d_mid", count - 1);
  sprintf(ku, "q%d_uid", count - 1);
  prefs.remove(km);
  prefs.remove(ku);
  prefs.putInt("qcount", count - 1);
  prefs.end();
}

// ───────────────────────────────────────────────────────────────────────────
// BUZZER HELPERS
// ───────────────────────────────────────────────────────────────────────────
void beepShort() {
  digitalWrite(PIN_BUZZER, HIGH);
  delay(50);
  digitalWrite(PIN_BUZZER, LOW);
}
void beepMedium() {
  digitalWrite(PIN_BUZZER, HIGH);
  delay(120);
  digitalWrite(PIN_BUZZER, LOW);
}
void beepLong() {
  digitalWrite(PIN_BUZZER, HIGH);
  delay(500);
  digitalWrite(PIN_BUZZER, LOW);
}
void beepSuccess() {
  for (int i = 0; i < 2; i++) {
    digitalWrite(PIN_BUZZER, HIGH);
    delay(70);
    digitalWrite(PIN_BUZZER, LOW);
    delay(70);
  }
}
void beepModeSwitch() { // Dua bunyi berbeda – pertanda mode ganti
  digitalWrite(PIN_BUZZER, HIGH);
  delay(80);
  digitalWrite(PIN_BUZZER, LOW);
  delay(60);
  digitalWrite(PIN_BUZZER, HIGH);
  delay(160);
  digitalWrite(PIN_BUZZER, LOW);
}

// ───────────────────────────────────────────────────────────────────────────
// BUTTON READ (deteksi short press vs hold)
// Return: 0 = tidak ditekan, 1 = short press, 2 = hold (>HOLD_THRESHOLD_MS)
// ───────────────────────────────────────────────────────────────────────────
int readButton(int pin) {
  if (digitalRead(pin) != LOW)
    return 0;
  delay(30); // debounce
  if (digitalRead(pin) != LOW)
    return 0;

  unsigned long t = millis();
  while (digitalRead(pin) == LOW) {
    if (millis() - t > HOLD_THRESHOLD_MS) {
      while (digitalRead(pin) == LOW)
        delay(5); // tunggu dilepas
      return 2;   // HOLD
    }
    delay(5);
  }
  return 1; // SHORT PRESS
}

// ───────────────────────────────────────────────────────────────────────────
// DISPLAY
// ───────────────────────────────────────────────────────────────────────────
void updateDisplay() {
  lcd.clear();

  if (currentMode == MODE_ABSENSI) {
    if (totalMateri > 0) {
      // Baris 1: ID sesi + nama materi (potong 16 char)
      String baris1 =
          "M" + String(materiList[currentMateriIndex].id) + " " +
          String(materiList[currentMateriIndex].nama).substring(0, 13);
      lcd.setCursor(0, 0);
      lcd.print(baris1);

      // Baris 2: jumlah antrian atau petunjuk tap
      int qc = getQueueCount();
      lcd.setCursor(0, 1);
      if (qc > 0)
        lcd.print("Antrian: " + String(qc));
      else
        lcd.print("Tap kartu...");

      Serial.printf("\n[STANDBY] Sesi Aktif: ID %d - %s",
                    materiList[currentMateriIndex].id,
                    materiList[currentMateriIndex].nama);
      if (qc > 0)
        Serial.printf("  | %d antrian offline", qc);
      Serial.println();
    } else {
      lcd.setCursor(0, 0);
      lcd.print("No Materi");
      lcd.setCursor(0, 1);
      lcd.print("Cek WiFi/Server");
      Serial.println("[ERROR] Tidak ada materi. Cek koneksi.");
    }
  } else {
    // MODE_QUEUE_VIEW
    int qc = getQueueCount();
    if (qc == 0) {
      lcd.setCursor(0, 0);
      lcd.print("Antrian Kosong");
      lcd.setCursor(0, 1);
      lcd.print("Hold=Kembali");
      Serial.println("[QUEUE VIEW] Antrian kosong.");
    } else {
      if (queueViewIndex >= qc)
        queueViewIndex = 0;
      QueueEntry e;
      getQueueEntry(queueViewIndex, e);
      // Baris 1: nomor / total dan materi id
      lcd.setCursor(0, 0);
      lcd.print("#" + String(queueViewIndex + 1) + "/" + String(qc) +
                " M:" + String(e.materiId));
      // Baris 2: UID (maks 16 char)
      lcd.setCursor(0, 1);
      lcd.print(String(e.uid).substring(0, 16));
      Serial.printf("[QUEUE VIEW] %d/%d | materi_id=%d | uid=%s\n",
                    queueViewIndex + 1, qc, e.materiId, e.uid);
    }
  }
}

// ───────────────────────────────────────────────────────────────────────────
// FETCH MATERIALS FROM SERVER
// ───────────────────────────────────────────────────────────────────────────
bool fetchMaterials() {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("[HTTP] fetchMaterials: WiFi tidak terhubung!");
    return false;
  }
  WiFiClientSecure client;
  client.setInsecure();
  HTTPClient http;
  String url = String(serverUrl) + "/api/materi";

  Serial.println("=== DEBUG fetchMaterials ===");
  Serial.print("URL    : ");
  Serial.println(url);
  Serial.print("WiFi IP: ");
  Serial.println(WiFi.localIP().toString());
  Serial.print("RSSI   : ");
  Serial.print(WiFi.RSSI());
  Serial.println(" dBm");

  bool beginOk = http.begin(client, url);
  Serial.print("http.begin: ");
  Serial.println(beginOk ? "OK" : "GAGAL");

  if (!beginOk) {
    Serial.println("[HTTP] http.begin() gagal! URL mungkin tidak valid.");
    return false;
  }

  http.setUserAgent("Mozilla/5.0 (Windows NT 10.0; Win64; x64)");

  int code = http.GET();
  Serial.print("HTTP Code : ");
  Serial.println(code);

  bool ok = false;
  if (code == HTTP_CODE_OK) {
    String payload = http.getString();
    Serial.print("Response  : ");
    Serial.println(payload.substring(0, 200)); // print 200 char pertama
    DynamicJsonDocument doc(4096);
    DeserializationError err = deserializeJson(doc, payload);
    if (!err) {
      JsonArray arr = doc.as<JsonArray>();
      totalMateri = min((int)arr.size(), 30);
      for (int i = 0; i < totalMateri; i++) {
        materiList[i].id = arr[i]["id"];
        String n = arr[i]["nama_materi"];
        n.toCharArray(materiList[i].nama, 64);
      }
      currentMateriIndex = 0;
      ok = true;
      Serial.printf("[SYNC] %d materi tersinkronisasi.\n", totalMateri);
    } else {
      Serial.print("[JSON] Parse error: ");
      Serial.println(err.c_str());
    }
  } else if (code < 0) {
    Serial.print("[HTTP] Error string: ");
    Serial.println(http.errorToString(code));
  } else {
    Serial.print("[HTTP] Response body: ");
    Serial.println(http.getString().substring(0, 200));
  }
  http.end();
  Serial.println("===========================");
  return ok;
}

// ───────────────────────────────────────────────────────────────────────────
// LOAD DEFAULT MATERIALS (OFFLINE FALLBACK)
// ───────────────────────────────────────────────────────────────────────────
void loadDefaultMaterials() {
  Serial.println("[SYSTEM] Memuat 14 materi default (Offline)...");
  totalMateri = 14;
  materiList[0].id = 1;
  strcpy(materiList[0].nama, "Ke-ASWAJA-an 2");
  materiList[1].id = 2;
  strcpy(materiList[1].nama, "Ke-NU-an 2");
  materiList[2].id = 3;
  strcpy(materiList[2].nama, "Ke-INDONESIA-an 2");
  materiList[3].id = 4;
  strcpy(materiList[3].nama, "Ke-IPNU/IPPNU-an 2");
  materiList[4].id = 5;
  strcpy(materiList[4].nama, "Tradisi Amaliyah NU");
  materiList[5].id = 6;
  strcpy(materiList[5].nama, "Kepemimpinan");
  materiList[6].id = 7;
  strcpy(materiList[6].nama, "Manajemen Organisasi");
  materiList[7].id = 8;
  strcpy(materiList[7].nama, "Komunikasi&Kerjasama");
  materiList[8].id = 9;
  strcpy(materiList[8].nama, "SPS");
  materiList[9].id = 10;
  strcpy(materiList[9].nama, "Diskusi&Persidangan");
  materiList[10].id = 11;
  strcpy(materiList[10].nama, "TOR & Proposal");
  materiList[11].id = 12;
  strcpy(materiList[11].nama, "Manajemen Konflik");
  materiList[12].id = 13;
  strcpy(materiList[12].nama, "Networking&Lobbying");
  materiList[13].id = 14;
  strcpy(materiList[13].nama, "Analisis Gender");
  currentMateriIndex = 0;
}

// ───────────────────────────────────────────────────────────────────────────
// SYNC OFFLINE QUEUE TO SERVER
// ───────────────────────────────────────────────────────────────────────────
void syncOfflineQueue() {
  if (WiFi.status() != WL_CONNECTED)
    return;
  int total = getQueueCount();
  if (total == 0)
    return;

  Serial.printf("[SYNC] Memulai sinkronisasi %d antrian offline...\n", total);
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Sync Antrian...");
  lcd.setCursor(0, 1);
  lcd.print(String(total) + " entri");
  delay(600);

  int synced = 0;
  while (getQueueCount() > 0 && WiFi.status() == WL_CONNECTED) {
    QueueEntry e;
    if (!getQueueEntry(0, e))
      break;

    WiFiClientSecure client;
    client.setInsecure();
    HTTPClient http;
    String url = String(serverUrl) + "/api/absensi";
    http.begin(client, url);
    http.setUserAgent("Mozilla/5.0 (Windows NT 10.0; Win64; x64)");
    http.addHeader("Content-Type", "application/json");

    StaticJsonDocument<200> doc;
    doc["materi_id"] = e.materiId;
    doc["rfid_uid"] = e.uid;
    String body;
    serializeJson(doc, body);

    int code = http.POST(body);
    bool sent = false;
    if (code > 0) {
      String resp = http.getString();
      StaticJsonDocument<300> res;
      if (!deserializeJson(res, resp)) {
        String st = res["status"] | "error";
        if (st == "success" || st == "warning")
          sent = true;
      }
    }
    http.end();

    if (sent) {
      Serial.printf("[SYNC] ✓ Terkirim: materi_id=%d uid=%s\n", e.materiId,
                    e.uid);
      dequeueFirst();
      synced++;
      delay(300);
    } else {
      Serial.println("[SYNC] ✗ Gagal, sinkronisasi dihentikan sementara.");
      break;
    }
  }

  int remaining = getQueueCount();
  Serial.printf("[SYNC] Selesai: %d berhasil, %d tersisa.\n", synced,
                remaining);
  if (synced > 0) {
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Sync OK: +" + String(synced));
    lcd.setCursor(0, 1);
    lcd.print("Sisa antrian: " + String(remaining));
    beepSuccess();
    delay(1800);
  }
}

// ───────────────────────────────────────────────────────────────────────────
// SETUP
// ───────────────────────────────────────────────────────────────────────────
void setup() {
  Serial.begin(115200);
  delay(800);
  Serial.println("\n==========================================");
  Serial.println("  LAKMUD V - RFID Absensi ESP32  v2.0    ");
  Serial.println("==========================================");

  pinMode(PIN_BTN_PREV, INPUT_PULLUP);
  pinMode(PIN_BTN_NEXT, INPUT_PULLUP);
  pinMode(PIN_BUZZER, OUTPUT);
  digitalWrite(PIN_BUZZER, LOW);

  lcd.init();
  lcd.backlight();
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("LAKMUD V  v2.0");
  lcd.setCursor(0, 1);
  lcd.print("Initializing...");
  delay(800);

  // ── WiFi ──
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Connecting WiFi");
  lcd.setCursor(0, 1);
  lcd.print(ssid);
  WiFi.begin(ssid, password);
  int att = 0;
  while (WiFi.status() != WL_CONNECTED && att < 20) {
    delay(500);
    Serial.print(".");
    att++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\n[WIFI] Connected: " + WiFi.localIP().toString());
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("WiFi OK");
    lcd.setCursor(0, 1);
    lcd.print(WiFi.localIP().toString());
    delay(1200);

    // Sync antrian sebelum ambil materi
    syncOfflineQueue();

    // Ambil daftar materi
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Sync Materi...");
    if (fetchMaterials()) {
      lcd.setCursor(0, 1);
      lcd.print("Sync Berhasil!");
      delay(1000);
    } else {
      lcd.setCursor(0, 1);
      lcd.print("Server Gagal!");
      delay(1000);
      lcd.clear();
      lcd.setCursor(0, 0);
      lcd.print("Pakai 14 Default");
      lcd.setCursor(0, 1);
      lcd.print("(Offline Data)");
      delay(1500);
      loadDefaultMaterials();
    }
  } else {
    Serial.println("\n[WIFI] Timeout – Offline Mode aktif.");
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("WiFi Timeout!");
    lcd.setCursor(0, 1);
    lcd.print("Offline Mode");
    delay(1500);
    loadDefaultMaterials();
  }

  // ── RFID ──
  SPI.begin(SPI_SCK, SPI_MISO, SPI_MOSI, PIN_RFID_SDA);
  mfrc522.PCD_Init();
  Serial.println("[SYSTEM] RC522 RFID siap.");

  int qc = getQueueCount();
  if (qc > 0)
    Serial.printf("[QUEUE] %d entri offline menunggu sinkronisasi.\n", qc);

  beepSuccess();
  updateDisplay();
}

// ───────────────────────────────────────────────────────────────────────────
// LOOP
// ───────────────────────────────────────────────────────────────────────────
unsigned long lastSyncMs = 0;

void loop() {

  // ╔══════════════════════════════════════════╗
  // ║  BUTTON NEXT (GPIO 26)                   ║
  // ║  Short  → next item (materi / antrian)   ║
  // ║  Hold   → toggle MODE_ABSENSI ↔ QUEUE   ║
  // ╚══════════════════════════════════════════╝
  int nextBtn = readButton(PIN_BTN_NEXT);

  if (nextBtn == 2) {
    // ── HOLD: Toggle mode ──
    beepModeSwitch();
    if (currentMode == MODE_ABSENSI) {
      currentMode = MODE_QUEUE_VIEW;
      queueViewIndex = 0;
      Serial.println("[MODE] → Masuk Mode Lihat Antrian Offline.");
    } else {
      currentMode = MODE_ABSENSI;
      Serial.println("[MODE] → Kembali ke Mode Absensi.");
    }
    updateDisplay();

  } else if (nextBtn == 1) {
    beepShort();
    if (currentMode == MODE_ABSENSI) {
      currentMateriIndex = (currentMateriIndex + 1) % totalMateri;
    } else {
      int qc = getQueueCount();
      if (qc > 0)
        queueViewIndex = (queueViewIndex + 1) % qc;
    }
    updateDisplay();
  }

  // ╔══════════════════════════════════════════╗
  // ║  BUTTON PREV (GPIO 25)                   ║
  // ║  Short/Hold → prev item                  ║
  // ╚══════════════════════════════════════════╝
  int prevBtn = readButton(PIN_BTN_PREV);
  if (prevBtn >= 1) {
    beepShort();
    if (currentMode == MODE_ABSENSI) {
      currentMateriIndex =
          (currentMateriIndex == 0) ? totalMateri - 1 : currentMateriIndex - 1;
    } else {
      int qc = getQueueCount();
      if (qc > 0)
        queueViewIndex = (queueViewIndex == 0) ? qc - 1 : queueViewIndex - 1;
    }
    updateDisplay();
  }

  // ╔══════════════════════════════════════════╗
  // ║  RFID SCAN (hanya di MODE_ABSENSI)       ║
  // ╚══════════════════════════════════════════╝
  if (currentMode == MODE_ABSENSI && mfrc522.PICC_IsNewCardPresent() &&
      mfrc522.PICC_ReadCardSerial()) {

    beepMedium();

    // Baca UID → hex string
    String cardUid = "";
    for (byte i = 0; i < mfrc522.uid.size; i++) {
      if (mfrc522.uid.uidByte[i] < 0x10)
        cardUid += "0";
      cardUid += String(mfrc522.uid.uidByte[i], HEX);
    }
    cardUid.toUpperCase();

    Serial.println("\n------------------------------------------");
    Serial.print("[RFID] Kartu ditap! UID: ");
    Serial.println(cardUid);

    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Memproses...");
    lcd.setCursor(0, 1);
    lcd.print(cardUid.substring(0, 16));

    bool serverOk = false;

    if (WiFi.status() == WL_CONNECTED) {
      WiFiClientSecure client;
      client.setInsecure();
      HTTPClient http;
      String url = String(serverUrl) + "/api/absensi";
      http.begin(client, url);
      http.setUserAgent("Mozilla/5.0 (Windows NT 10.0; Win64; x64)");
      http.addHeader("Content-Type", "application/json");

      StaticJsonDocument<200> doc;
      doc["materi_id"] = materiList[currentMateriIndex].id;
      doc["rfid_uid"] = cardUid;
      String body;
      serializeJson(doc, body);
      Serial.print("[HTTP] POST Body: ");
      Serial.println(body);

      int code = http.POST(body);
      Serial.printf("[HTTP] Response Code: %d\n", code);
      lcd.clear();

      if (code > 0) {
        String resp = http.getString();
        Serial.println("[HTTP] Response: " + resp);
        StaticJsonDocument<300> res;
        if (!deserializeJson(res, resp)) {
          String status = res["status"] | "error";
          String name = res["name"] | "Tidak Diketahui";
          String msg = res["message"] | "Gagal";

          if (status == "success") {
            beepSuccess();
            lcd.setCursor(0, 0);
            lcd.print(name.substring(0, 16));
            lcd.setCursor(0, 1);
            lcd.print("ABSEN BERHASIL");
            serverOk = true;
            Serial.printf("[ABSENSI] ✓ BERHASIL: %s\n", name.c_str());
          } else if (status == "warning") {
            beepSuccess();
            lcd.setCursor(0, 0);
            lcd.print(name.substring(0, 16));
            lcd.setCursor(0, 1);
            lcd.print("SUDAH ABSEN");
            serverOk = true; // sudah tercatat, tidak perlu queue
            Serial.printf("[ABSENSI] ⚠ Sudah absen: %s\n", name.c_str());
          } else {
            beepLong();
            lcd.setCursor(0, 0);
            lcd.print("GAGAL ABSEN!");
            lcd.setCursor(0, 1);
            lcd.print(msg.substring(0, 16));
            Serial.printf("[ABSENSI] ✗ GAGAL: %s\n", msg.c_str());
          }
        } else {
          beepLong();
          lcd.setCursor(0, 0);
          lcd.print("Parse Error!");
          lcd.setCursor(0, 1);
          lcd.print("Tap Ulang...");
        }
      } else {
        Serial.printf("[HTTP] Request gagal: %s\n",
                      http.errorToString(code).c_str());
        beepLong();
        lcd.setCursor(0, 0);
        lcd.print("Server Timeout!");
        lcd.setCursor(0, 1);
        lcd.print("Simpan offline...");
      }
      http.end();

    } else {
      // Tidak ada WiFi
      Serial.println("[WIFI] Offline! Menyimpan ke antrian...");
      beepLong();
      lcd.clear();
      lcd.setCursor(0, 0);
      lcd.print("Jaringan Offline");
      lcd.setCursor(0, 1);
      lcd.print("Simpan ke queue");
      delay(600);
    }

    // Jika tidak berhasil ke server → simpan ke antrian offline
    if (!serverOk) {
      enqueueEntry(materiList[currentMateriIndex].id, cardUid.c_str());
      int qc = getQueueCount();
      delay(500);
      lcd.clear();
      lcd.setCursor(0, 0);
      lcd.print("Tersimpan Lokal!");
      lcd.setCursor(0, 1);
      lcd.print("Antrian: " + String(qc));
    }

    mfrc522.PICC_HaltA();
    mfrc522.PCD_StopCrypto1();
    Serial.println("------------------------------------------");
    delay(2000);
    updateDisplay();
  }

  // ╔══════════════════════════════════════════════════════╗
  // ║  AUTO-SYNC periodik (tiap SYNC_INTERVAL_MS = 30 dt) ║
  // ║  Hanya berjalan di MODE_ABSENSI agar tidak           ║
  // ║  mengganggu saat sedang melihat antrian              ║
  // ╚══════════════════════════════════════════════════════╝
  if (currentMode == MODE_ABSENSI && getQueueCount() > 0 &&
      WiFi.status() == WL_CONNECTED &&
      millis() - lastSyncMs > SYNC_INTERVAL_MS) {
    lastSyncMs = millis();
    syncOfflineQueue();
    updateDisplay();
  }
}
