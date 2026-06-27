/**
 * LAKMUD V - ESP32 RFID Absensi Client Program
 * 
 * Hardware Wiring:
 * - LCD I2C 16x2: SDA -> GPIO 21, SCL -> GPIO 22
 * - RC522 RFID: 
 *   - RST  -> GPIO 15
 *   - MISO -> GPIO 4
 *   - MOSI -> GPIO 5
 *   - SCK  -> GPIO 18
 *   - SDA  -> GPIO 19 (SS)
 * - Button <- (Prev) : GPIO 25 (INPUT_PULLUP)
 * - Button -> (Next) : GPIO 26 (INPUT_PULLUP)
 * - Buzzer: GPIO 14 (Active HIGH)
 * 
 * Required Arduino Libraries:
 * - LiquidCrystal_I2C (by Frank de Brabander)
 * - MFRC522 (by Github Community)
 * - ArduinoJson (by Benoit Blanchon, v6 or v7)
 */

#include <WiFi.h>
#include <HTTPClient.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <SPI.h>
#include <MFRC522.h>
#include <ArduinoJson.h>

// WiFi Configuration
const char* ssid = "Matahary 2.4G";
const char* password = "kebunanggur";

// Server Address
const char* serverUrl = "https://5367-103-169-135-11.ngrok-free.app"; 

// Pin Definitions
#define PIN_BTN_PREV 25
#define PIN_BTN_NEXT 26
#define PIN_BUZZER   14
#define PIN_RFID_RST 15
#define PIN_RFID_SDA 19 // SS Pin

// SPI Pin Configuration (Custom SPI pins)
#define SPI_SCK  18
#define SPI_MISO 4
#define SPI_MOSI 5

// Struct for Materials
struct Materi {
  int id;
  char nama[64];
};

Materi materiList[30];
int totalMateri = 0;
int currentMateriIndex = 0;

// Hardware Interfaces
LiquidCrystal_I2C lcd(0x27, 16, 2); // LCD Address 0x27, 16 columns, 2 rows
MFRC522 mfrc522(PIN_RFID_SDA, PIN_RFID_RST);

// Sound alert functions with Serial logging
void beepShort() {
  Serial.println("[BUZZER] Beep Short (Tombol ditekan)");
  digitalWrite(PIN_BUZZER, HIGH);
  delay(50);
  digitalWrite(PIN_BUZZER, LOW);
}

void beepMedium() {
  Serial.println("[BUZZER] Beep Medium (Kartu terdeteksi)");
  digitalWrite(PIN_BUZZER, HIGH);
  delay(100);
  digitalWrite(PIN_BUZZER, LOW);
}

void beepLong() {
  Serial.println("[BUZZER] Beep Long (Absensi gagal)");
  digitalWrite(PIN_BUZZER, HIGH);
  delay(500);
  digitalWrite(PIN_BUZZER, LOW);
}

void beepSuccess() {
  Serial.println("[BUZZER] Beep Success (Absensi berhasil)");
  digitalWrite(PIN_BUZZER, HIGH);
  delay(60);
  digitalWrite(PIN_BUZZER, LOW);
  delay(60);
  digitalWrite(PIN_BUZZER, HIGH);
  delay(60);
  digitalWrite(PIN_BUZZER, LOW);
}

// Display standby screen and print state to Serial Monitor
void updateDisplay() {
  lcd.clear();
  if (totalMateri > 0) {
    String line1 = "ID: " + String(materiList[currentMateriIndex].id);
    String line2 = String(materiList[currentMateriIndex].nama).substring(0, 16); // Truncate to 16 chars for LCD
    
    lcd.setCursor(0, 0);
    lcd.print(line1);
    lcd.setCursor(0, 1);
    lcd.print(line2);
    
    Serial.println("\n------------------------------------------");
    Serial.print("[STANDBY] Sesi Aktif: ");
    Serial.print(line1);
    Serial.print(" - ");
    Serial.println(line2);
    Serial.println("Silakan tap kartu RFID Anda...");
    Serial.println("------------------------------------------");
  } else {
    lcd.setCursor(0, 0);
    lcd.print("No Materials");
    lcd.setCursor(0, 1);
    lcd.print("WiFi/Server Err");
    
    Serial.println("\n[ERROR] Tidak ada materi terunduh. Cek koneksi WiFi/Server.");
  }
}

// Populate default list in case of connection failure
void loadDefaultMaterials() {
  Serial.println("[SYSTEM] Mengisi 14 Materi bawaan (Offline Mode)...");
  totalMateri = 14;
  
  materiList[0].id = 1; strcpy(materiList[0].nama, "Ke-ASWAJA-an 2");
  materiList[1].id = 2; strcpy(materiList[1].nama, "Ke-NU-an 2");
  materiList[2].id = 3; strcpy(materiList[2].nama, "Ke-INDONESIA-an 2");
  materiList[3].id = 4; strcpy(materiList[3].nama, "Ke-IPNU IPPNU-an 2");
  materiList[4].id = 5; strcpy(materiList[4].nama, "Tradisi Amaliyah NU");
  materiList[5].id = 6; strcpy(materiList[5].nama, "Kepemimpinan");
  materiList[6].id = 7; strcpy(materiList[6].nama, "Manajemen Organisasi");
  materiList[7].id = 8; strcpy(materiList[7].nama, "Komunikasi dan Kerjasama");
  materiList[8].id = 9; strcpy(materiList[8].nama, "Scientific Problem Solving (SPS)");
  materiList[9].id = 10; strcpy(materiList[9].nama, "Teknik Diskusi dan Persidangan");
  materiList[10].id = 11; strcpy(materiList[10].nama, "Teknik Pembuatan TOR & Proposal");
  materiList[11].id = 12; strcpy(materiList[11].nama, "Manajemen Konflik");
  materiList[12].id = 13; strcpy(materiList[12].nama, "Networking and Lobbying");
  materiList[13].id = 14; strcpy(materiList[13].nama, "Analisis Gender");

  for (int i = 0; i < totalMateri; i++) {
    Serial.printf("  -> Offline Sesi %d: %s\n", materiList[i].id, materiList[i].nama);
  }
  currentMateriIndex = 0;
}

// Fetch materials from API
bool fetchMaterials() {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("[HTTP] Gagal mengambil materi: WiFi terputus.");
    return false;
  }

  HTTPClient http;
  String url = String(serverUrl) + "/api/materi";
  Serial.print("[HTTP] Mengambil materi dari: ");
  Serial.println(url);
  http.begin(url);
  
  int httpResponseCode = http.GET();
  bool success = false;

  Serial.printf("[HTTP] Kode Response GET: %d\n", httpResponseCode);

  if (httpResponseCode == HTTP_CODE_OK) {
    String payload = http.getString();
    Serial.println("[HTTP] Payload Materi diterima:");
    Serial.println(payload);
    
    // Parse JSON
    DynamicJsonDocument doc(4096);
    DeserializationError error = deserializeJson(doc, payload);
    
    if (!error) {
      JsonArray arr = doc.as<JsonArray>();
      totalMateri = arr.size();
      if (totalMateri > 30) totalMateri = 30; // Safety cap

      Serial.printf("[SYSTEM] Sinkronisasi %d materi berhasil:\n", totalMateri);
      for (int i = 0; i < totalMateri; i++) {
        materiList[i].id = arr[i]["id"];
        String name = arr[i]["nama_materi"];
        name.toCharArray(materiList[i].nama, sizeof(materiList[i].nama));
        Serial.printf("  [%d] ID: %d | %s\n", i + 1, materiList[i].id, materiList[i].nama);
      }
      currentMateriIndex = 0;
      success = true;
    } else {
      Serial.print("[ERROR] Gagal parse JSON: ");
      Serial.println(error.c_str());
    }
  } else {
    Serial.println("[ERROR] Server mengembalikan respon non-200.");
  }

  http.end();
  return success;
}

void setup() {
  Serial.begin(115200);
  delay(1000);
  
  Serial.println("\n\n==========================================");
  Serial.println("  LAKMUD V - CLIENT ABSENSI RFID ESP32    ");
  Serial.println("==========================================");
  Serial.printf("[SYSTEM] SSID WiFi : %s\n", ssid);
  Serial.printf("[SYSTEM] Server URL: %s\n", serverUrl);

  // Setup GPIOs
  pinMode(PIN_BTN_PREV, INPUT_PULLUP);
  pinMode(PIN_BTN_NEXT, INPUT_PULLUP);
  pinMode(PIN_BUZZER, OUTPUT);
  digitalWrite(PIN_BUZZER, LOW);
  Serial.println("[SYSTEM] GPIO Buttons & Buzzer terinisialisasi.");

  // Initialize LCD
  lcd.init();
  lcd.backlight();
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("LAKMUD V RFID");
  lcd.setCursor(0, 1);
  lcd.print("Booting...");
  Serial.println("[SYSTEM] LCD I2C terinisialisasi.");

  // Connect to WiFi
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Connecting WiFi");
  lcd.setCursor(0, 1);
  lcd.print(ssid);
  
  Serial.print("[WIFI] Menghubungkan ke ");
  Serial.print(ssid);

  WiFi.begin(ssid, password);
  int wifiAttempts = 0;
  while (WiFi.status() != WL_CONNECTED && wifiAttempts < 20) {
    delay(500);
    Serial.print(".");
    lcd.setCursor(wifiAttempts % 16, 1);
    lcd.print(".");
    wifiAttempts++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\n[WIFI] Terhubung!");
    Serial.print("[WIFI] IP Address local: ");
    Serial.println(WiFi.localIP().toString());
    
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("WiFi Connected");
    lcd.setCursor(0, 1);
    lcd.print(WiFi.localIP().toString());
    delay(1500);

    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Syncing Materials");
    
    if (fetchMaterials()) {
      lcd.setCursor(0, 1);
      lcd.print("Sync Success!");
    } else {
      lcd.setCursor(0, 1);
      lcd.print("Sync Failed!");
      delay(1500);
      loadDefaultMaterials();
    }
  } else {
    Serial.println("\n[WIFI] Gagal terhubung (Timeout).");
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("WiFi Timeout!");
    lcd.setCursor(0, 1);
    lcd.print("Offline Mode");
    delay(1500);
    loadDefaultMaterials();
  }

  // Initialize Custom SPI for RC522
  Serial.println("[SYSTEM] Menginisialisasi modul SPI & RC522 RFID...");
  SPI.begin(SPI_SCK, SPI_MISO, SPI_MOSI, PIN_RFID_SDA);
  mfrc522.PCD_Init();
  Serial.println("[SYSTEM] RC522 RFID siap digunakan.");

  beepSuccess();
  updateDisplay();
}

void loop() {
  // Check for button press (active LOW)
  if (digitalRead(PIN_BTN_PREV) == LOW) {
    delay(50); // Debounce
    if (digitalRead(PIN_BTN_PREV) == LOW) {
      Serial.println("[BUTTON] Tombol PREV ditekan.");
      beepShort();
      if (currentMateriIndex == 0) {
        currentMateriIndex = totalMateri - 1;
      } else {
        currentMateriIndex--;
      }
      updateDisplay();
      while(digitalRead(PIN_BTN_PREV) == LOW); // Wait for release
    }
  }

  if (digitalRead(PIN_BTN_NEXT) == LOW) {
    delay(50); // Debounce
    if (digitalRead(PIN_BTN_NEXT) == LOW) {
      Serial.println("[BUTTON] Tombol NEXT ditekan.");
      beepShort();
      if (currentMateriIndex == totalMateri - 1) {
        currentMateriIndex = 0;
      } else {
        currentMateriIndex++;
      }
      updateDisplay();
      while(digitalRead(PIN_BTN_NEXT) == LOW); // Wait for release
    }
  }

  // Check for new RFID card
  if (mfrc522.PICC_IsNewCardPresent() && mfrc522.PICC_ReadCardSerial()) {
    beepMedium();

    // Convert UID to Hex String
    String cardUid = "";
    for (byte i = 0; i < mfrc522.uid.size; i++) {
      cardUid += String(mfrc522.uid.uidByte[i] < 0x10 ? "0" : "");
      cardUid += String(mfrc522.uid.uidByte[i], HEX);
    }
    cardUid.toUpperCase();
    
    Serial.println("\n------------------------------------------");
    Serial.print("[RFID] Kartu Ditap! UID Hex: ");
    Serial.println(cardUid);
    
    // Display Tap Progress
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Mengirim Absen...");
    lcd.setCursor(0, 1);
    lcd.print(cardUid);

    // Call API Absensi
    if (WiFi.status() == WL_CONNECTED) {
      HTTPClient http;
      String url = String(serverUrl) + "/api/absensi";
      Serial.print("[HTTP] Mengirim POST request ke: ");
      Serial.println(url);
      http.begin(url);
      http.addHeader("Content-Type", "application/json");

      // Build JSON body
      StaticJsonDocument<200> doc;
      doc["materi_id"] = materiList[currentMateriIndex].id;
      doc["rfid_uid"] = cardUid;
      String requestBody;
      serializeJson(doc, requestBody);
      
      Serial.print("[HTTP] Request Body: ");
      Serial.println(requestBody);

      int httpResponseCode = http.POST(requestBody);
      Serial.printf("[HTTP] Kode Response POST: %d\n", httpResponseCode);
      
      lcd.clear();
      if (httpResponseCode > 0) {
        String responsePayload = http.getString();
        Serial.println("[HTTP] Response Body:");
        Serial.println(responsePayload);

        StaticJsonDocument<300> resDoc;
        DeserializationError err = deserializeJson(resDoc, responsePayload);
        
        if (!err) {
          String status = resDoc["status"] | "error";
          String message = resDoc["message"] | "Gagal";
          String name = resDoc["name"] | "Tidak Diketahui";

          if (status == "success") {
            Serial.printf("[ABSENSI] BERHASIL: %s | Sesi ID: %d\n", name.c_str(), materiList[currentMateriIndex].id);
            beepSuccess();
            lcd.setCursor(0, 0);
            lcd.print(name.substring(0, 16));
            lcd.setCursor(0, 1);
            lcd.print("ABSEN BERHASIL");
          } else if (status == "warning") {
            Serial.printf("[ABSENSI] PERINGATAN: %s sudah terabsen sebelumnya.\n", name.c_str());
            beepSuccess();
            lcd.setCursor(0, 0);
            lcd.print(name.substring(0, 16));
            lcd.setCursor(0, 1);
            lcd.print("SUDAH ABSEN");
          } else {
            Serial.printf("[ABSENSI] GAGAL: %s\n", message.c_str());
            beepLong();
            lcd.setCursor(0, 0);
            lcd.print("GAGAL ABSEN!");
            lcd.setCursor(0, 1);
            lcd.print(message.substring(0, 16));
          }
        } else {
          Serial.print("[ERROR] Gagal parsing respon JSON: ");
          Serial.println(err.c_str());
          beepLong();
          lcd.setCursor(0, 0);
          lcd.print("RESPON ERROR!");
          lcd.setCursor(0, 1);
          lcd.print("JSON Parse Err");
        }
      } else {
        Serial.printf("[ERROR] HTTP Request POST gagal. Error: %s\n", http.errorToString(httpResponseCode).c_str());
        beepLong();
        lcd.setCursor(0, 0);
        lcd.print("ABSEN GAGAL!");
        lcd.setCursor(0, 1);
        lcd.print("RTO / Jaringan");
      }
      http.end();
    } else {
      Serial.println("[ERROR] Offline! Tidak dapat mengirim absensi.");
      beepLong();
      lcd.clear();
      lcd.setCursor(0, 0);
      lcd.print("ABSEN GAGAL!");
      lcd.setCursor(0, 1);
      lcd.print("Jaringan Offline");
    }

    // Halt PICC
    mfrc522.PICC_HaltA();
    mfrc522.PCD_StopCrypto1();

    Serial.println("------------------------------------------");
    delay(2000);
    updateDisplay();
  }
}
