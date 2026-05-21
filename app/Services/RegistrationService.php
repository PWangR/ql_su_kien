<?php

namespace App\Services;

use App\Models\DangKy;
use App\Models\LichSuDiem;
use App\Models\SuKien;
use App\Models\ChiTietDiemDanh;
use Carbon\Carbon;

class RegistrationService
{
    /**
     * Đăng ký tham gia sự kiện
     * Chỉ cho phép đăng ký khi sự kiện chưa bắt đầu
     */
    public function registerEvent($userId, $eventId)
    {
        $event = SuKien::find($eventId);

        if (!$event) {
            return ['success' => false, 'message' => 'Sự kiện không tồn tại'];
        }

        // Kiểm tra sự kiện đã bắt đầu chưa
        if ($event->thoi_gian_bat_dau <= now()) {
            return ['success' => false, 'message' => 'Sự kiện đã bắt đầu, không thể đăng ký'];
        }

        if ($event->so_luong_hien_tai >= $event->so_luong_toi_da) {
            return ['success' => false, 'message' => 'Sự kiện đã đủ số lượng'];
        }

        // Kiểm tra đã đăng ký chưa
        $existingRegistration = DangKy::where('ma_sinh_vien', $userId)
            ->where('ma_su_kien', $eventId)
            ->whereNull('deleted_at')
            ->first();

        if ($existingRegistration) {
            return ['success' => false, 'message' => 'Bạn đã đăng ký sự kiện này'];
        }

        $registration = DangKy::create([
            'ma_sinh_vien' => $userId,
            'ma_su_kien' => $eventId,
            'trang_thai_tham_gia' => 'da_dang_ky',
        ]);

        // Cập nhật số lượng hiện tại
        $event->increment('so_luong_hien_tai');

        return ['success' => true, 'message' => 'Đăng ký thành công', 'data' => $registration];
    }

    /**
     * Hủy đăng ký
     * Chỉ cho phép hủy khi sự kiện chưa bắt đầu
     */
    public function cancelRegistration($userId, $eventId)
    {
        $registration = DangKy::where('ma_sinh_vien', $userId)
            ->where('ma_su_kien', $eventId)
            ->first();

        if (!$registration) {
            return ['success' => false, 'message' => 'Đăng ký không tồn tại'];
        }

        $event = $registration->suKien;

        // Kiểm tra sự kiện đã bắt đầu chưa
        if ($event->thoi_gian_bat_dau <= now()) {
            return ['success' => false, 'message' => 'Sự kiện đã bắt đầu, không thể hủy đăng ký'];
        }

        $registration->delete();

        // Cập nhật số lượng hiện tại
        $event->decrement('so_luong_hien_tai');

        return ['success' => true, 'message' => 'Hủy đăng ký thành công'];
    }

    /**
     * Cập nhật trạng thái tham gia
     */
    public function updateParticipationStatus($registrationId, $status)
    {
        $registration = DangKy::find($registrationId);

        if (!$registration) {
            return ['success' => false, 'message' => 'Đăng ký không tồn tại'];
        }

        $validStatuses = ['da_dang_ky', 'da_tham_gia', 'vang_mat', 'chua_du_dieu_kien', 'huy'];
        if (!in_array($status, $validStatuses)) {
            return ['success' => false, 'message' => 'Trạng thái không hợp lệ'];
        }

        $registration->update(['trang_thai_tham_gia' => $status]);

        // Nếu đã tham gia, cộng điểm (nếu chưa cộng)
        if ($status === 'da_tham_gia' && !LichSuDiem::where('ma_dang_ky', $registrationId)->exists()) {
            $event = $registration->suKien;
            LichSuDiem::create([
                'ma_sinh_vien' => $registration->ma_sinh_vien,
                'ma_su_kien' => $registration->ma_su_kien,
                'ma_dang_ky' => $registrationId,
                'diem' => $event->diem_cong,
                'nguon' => 'tham_gia_su_kien',
            ]);
        }

        return ['success' => true, 'message' => 'Cập nhật trạng thái thành công'];
    }

    /**
     * Điểm danh bằng QR - Luồng mới
     * Theo dõi loại điểm danh (đầu buổi/cuối buổi)
     * Tự động cộng điểm khi đủ 2 lần điểm danh
     */
    public function checkInEvent($userId, $eventId, $loaiDiemDanh = 'dau_buoi')
    {
        if (!in_array($loaiDiemDanh, ['dau_buoi', 'cuoi_buoi'], true)) {
            return ['success' => false, 'message' => 'Loại điểm danh không hợp lệ', 'status' => 422];
        }

        $event = SuKien::find($eventId);

        if (!$event) {
            return ['success' => false, 'message' => 'Sự kiện không tồn tại', 'status' => 404];
        }

        if ($event->trang_thai === 'huy') {
            return ['success' => false, 'message' => 'Sự kiện đã bị hủy.', 'status' => 400];
        }

        $dangKy = DangKy::withTrashed()
            ->where('ma_sinh_vien', $userId)
            ->where('ma_su_kien', $eventId)
            ->first();

        // Nếu chưa đăng ký, tạo mới (cho phép điểm danh QR mà không cần đăng ký trước)
        if (!$dangKy) {
            if ($event->so_luong_toi_da > 0 && $event->so_luong_hien_tai >= $event->so_luong_toi_da) {
                return ['success' => false, 'message' => 'Sự kiện đã đủ số lượng tham gia.', 'status' => 400];
            }

            $dangKy = DangKy::create([
                'ma_sinh_vien' => $userId,
                'ma_su_kien' => $eventId,
                'trang_thai_tham_gia' => 'da_dang_ky'
            ]);
            $event->increment('so_luong_hien_tai');
        } elseif ($dangKy->trashed()) {
            $dangKy->restore();
        }

        $soLanYeuCau = $this->requiredCheckinCount($event);
        $soLanDaCo = ChiTietDiemDanh::where('ma_dang_ky', $dangKy->ma_dang_ky)->count();

        if ($soLanDaCo >= $soLanYeuCau) {
            $this->markQualifiedAndAwardPoints($dangKy, $event);
            $dangKy->load('chiTietDiemDanh');

            return [
                'success' => false,
                'message' => 'Bạn đã điểm danh đủ số lần yêu cầu cho sự kiện này!',
                'status' => 400,
                'data' => $this->checkinResponseData($dangKy, $soLanDaCo, $soLanYeuCau),
            ];
        }

        // Kiểm tra đã điểm danh loại này chưa
        $existingCheckin = ChiTietDiemDanh::where('ma_dang_ky', $dangKy->ma_dang_ky)
            ->where('loai_diem_danh', $loaiDiemDanh)
            ->first();

        if ($existingCheckin) {
            return ['success' => false, 'message' => "Bạn đã điểm danh {$loaiDiemDanh}!", 'status' => 400];
        }

        // Tạo chi tiết điểm danh
        ChiTietDiemDanh::create([
            'ma_dang_ky' => $dangKy->ma_dang_ky,
            'ma_su_kien' => $eventId,
            'ma_sinh_vien' => $userId,
            'loai_diem_danh' => $loaiDiemDanh,
            'diem_danh_at' => now(),
        ]);

        // Cập nhật trạng thái thành đã tham gia (nếu chưa)
        if (in_array($dangKy->trang_thai_tham_gia, ['da_dang_ky', 'chua_du_dieu_kien'], true)) {
            $dangKy->update(['trang_thai_tham_gia' => 'da_tham_gia']);
            $dangKy->refresh();
        }

        // Kiểm tra nếu đã điểm danh đủ số lần yêu cầu → cộng điểm
        $soLanDiemDanh = ChiTietDiemDanh::where('ma_dang_ky', $dangKy->ma_dang_ky)->count();

        if ($soLanDiemDanh >= $soLanYeuCau) {
            $this->markQualifiedAndAwardPoints($dangKy, $event);
        }

        $dangKy->load('chiTietDiemDanh');

        return [
            'success' => true,
            'message' => 'Điểm danh thành công!',
            'status' => 200,
            'data' => [
                'registration' => $dangKy,
                ...$this->checkinResponseData($dangKy, $soLanDiemDanh, $soLanYeuCau),
            ],
        ];
    }

    /**
     * Lấy lịch sử tham gia của người dùng
     */
    /**
     * Admin/ban to chuc quet QR ca nhan cua sinh vien.
     * Mot lan quet duoc tinh nhu diem danh du ca dau buoi va cuoi buoi.
     */
    public function adminCheckInStudent($userId, $eventId)
    {
        return \DB::transaction(function () use ($userId, $eventId) {
            $event = SuKien::find($eventId);

            if (!$event) {
                return ['success' => false, 'message' => 'Su kien khong ton tai', 'status' => 404];
            }

            if ($event->trang_thai === 'huy') {
                return ['success' => false, 'message' => 'Su kien da bi huy.', 'status' => 400];
            }

            $dangKy = DangKy::withTrashed()
                ->where('ma_sinh_vien', $userId)
                ->where('ma_su_kien', $eventId)
                ->first();

            if (!$dangKy) {
                if ($event->so_luong_toi_da > 0 && $event->so_luong_hien_tai >= $event->so_luong_toi_da) {
                    return ['success' => false, 'message' => 'Su kien da du so luong tham gia.', 'status' => 400];
                }

                $dangKy = DangKy::create([
                    'ma_sinh_vien' => $userId,
                    'ma_su_kien' => $eventId,
                    'trang_thai_tham_gia' => 'da_dang_ky'
                ]);
                $event->increment('so_luong_hien_tai');
            } elseif ($dangKy->trashed()) {
                $dangKy->restore();
            }

            $existingTypes = ChiTietDiemDanh::where('ma_dang_ky', $dangKy->ma_dang_ky)
                ->pluck('loai_diem_danh')
                ->all();

            $soLanYeuCau = $this->requiredCheckinCount($event);
            $requiredTypes = $this->requiredCheckinTypes($event);
            $alreadyComplete = count(array_intersect($requiredTypes, $existingTypes)) >= $soLanYeuCau;

            if ($alreadyComplete && LichSuDiem::where('ma_dang_ky', $dangKy->ma_dang_ky)->exists()) {
                $dangKy->load('chiTietDiemDanh');

                return [
                    'success' => false,
                    'message' => 'Sinh vien nay da duoc diem danh du so lan yeu cau truoc do!',
                    'status' => 400,
                    'data' => $this->checkinResponseData($dangKy, $dangKy->chiTietDiemDanh->count(), $soLanYeuCau),
                ];
            }

            $scanAt = now();
            foreach ($requiredTypes as $loaiDiemDanh) {
                ChiTietDiemDanh::updateOrCreate(
                    [
                        'ma_dang_ky' => $dangKy->ma_dang_ky,
                        'loai_diem_danh' => $loaiDiemDanh,
                    ],
                    [
                        'ma_su_kien' => $eventId,
                        'ma_sinh_vien' => $userId,
                        'diem_danh_at' => $scanAt,
                    ]
                );
            }

            if (in_array($dangKy->trang_thai_tham_gia, ['da_dang_ky', 'chua_du_dieu_kien'], true)) {
                $dangKy->update(['trang_thai_tham_gia' => 'da_tham_gia']);
                $dangKy->refresh();
            }

            $this->markQualifiedAndAwardPoints($dangKy, $event);

            $dangKy->load('chiTietDiemDanh');
            $soLanDiemDanh = $dangKy->chiTietDiemDanh->count();

            return [
                'success' => true,
                'message' => 'Diem danh sinh vien thanh cong!',
                'status' => 200,
                'data' => [
                    ...$this->checkinResponseData($dangKy, $soLanDiemDanh, $soLanYeuCau),
                    'diem_danh_at' => $scanAt,
                ],
            ];
        });
    }

    public function getUserEventHistory($userId, $limit = 10, $page = 1)
    {
        return DangKy::where('ma_sinh_vien', $userId)
            ->with(['suKien', 'suKien.loaiSuKien', 'chiTietDiemDanh'])
            ->orderBy('thoi_gian_dang_ky', 'desc')
            ->paginate($limit, ['*'], 'page', $page);
    }

    /**
     * Lấy danh sách người tham gia sự kiện
     */
    public function getEventParticipants($eventId)
    {
        return DangKy::where('ma_su_kien', $eventId)
            ->with(['nguoiDung', 'chiTietDiemDanh'])
            ->get();
    }

    /**
     * Tự động cập nhật trạng thái khi sự kiện kết thúc
     * Gọi từ scheduled command
     */
    public function updateStatusAfterEventEnds($eventId)
    {
        $event = SuKien::find($eventId);

        if (!$event || $event->thoi_gian_ket_thuc > now()) {
            return ['success' => false, 'message' => 'Sự kiện chưa kết thúc'];
        }

        $registrations = DangKy::where('ma_su_kien', $eventId)
            ->where('trang_thai_tham_gia', 'da_tham_gia')
            ->get();

        foreach ($registrations as $registration) {
            $soLanDiemDanh = $registration->chiTietDiemDanh()->count();

            // Nếu điểm danh chưa đủ số lần yêu cầu → chuyển sang "chua_du_dieu_kien"
            if ($soLanDiemDanh < $this->requiredCheckinCount($event)) {
                $registration->update(['trang_thai_tham_gia' => 'chua_du_dieu_kien']);
            }
        }

        // Cập nhật trạng thái người không điểm danh thành "vang_mat"
        DangKy::where('ma_su_kien', $eventId)
            ->where('trang_thai_tham_gia', 'da_dang_ky')
            ->update(['trang_thai_tham_gia' => 'vang_mat']);

        return ['success' => true, 'message' => 'Cập nhật trạng thái thành công'];
    }

    protected function requiredCheckinCount(SuKien $event): int
    {
        return (int) ($event->so_lan_diem_danh_yeu_cau ?? 2) === 1 ? 1 : 2;
    }

    protected function requiredCheckinTypes(SuKien $event): array
    {
        return $this->requiredCheckinCount($event) === 1
            ? ['dau_buoi']
            : ['dau_buoi', 'cuoi_buoi'];
    }

    protected function markQualifiedAndAwardPoints(DangKy $dangKy, SuKien $event): void
    {
        if ($dangKy->trang_thai_tham_gia !== 'da_tham_gia') {
            $dangKy->update(['trang_thai_tham_gia' => 'da_tham_gia']);
            $dangKy->refresh();
        }

        if (!LichSuDiem::where('ma_dang_ky', $dangKy->ma_dang_ky)->exists()) {
            LichSuDiem::create([
                'ma_sinh_vien' => $dangKy->ma_sinh_vien,
                'ma_su_kien' => $dangKy->ma_su_kien,
                'ma_dang_ky' => $dangKy->ma_dang_ky,
                'diem' => $event->diem_cong,
                'nguon' => 'tham_gia_su_kien',
            ]);
        }
    }

    protected function checkinResponseData(DangKy $dangKy, int $soLanDiemDanh, int $soLanYeuCau): array
    {
        return [
            'registration' => $dangKy,
            'so_lan_diem_danh' => $soLanDiemDanh,
            'so_lan_diem_danh_yeu_cau' => $soLanYeuCau,
            'da_diem_danh_dau_buoi' => $dangKy->da_diem_danh_dau_buoi,
            'da_diem_danh_cuoi_buoi' => $dangKy->da_diem_danh_cuoi_buoi,
            'trang_thai' => $dangKy->trang_thai_tham_gia,
            'du_dieu_kien_cong_diem' => $soLanDiemDanh >= $soLanYeuCau,
            'da_cong_diem' => LichSuDiem::where('ma_dang_ky', $dangKy->ma_dang_ky)->exists(),
        ];
    }
}
