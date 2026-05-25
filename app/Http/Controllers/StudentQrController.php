<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class StudentQrController extends Controller
{
    // Preview: PNG (aman, pakai GD)
    public function show(Student $student)
    {
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $student->qr_token,
            size: 300,
            margin: 10
        );

        $result = $builder->build();

        return response($result->getString(), 200)
            ->header('Content-Type', $result->getMimeType()); // image/png
    }

    // Download: JPG (generate PNG dulu, lalu convert ke JPG pakai GD)
    public function download(Student $student)
    {
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $student->qr_token,
            size: 800,
            margin: 20
        );

        $result = $builder->build();
        $pngBinary = $result->getString(); // PNG bytes

        // Convert PNG bytes -> JPG bytes (GD)
        $img = imagecreatefromstring($pngBinary);
        if ($img === false) {
            abort(500, 'Gagal membuat image dari PNG. Pastikan extension GD aktif.');
        }

        // Biar JPG background putih (PNG transparan kadang jadi hitam)
        $canvas = imagecreatetruecolor(imagesx($img), imagesy($img));
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $white);
        imagecopy($canvas, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));

        ob_start();
        imagejpeg($canvas, null, 90);
        $jpgBinary = ob_get_clean();

        imagedestroy($img);
        imagedestroy($canvas);

        $filename = 'QR-' . $student->nis . '.jpg';

        return response($jpgBinary, 200)
            ->header('Content-Type', 'image/jpeg')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

        public function idCard(\App\Models\Student $student)
        {
            $pdf = Pdf::loadView('students.id_card_pdf', compact('student'))
                ->setPaper([0, 0, 260, 420], 'portrait');

            return $pdf->download('ID-Card-'.$student->nis.'.pdf');
        }
}
