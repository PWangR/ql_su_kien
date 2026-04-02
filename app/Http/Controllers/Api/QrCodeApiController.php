<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

        // Return SVG response
        return response(
            QrCode::format('svg')
                ->size($size)
                ->margin($margin)
                ->generate($data)
        )->header('Content-Type', 'image/svg+xml');
    }
}
