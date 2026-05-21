@extends('admin.layout')

@section('title', 'Thông Báo')
@section('page-title', 'Thông Báo')

@section('styles')
<style>
    .notify-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(360px, .95fr);
        gap: var(--space-lg);
        align-items: start;
    }

    .notify-options {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
    }

    .notify-option {
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 12px;
        background: var(--card);
        cursor: pointer;
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }

    .notify-option input {
        margin-top: 4px;
        flex-shrink: 0;
    }

    .notify-option strong {
        display: block;
        font-size: .88rem;
        margin-bottom: 2px;
    }

    .notify-option span {
        display: block;
        color: var(--text-muted);
        font-size: .75rem;
        line-height: 1.4;
    }

    .user-picker {
        border: 1px solid var(--border);
        border-radius: 10px;
        background: var(--bg);
        max-height: 260px;
        overflow-y: auto;
        padding: 8px;
    }

    .user-picker label {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px;
        border-radius: 8px;
        cursor: pointer;
    }

    .user-picker label:hover {
        background: var(--bg-alt);
    }

    .schedule-meta {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 6px;
    }

    @media (max-width: 980px) {
        .notify-grid,
        .notify-options {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
        <div>
            <div class="stat-value">{{ $stats['cho_gui'] }}</div>
            <div class="stat-label">Đang chờ gửi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-send-check"></i></div>
        <div>
            <div class="stat-value">{{ $stats['da_gui'] }}</div>
            <div class="stat-label">Đã gửi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-exclamation-triangle"></i></div>
        <div>
            <div class="stat-value">{{ $stats['loi'] }}</div>
            <div class="stat-label">Lỗi gửi</div>
        </div>
    </div>
</div>

<div class="notify-grid">
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-bell"></i> Tạo thông báo</div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.thong-bao.store') }}" id="notifyForm">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="tieu_de">Tiêu đề</label>
                    <input class="form-control" id="tieu_de" name="tieu_de" maxlength="200" required
                        value="{{ old('tieu_de') }}" placeholder="Ví dụ: Nhắc lịch tham gia workshop AI">
                </div>

                <div class="form-group">
                    <label class="form-label" for="noi_dung">Nội dung</label>
                    <textarea class="form-control" id="noi_dung" name="noi_dung" rows="5" maxlength="5000" required
                        placeholder="Nhập nội dung thông báo...">{{ old('noi_dung') }}</textarea>
                </div>

                <div class="input-grid">
                    <div class="form-group">
                        <label class="form-label" for="loai_thong_bao">Loại thông báo</label>
                        <select class="form-control" id="loai_thong_bao" name="loai_thong_bao">
                            @foreach(['he_thong' => 'Hệ thống', 'nhac_nho_su_kien' => 'Nhắc nhở sự kiện', 'cap_nhat_diem' => 'Cập nhật điểm', 'khac' => 'Khác'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('loai_thong_bao', 'he_thong') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="ma_su_kien_lien_quan">Sự kiện liên quan</label>
                        <select class="form-control" id="ma_su_kien_lien_quan" name="ma_su_kien_lien_quan">
                            <option value="">Không gắn sự kiện</option>
                            @foreach($suKien as $event)
                                <option value="{{ $event->ma_su_kien }}" @selected(old('ma_su_kien_lien_quan') == $event->ma_su_kien)>
                                    {{ $event->ten_su_kien }} - {{ $event->thoi_gian_bat_dau?->format('d/m/Y H:i') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Người nhận</label>
                    <div class="notify-options">
                        <label class="notify-option">
                            <input type="radio" name="pham_vi" value="tat_ca" @checked(old('pham_vi', 'tat_ca') === 'tat_ca')>
                            <span><strong>Toàn người dùng</strong><span>Gửi đến tất cả tài khoản đang hoạt động.</span></span>
                        </label>
                        <label class="notify-option">
                            <input type="radio" name="pham_vi" value="nguoi_dang_ky_su_kien" @checked(old('pham_vi') === 'nguoi_dang_ky_su_kien')>
                            <span><strong>Người đăng ký sự kiện</strong><span>Lấy danh sách người đã đăng ký sự kiện được chọn.</span></span>
                        </label>
                        <label class="notify-option">
                            <input type="radio" name="pham_vi" value="tuy_chon" @checked(old('pham_vi') === 'tuy_chon')>
                            <span><strong>Người dùng tùy chọn</strong><span>Chọn thủ công từng người nhận.</span></span>
                        </label>
                    </div>
                </div>

                <div class="form-group" id="customRecipientsBox" style="display:none;">
                    <label class="form-label">Chọn người nhận</label>
                    <input class="form-control" type="search" id="userSearch" placeholder="Tìm theo tên, MSSV, email hoặc lớp..." style="margin-bottom:8px;">
                    <div class="user-picker" id="userPicker">
                        @foreach($nguoiDung as $user)
                            @php $searchText = strtolower($user->ma_sinh_vien.' '.$user->ho_ten.' '.$user->email.' '.$user->lop.' '.$user->vai_tro); @endphp
                            <label data-user-search="{{ $searchText }}">
                                <input type="checkbox" name="nguoi_nhan[]" value="{{ $user->ma_sinh_vien }}" @checked(in_array($user->ma_sinh_vien, old('nguoi_nhan', []), true))>
                                <span>
                                    <strong style="font-size:.85rem;">{{ $user->ho_ten }}</strong>
                                    <span class="text-xs text-muted">{{ $user->ma_sinh_vien }} · {{ $user->lop ?: $user->vai_tro }} · {{ $user->email }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Cách gửi</label>
                    <div class="notify-options">
                        <label class="notify-option">
                            <input type="radio" name="kieu_gui" value="ngay_lap_tuc" @checked(old('kieu_gui', 'ngay_lap_tuc') === 'ngay_lap_tuc')>
                            <span><strong>Gửi ngay</strong><span>Tạo thông báo cho người nhận lập tức.</span></span>
                        </label>
                        <label class="notify-option">
                            <input type="radio" name="kieu_gui" value="hen_gio" @checked(old('kieu_gui') === 'hen_gio')>
                            <span><strong>Hẹn giờ</strong><span>Gửi tại thời điểm cụ thể.</span></span>
                        </label>
                        <label class="notify-option">
                            <input type="radio" name="kieu_gui" value="nhac_nho_su_kien" @checked(old('kieu_gui') === 'nhac_nho_su_kien')>
                            <span><strong>Nhắc nhở sự kiện</strong><span>Tự tính giờ gửi trước khi sự kiện bắt đầu.</span></span>
                        </label>
                    </div>
                </div>

                <div class="input-grid">
                    <div class="form-group" id="scheduledAtBox" style="display:none;">
                        <label class="form-label" for="thoi_gian_gui">Thời gian hẹn gửi</label>
                        <input class="form-control" type="datetime-local" id="thoi_gian_gui" name="thoi_gian_gui" value="{{ old('thoi_gian_gui') }}">
                    </div>

                    <div class="form-group" id="reminderBox" style="display:none;">
                        <label class="form-label" for="reminder_minutes">Nhắc trước sự kiện</label>
                        <select class="form-control" id="reminder_minutes" name="reminder_minutes">
                            @foreach([15 => '15 phút', 30 => '30 phút', 60 => '1 giờ', 120 => '2 giờ', 1440 => '1 ngày'] as $value => $label)
                                <option value="{{ $value }}" @selected((int) old('reminder_minutes', 60) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send"></i> Tạo thông báo
                </button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-list-check"></i> Lịch gửi gần đây</div>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nội dung</th>
                        <th>Gửi lúc</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lichGui as $item)
                        @php
                            $badgeClass = match($item->trang_thai) {
                                'da_gui' => 'badge-success',
                                'cho_gui' => 'badge-primary',
                                'da_huy' => 'badge-secondary',
                                default => 'badge-danger',
                            };
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $item->tieu_de }}</strong>
                                <div class="text-sm text-muted" style="margin-top:4px;">{{ $item->pham_vi_label }} · {{ $item->kieu_gui_label }}</div>
                                @if($item->suKienLienQuan)
                                    <div class="text-xs text-muted">Sự kiện: {{ $item->suKienLienQuan->ten_su_kien }}</div>
                                @endif
                                @if($item->loi)
                                    <div class="text-xs text-danger">{{ $item->loi }}</div>
                                @endif
                            </td>
                            <td class="text-sm text-muted">
                                {{ $item->thoi_gian_gui?->format('d/m/Y H:i') ?: '-' }}
                                @if($item->thoi_gian_da_gui)
                                    <div class="text-xs">Đã gửi: {{ $item->thoi_gian_da_gui->format('d/m/Y H:i') }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $badgeClass }}">{{ $item->trang_thai_label }}</span>
                                @if($item->trang_thai === 'da_gui')
                                    <div class="text-xs text-muted" style="margin-top:4px;">{{ $item->so_nguoi_nhan }} người</div>
                                @endif
                            </td>
                            <td>
                                @if($item->trang_thai === 'cho_gui')
                                    <form method="POST" action="{{ route('admin.thong-bao.cancel', $item->ma_lich_gui) }}" onsubmit="return confirm('Hủy lịch gửi này?')">
                                        @csrf
                                        <button class="btn btn-sm btn-outline" type="submit">Hủy</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center;padding:var(--space-2xl);color:var(--text-muted);">Chưa có lịch gửi thông báo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($lichGui->hasPages())
            <div class="card-footer">{{ $lichGui->links() }}</div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    const scopeInputs = document.querySelectorAll('input[name="pham_vi"]');
    const sendTypeInputs = document.querySelectorAll('input[name="kieu_gui"]');
    const customRecipientsBox = document.getElementById('customRecipientsBox');
    const scheduledAtBox = document.getElementById('scheduledAtBox');
    const reminderBox = document.getElementById('reminderBox');
    const eventSelect = document.getElementById('ma_su_kien_lien_quan');
    const typeSelect = document.getElementById('loai_thong_bao');

    function selectedValue(name) {
        return document.querySelector(`input[name="${name}"]:checked`)?.value;
    }

    function refreshFormMode() {
        const scope = selectedValue('pham_vi');
        const sendType = selectedValue('kieu_gui');

        customRecipientsBox.style.display = scope === 'tuy_chon' ? 'block' : 'none';
        scheduledAtBox.style.display = sendType === 'hen_gio' ? 'block' : 'none';
        reminderBox.style.display = sendType === 'nhac_nho_su_kien' ? 'block' : 'none';

        if (sendType === 'nhac_nho_su_kien') {
            typeSelect.value = 'nhac_nho_su_kien';
            eventSelect.required = true;
        } else if (scope === 'nguoi_dang_ky_su_kien') {
            eventSelect.required = true;
        } else {
            eventSelect.required = false;
        }
    }

    scopeInputs.forEach(input => input.addEventListener('change', refreshFormMode));
    sendTypeInputs.forEach(input => input.addEventListener('change', refreshFormMode));
    refreshFormMode();

    document.getElementById('userSearch')?.addEventListener('input', function() {
        const keyword = this.value.trim().toLowerCase();
        document.querySelectorAll('#userPicker [data-user-search]').forEach(row => {
            row.style.display = row.dataset.userSearch.includes(keyword) ? 'flex' : 'none';
        });
    });
</script>
@endsection
