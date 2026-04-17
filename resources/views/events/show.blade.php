@extends('layouts.app')

@section('title', $suKien->ten_su_kien)

@section('styles')
<style>
    .event-detail-grid {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: var(--space-xl);
        align-items: start;
    }

    .event-banner {
        width: 100%;
        max-height: 360px;
        object-fit: cover;
        border: 1px solid var(--border);
        margin-bottom: var(--space-lg);
    }

    .event-banner-placeholder {
        height: 220px;
        background: var(--bg-alt);
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: var(--space-lg);
    }

    .info-box {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--border-radius-md);
        padding: 20px;
        margin-bottom: var(--space-md);
    }

    .info-box-title {
        font-family: var(--font-serif);
        font-size: 0.9375rem;
        font-weight: 600;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-box-title i {
        color: var(--accent);
        font-size: 14px;
    }

    .info-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 0;
        border-bottom: 1px solid var(--border-light);
        font-size: 0.875rem;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-row i {
        color: var(--accent);
        font-size: 14px;
        width: 18px;
        flex-shrink: 0;
    }

    .info-label {
        font-size: 0.6875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--text-muted);
    }

    .info-value {
        font-weight: 600;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 10px;
    }

    .gallery-item {
        position: relative;
        display: block;
        aspect-ratio: 1;
        overflow: hidden;
        border: 1px solid var(--border);
        background: var(--bg-alt);
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: opacity 0.2s;
    }

    .gallery-item:hover img {
        opacity: 0.7;
    }

    .related-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px;
        border: 1px solid var(--border);
        border-radius: var(--border-radius);
        text-decoration: none;
        transition: background 0.2s;
        margin-bottom: 8px;
    }

    .related-item:hover {
        background: var(--bg-alt);
    }

    .related-item-img {
        width: 48px;
        height: 48px;
        background: var(--bg-alt);
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
    }

    /* QR Code Section */
    .qr-section {
        margin-top: var(--space-md);
        padding: var(--space-md);
        border: 1px solid var(--border);
        border-radius: var(--border-radius-md);
        background: var(--bg-alt);
        text-align: center;
    }

    .qr-section img {
        border: 1px solid var(--border);
        background: #fff;
        padding: 8px;
        width: 180px;
        height: 180px;
        object-fit: contain;
    }

    /* Header Section */
    .event-header-section {
        margin-bottom: var(--space-lg);
    }

    .event-header-section h1 {
        font-size: 1.875rem;
        margin-bottom: var(--space-sm);
        line-height: 1.2;
    }

    .event-header-tags {
        display: flex;
        gap: var(--space-sm);
        flex-wrap: wrap;
        margin-bottom: var(--space-md);
    }

    /* Description Section */
    .event-description {
        font-size: 0.95rem;
        line-height: 1.8;
        color: var(--text);
    }

    @media (max-width: 768px) {
        .event-detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="event-detail-grid">

    <!-- Main Content - Sắp xếp theo bo_cuc (bố cục) -->
    <div>
        @php
        // Lấy bố cục từ sự kiện, mặc định nếu không có
        $layout = $suKien->bo_cuc ?: ['banner', 'header', 'info', 'description', 'gallery'];

        $moduleConfig = [
        'banner' => [
        'label' => 'Ảnh bìa',
        'icon' => 'bi-card-image',
        'description' => 'Hero và hình ảnh mở đầu'
        ],
        'header' => [
        'label' => 'Tiêu đề',
        'icon' => 'bi-type-h1',
        'description' => 'Tiêu đề và đoạn dẫn nhập'
        ],
        'info' => [
        'label' => 'Thông tin',
        'icon' => 'bi-info-circle',
        'description' => 'Thời gian, địa điểm, chỉ tiêu'
        ],
        'description' => [
        'label' => 'Nội dung',
        'icon' => 'bi-text-paragraph',
        'description' => 'Phần mô tả chi tiết'
        ],
        'gallery' => [
        'label' => 'Gallery',
        'icon' => 'bi-images',
        'description' => 'Thư viện ảnh hỗ trợ'
        ],
        ];
        @endphp

        @foreach($layout as $section)
        @switch($section)
        {{-- BANNER SECTION --}}
        @case('banner')
        <div class="section-banner">
            @if($suKien->anh_su_kien)
            <img src="{{ asset('storage/'.$suKien->anh_su_kien) }}" alt="{{ $suKien->ten_su_kien }}" class="event-banner">
            @else
            <div class="event-banner-placeholder">
                <i class="bi bi-calendar-event" style="font-size:64px;color:var(--border);"></i>
            </div>
            @endif
        </div>
        @break

        {{-- HEADER SECTION: Tiêu đề + Badges --}}
        @case('header')
        <div class="event-header-section">
            <div class="event-header-tags">
                @if($suKien->loaiSuKien)
                <span class="badge badge-primary">{{ $suKien->loaiSuKien->ten_loai }}</span>
                @endif

                @php
                $statusMap = [
                'sap_to_chuc' => ['class' => 'badge-primary', 'label' => 'Sắp tổ chức'],
                'dang_dien_ra' => ['class' => 'badge-success', 'label' => 'Đang diễn ra'],
                'da_ket_thuc' => ['class' => 'badge-secondary', 'label' => 'Đã kết thúc'],
                'huy' => ['class' => 'badge-danger', 'label' => 'Đã hủy'],
                ];
                $status = $suKien->trang_thai_thuc_te;
                $s = $statusMap[$status] ?? $statusMap['da_ket_thuc'];
                @endphp
                <span class="badge {{ $s['class'] }}">{{ $s['label'] }}</span>
            </div>

            <h1>{{ $suKien->ten_su_kien }}</h1>

            @if($status === 'sap_to_chuc')
            <div id="countdown-timer" style="font-size:0.875rem;color:var(--text-muted);font-weight:600;margin-top:var(--space-sm);">
                <i class="bi bi-hourglass-split"></i> Sắp bắt đầu...
            </div>
            @endif
        </div>
        @break

        {{-- INFO SECTION: Bỏ qua trong main content, sẽ hiển thị trong sidebar --}}
        @case('info')
        @break

        {{-- DESCRIPTION SECTION: Nội dung chi tiết --}}
        @case('description')
        @if($suKien->mo_ta_chi_tiet)
        <div class="info-box">
            <h3 class="info-box-title"><i class="bi bi-file-text"></i> Nội dung chi tiết</h3>
            <div class="event-description">
                {!! nl2br($suKien->mo_ta_chi_tiet) !!}
            </div>
        </div>
        @endif
        @break

        {{-- GALLERY SECTION: Hình ảnh --}}
        @case('gallery')
        @if($suKien->media && count($suKien->media) > 0)
        <div class="info-box">
            <h3 class="info-box-title"><i class="bi bi-images"></i> Hình ảnh sự kiện</h3>
            <div class="gallery-grid">
                @foreach($suKien->media as $img)
                <a href="{{ asset('storage/'.$img->duong_dan_tep) }}" class="gallery-item" target="_blank">
                    <img src="{{ asset('storage/'.$img->duong_dan_tep) }}" alt="Ảnh sự kiện">
                </a>
                @endforeach
            </div>
        </div>
        @endif
        @break
        @endswitch
        @endforeach
    </div>

    <!-- Sidebar -->
    <div style="position:sticky;top:80px;">
        {{-- Event Info Card (on desktop) --}}
        <div class="info-box" style="margin-bottom: var(--space-lg);">
            <h3 class="info-box-title"><i class="bi bi-info-circle"></i> Thông tin sự kiện</h3>

            @if($suKien->thoi_gian_bat_dau)
            <div class="info-row">
                <i class="bi bi-clock"></i>
                <div>
                    <div class="info-label">Bắt đầu</div>
                    <div class="info-value">{{ $suKien->thoi_gian_bat_dau->format('H:i, d/m/Y') }}</div>
                </div>
            </div>
            @endif

            @if($suKien->thoi_gian_ket_thuc)
            <div class="info-row">
                <i class="bi bi-clock-history"></i>
                <div>
                    <div class="info-label">Kết thúc</div>
                    <div class="info-value">{{ $suKien->thoi_gian_ket_thuc->format('H:i, d/m/Y') }}</div>
                </div>
            </div>
            @endif

            @if($suKien->dia_diem)
            <div class="info-row">
                <i class="bi bi-geo-alt"></i>
                <div>
                    <div class="info-label">Địa điểm</div>
                    <div class="info-value">{{ $suKien->dia_diem }}</div>
                </div>
            </div>
            @endif

            <div class="info-row">
                <i class="bi bi-people"></i>
                <div>
                    <div class="info-label">Đăng ký / Tối đa</div>
                    <div class="info-value">{{ $suKien->so_luong_hien_tai }} / {{ $suKien->so_luong_toi_da ?: 'Không giới hạn' }}</div>
                </div>
            </div>

            @if($suKien->diem_cong > 0)
            <div class="info-row">
                <i class="bi bi-star" style="color:var(--warning);"></i>
                <div>
                    <div class="info-label">Điểm cộng</div>
                    <div class="info-value" style="color:var(--warning);">+{{ $suKien->diem_cong }} điểm</div>
                </div>
            </div>
            @endif
        </div>

        {{-- Register Button & Info --}}
        @auth
        <div class="info-box">
            @if($daDangKy)
            {{-- Nếu đã đăng ký: kiểm tra xem sự kiện đã bắt đầu chưa --}}
            @if($suKien->trang_thai_thuc_te !== 'da_ket_thuc' && $suKien->trang_thai_thuc_te !== 'huy' && $suKien->thoi_gian_bat_dau > now())
            <form method="POST" action="{{ route('events.huy-dang-ky', $suKien->ma_su_kien) }}">
                @csrf
                <button type="submit" class="btn btn-danger w-full" onclick="return confirm('Hủy đăng ký sự kiện này?')" style="padding:12px;">
                    <i class="bi bi-x-circle"></i> Hủy đăng ký
                </button>
            </form>
            @else
            <button disabled class="btn btn-secondary w-full" style="padding:12px;cursor:not-allowed;opacity:0.6;">
                <i class="bi bi-lock"></i> Không thể hủy (Sự kiện đã bắt đầu)
            </button>
            @endif

            <div style="text-align:center;margin-top:8px;font-size:0.8125rem;color:var(--success);font-weight:600;">
                <i class="bi bi-check-circle"></i> Bạn đã đăng ký sự kiện này
            </div>
            @elseif($suKien->trang_thai_thuc_te === 'da_ket_thuc' || $suKien->trang_thai_thuc_te === 'huy')
            <button disabled class="btn btn-secondary w-full" style="padding:12px;cursor:not-allowed;opacity:0.6;">
                Sự kiện đã kết thúc
            </button>
            @elseif($suKien->so_luong_toi_da > 0 && $suKien->so_luong_hien_tai >= $suKien->so_luong_toi_da)
            <button disabled class="btn btn-danger w-full" style="padding:12px;cursor:not-allowed;opacity:0.6;">
                <i class="bi bi-exclamation-circle"></i> Đã đầy chỗ
            </button>
            @else
            <form method="POST" action="{{ route('events.dang-ky', $suKien->ma_su_kien) }}">
                @csrf
                <button type="submit" class="btn btn-primary w-full" style="padding:12px;">
                    <i class="bi bi-plus-circle"></i> Đăng ký tham gia
                </button>
            </form>
            @endif
        </div>
        @else
        <div class="info-box">
            <a href="{{ route('login') }}" class="btn btn-primary w-full" style="padding:12px;">
                <i class="bi bi-box-arrow-in-right"></i> Đăng nhập để đăng ký
            </a>
        </div>
        @endauth

        {{-- Student QR Code section --}}
        @auth
        @if($daDangKy)
        <div class="qr-section">
            <div class="info-label" style="margin-bottom:var(--space-sm);">Mã QR Điểm Danh Cá Nhân</div>
            <div style="margin-bottom:var(--space-sm); display:flex; justify-content:center; position: relative;">
                <img id="qr-image" alt="QR Code" style="display:none;">
            </div>
            <div id="qr-info" class="text-sm text-muted" style="margin-top:var(--space-sm);display:none;">
                Đưa mã này cho Admin quét để điểm danh.<br>
                Mã sẽ tự động làm mới sau <strong id="qr-countdown">20</strong>s.
            </div>
            <div id="qr-loading">
                <i class="bi bi-arrow-repeat spin"></i> Đang tải mã QR...
            </div>
        </div>
        @endif
        @endauth
    </div>

</div>
@endsection

@section('scripts')
<script>
    // Countdown timer
    function updateCountdown() {
        const eventStart = new Date('{{ $suKien->thoi_gian_bat_dau->toIso8601String() }}').getTime();
        const now = new Date().getTime();
        const distance = eventStart - now;

        if (distance > 0) {
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            let timeStr = '';
            if (days > 0) timeStr = `${days} ngày ${hours}h`;
            else if (hours > 0) timeStr = `${hours}h ${minutes}p`;
            else timeStr = `${minutes}p ${seconds}s`;

            const timer = document.getElementById('countdown-timer');
            if (timer) timer.innerHTML = `<i class="bi bi-hourglass-split"></i> Bắt đầu trong ${timeStr}`;
        }
    }
    updateCountdown();
    setInterval(updateCountdown, 1000);

    // Student QR Code generation (Dynamic)
    @auth
    @if($daDangKy)
    let qrRefreshTimer = 20;

    function generateQR() {
        const maSuKien = '{{ $suKien->ma_su_kien }}';
        const mssv = '{{ auth()->user()->ma_sinh_vien ?? "" }}';
        const timestamp = Date.now();

        const qrData = JSON.stringify({
            action: 'student_checkin',
            ma_su_kien: maSuKien,
            ma_sinh_vien: mssv,
            t: timestamp
        });

        const qrUrl = `/api/generate-qr?base64=1&data=${encodeURIComponent(btoa(qrData))}`;

        const img = document.getElementById('qr-image');
        if (img) {
            img.src = qrUrl;
            img.style.display = 'block';
        }

        document.getElementById('qr-loading').style.display = 'none';
        document.getElementById('qr-info').style.display = 'block';
        qrRefreshTimer = 20;
        document.getElementById('qr-countdown').innerText = qrRefreshTimer;
    }

    generateQR();

    setInterval(() => {
        qrRefreshTimer--;
        if (qrRefreshTimer <= 0) {
            generateQR();
        } else {
            const cdLabel = document.getElementById('qr-countdown');
            if (cdLabel) {
                cdLabel.innerText = qrRefreshTimer;
            }
        }
    }, 1000);
    @endif
    @endauth
</script>
@endsection