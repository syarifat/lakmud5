<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class IdCardController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['peserta', 'pendaftar'])->with('pendaftaran');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('pendaftaran', function($qp) use ($search) {
                      $qp->where('delegasi', 'like', "%{$search}%");
                  });
            });
        }

        $pesertas = $query->latest()->get();

        return view('admin.idcard.index', compact('pesertas'));
    }

    public function download($id)
    {
        $peserta = User::whereIn('role', ['peserta', 'pendaftar'])->with('pendaftaran')->findOrFail($id);

        // Path to the base template image
        $templatePath = public_path('storage/file_template/template_idcard.PNG');
        if (!file_exists($templatePath)) {
            abort(404, 'Template ID Card tidak ditemukan.');
        }

        // Load the background template image
        $im = imagecreatefrompng($templatePath);
        if (!$im) {
            abort(500, 'Gagal memuat template ID Card.');
        }

        // Ensure alpha blending and save alpha options are correct for PNG
        imagealphablending($im, true);
        imagesavealpha($im, true);

        // Coordinates for the stamp container (pixel equivalents for 1276 x 2022 image)
        $stamp_left = 366;
        $stamp_top = 624;
        $stamp_width = 544;
        $stamp_height = 704;

        // Load and place participant's photo or default silhouette
        $photoUploaded = false;
        if ($peserta->pendaftaran && !empty($peserta->pendaftaran->file_foto)) {
            $photoPath = public_path('storage/' . $peserta->pendaftaran->file_foto);
            if (is_file($photoPath)) {
                $photo = $this->loadImage($photoPath);
                if ($photo) {
                    $photoUploaded = true;
                    $photoW = imagesx($photo);
                    $photoH = imagesy($photo);

                    // Center-crop and resize the photo to fit 544 x 704
                    $targetW = 544;
                    $targetH = 704;
                    $targetRatio = $targetW / $targetH;
                    $photoRatio = $photoW / $photoH;

                    if ($photoRatio > $targetRatio) {
                        // Wider: crop left/right
                        $srcH = $photoH;
                        $srcW = (int)($photoH * $targetRatio);
                        $srcY = 0;
                        $srcX = (int)(($photoW - $srcW) / 2);
                    } else {
                        // Taller: crop top/bottom
                        $srcW = $photoW;
                        $srcH = (int)($photoW / $targetRatio);
                        $srcX = 0;
                        $srcY = (int)(($photoH - $srcH) / 2);
                    }

                    // Copy photo onto the card image at stamp position
                    imagecopyresampled($im, $photo, $stamp_left, $stamp_top, $srcX, $srcY, $targetW, $targetH, $srcW, $srcH);
                    imagedestroy($photo);
                }
            }
        }

        if (!$photoUploaded) {
            // Draw default golden background
            $goldColor = imagecolorallocate($im, 204, 160, 52); // Hex #cca034
            imagefilledrectangle($im, $stamp_left, $stamp_top, $stamp_left + $stamp_width, $stamp_top + $stamp_height, $goldColor);

            // Draw white silhouette placeholder
            $whiteColor = imagecolorallocate($im, 255, 255, 255);
            // Circle head (center: stamp_left + 272, stamp_top + 240)
            imagefilledellipse($im, $stamp_left + 272, $stamp_top + 240, 224, 224, $whiteColor);
            // Arch body (bottom: stamp_top + 704, height: 240, width: 416)
            imagefilledrectangle($im, $stamp_left + 64, $stamp_top + 672, $stamp_left + 64 + 416, $stamp_top + 704, $whiteColor);
            imagefilledarc($im, $stamp_left + 272, $stamp_top + 672, 416, 416, 180, 360, $whiteColor, IMG_ARC_PIE);
        }

        // Corner-rounding pixel-loop with background color #F5F2E9 (RGB 245, 242, 233)
        $bgColor = imagecolorallocate($im, 245, 242, 233);
        $r = 25;
        for ($i = 0; $i < $r; $i++) {
            for ($j = 0; $j < $r; $j++) {
                // Top-Left
                if (($r - $i) * ($r - $i) + ($r - $j) * ($r - $j) > $r * $r) {
                    imagesetpixel($im, $stamp_left + $i, $stamp_top + $j, $bgColor);
                }
                // Top-Right
                if (($r - $i) * ($r - $i) + ($r - $j) * ($r - $j) > $r * $r) {
                    imagesetpixel($im, $stamp_left + $stamp_width - 1 - $i, $stamp_top + $j, $bgColor);
                }
                // Bottom-Left
                if (($r - $i) * ($r - $i) + ($r - $j) * ($r - $j) > $r * $r) {
                    imagesetpixel($im, $stamp_left + $i, $stamp_top + $stamp_height - 1 - $j, $bgColor);
                }
                // Bottom-Right
                if (($r - $i) * ($r - $i) + ($r - $j) * ($r - $j) > $r * $r) {
                    imagesetpixel($im, $stamp_left + $stamp_width - 1 - $i, $stamp_top + $stamp_height - 1 - $j, $bgColor);
                }
            }
        }

        // Write Nama and Delegasi text details
        $fontPath = base_path('vendor/dompdf/dompdf/lib/fonts/DejaVuSans-Bold.ttf');
        $labelColor = imagecolorallocate($im, 13, 42, 74);
        $valueColor = imagecolorallocate($im, 0, 0, 0);

        // Calculate dynamic font sizes to fit within width limit
        $maxWidth = 732;

        // Nama
        $nameFontSize = 34;
        do {
            $bbox = imagettfbbox($nameFontSize, 0, $fontPath, $peserta->name);
            $textWidth = $bbox[2] - $bbox[0];
            if ($textWidth > $maxWidth && $nameFontSize > 18) {
                $nameFontSize -= 2;
            } else {
                break;
            }
        } while (true);

        // Delegasi
        $delegasiText = $peserta->pendaftaran?->delegasi ?? 'Belum Ditentukan';
        $delegasiFontSize = 30;
        do {
            $bbox = imagettfbbox($delegasiFontSize, 0, $fontPath, $delegasiText);
            $textWidth = $bbox[2] - $bbox[0];
            if ($textWidth > $maxWidth && $delegasiFontSize > 16) {
                $delegasiFontSize -= 2;
            } else {
                break;
            }
        } while (true);

        // Y baselines (moved lower: 1755px and 1900px)
        $namaY = 1755;
        $delegasiY = 1900;

        // Draw Nama
        imagettftext($im, 36, 0, 144, $namaY, $labelColor, $fontPath, "Nama:");
        imagettftext($im, $nameFontSize, 0, 400, $namaY, $valueColor, $fontPath, $peserta->name);
        imagefilledrectangle($im, 400, $namaY + 10, 1132, $namaY + 18, $labelColor);

        // Draw Delegasi
        imagettftext($im, 36, 0, 144, $delegasiY, $labelColor, $fontPath, "Delegasi:");
        imagettftext($im, $delegasiFontSize, 0, 400, $delegasiY, $valueColor, $fontPath, $delegasiText);
        imagefilledrectangle($im, 400, $delegasiY + 10, 1132, $delegasiY + 18, $labelColor);

        // Capture image output stream
        ob_start();
        imagepng($im);
        $imageContent = ob_get_clean();
        imagedestroy($im);

        // Sanitized filename: [name]_[delegation].png
        $safeName = str_replace(' ', '_', preg_replace('/[^A-Za-z0-9 ]/', '', $peserta->name));
        $safeDelegasi = str_replace(' ', '_', preg_replace('/[^A-Za-z0-9 ]/', '', $delegasiText));
        $filename = $safeName . '_' . $safeDelegasi . '.png';

        return response($imageContent)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    private function loadImage($path)
    {
        if (!file_exists($path)) return false;
        $info = getimagesize($path);
        if (!$info) return false;
        switch ($info[2]) {
            case IMAGETYPE_JPEG:
                return imagecreatefromjpeg($path);
            case IMAGETYPE_PNG:
                return imagecreatefrompng($path);
            case IMAGETYPE_GIF:
                return imagecreatefromgif($path);
            case IMAGETYPE_WEBP:
                if (function_exists('imagecreatefromwebp')) {
                    return imagecreatefromwebp($path);
                }
                break;
        }
        return false;
    }

    public function downloadAll(Request $request)
    {
        $pesertas = User::whereIn('role', ['peserta', 'pendaftar'])->with('pendaftaran')->latest()->get();

        if ($pesertas->isEmpty()) {
            return redirect()->back()->with('status', 'Tidak ada data peserta untuk diexport.');
        }

        $pdf = Pdf::loadView('admin.idcard.pdf_all', compact('pesertas'));
        $pdf->setPaper([0, 0, 398, 632]);

        return $pdf->download('ID_Card_Peserta_Semua.pdf');
    }
}
