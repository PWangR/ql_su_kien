<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeApiController extends Controller
{
    /**
     * Generate a QR code from provided data.
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request)
    {
        $data = $request->query('data', 'No Data');
        
        // Try decoding if it looks like base64
        if ($request->has('base64')) {
            $data = base64_decode($data);
        }

        $size = $request->query('size', 300);
        $margin = $request->query('margin', 1);
        $format = strtolower($request->query('format', 'svg'));

        if (!in_array($format, ['svg', 'png'], true)) {
            $format = 'svg';
        }

        $contentType = $format === 'png' ? 'image/png' : 'image/svg+xml';

        if ($format === 'png') {
            return response(
                $this->generatePng($data, (int) $size, (int) $margin)
            )->header('Content-Type', $contentType);
        }

        return response(
            QrCode::format($format)
                ->size($size)
                ->margin($margin)
                ->generate($data)
        )->header('Content-Type', $contentType);
    }

    private function generatePng(string $data, int $size, int $margin): string
    {
        $size = max(80, min($size, 1200));
        $margin = max(0, min($margin, 20));

        $qrCode = Encoder::encode($data, ErrorCorrectionLevel::M(), 'UTF-8');
        $matrix = $qrCode->getMatrix();
        $matrixSize = $matrix->getWidth();
        $moduleSize = max(1, (int) floor($size / ($matrixSize + ($margin * 2))));
        $qrImageSize = $moduleSize * ($matrixSize + ($margin * 2));
        $offset = (int) floor(($size - $qrImageSize) / 2) + ($margin * $moduleSize);

        $image = imagecreatetruecolor($size, $size);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);

        imagefill($image, 0, 0, $white);

        for ($y = 0; $y < $matrixSize; $y++) {
            for ($x = 0; $x < $matrixSize; $x++) {
                if ($matrix->get($x, $y) !== 1) {
                    continue;
                }

                imagefilledrectangle(
                    $image,
                    $offset + ($x * $moduleSize),
                    $offset + ($y * $moduleSize),
                    $offset + (($x + 1) * $moduleSize) - 1,
                    $offset + (($y + 1) * $moduleSize) - 1,
                    $black
                );
            }
        }

        ob_start();
        imagepng($image);
        imagedestroy($image);

        return (string) ob_get_clean();
    }
}
