<?php

namespace App\Services;

use App\Models\SuKien;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCheckinService
{
    /**
     * Ensure a QR token exists and (re)generate the QR image for check-in.
     */
    public function ensure(SuKien $suKien): SuKien
    {
        if (empty($suKien->qr_checkin_token)) {
            $suKien->qr_checkin_token = Str::uuid()->toString();
        }

        $url  = route('events.qr-checkin', $suKien->qr_checkin_token);
        $path = "qr/su_kien/{$suKien->ma_su_kien}.svg";

        Storage::disk('public')->put(
            $path,
            QrCode::format('svg')
                ->size(600)
                ->margin(1)
                ->generate($url)
        );

        $suKien->qr_code_path = $path;
        $suKien->save();

        return $suKien;
    }
}
