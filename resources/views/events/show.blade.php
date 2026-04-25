@extends('layouts.app')

@section('title', $suKien->ten_su_kien)

@php
$modules = \App\Support\EventTemplateSupport::normalizeTemplateModules($suKien->bo_cuc);
$infoCatalog = \App\Support\EventTemplateSupport::infoFieldCatalog();
@endphp

@section('styles')
<style>
    .event-detail-grid {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: var(--space-xl);
        align-items: start;
    }

    .module-box,
    .sidebar-box {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--border-radius-md);
        padding: 20px;
        margin-bottom: var(--space-md);
    }

    .module-banner {
        width: 100%;
        max-height: 380px;
        object-fit: cover;
        border-radius: var(--border-radius-md);
        border: 1px solid var(--border);
        margin-bottom: 10px;
    }

    .module-title {
        font-family: var(--font-serif);
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .module-title i {
        color: var(--accent);
    }

    .header-badges {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }

    .header-subtitle,
    .module-note {
        color: var(--text-muted);
        line-height: 1.8;
    }

    .info-list {
        display: grid;
        gap: 12px;
    }

    .info-row {
        border-bottom: 1px solid var(--border-light);
        padding-bottom: 12px;
    }

    .info-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .info-label {
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--text-muted);
        margin-bottom: 4px;
    }

    .info-value {
        font-weight: 600;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 10px;
    }

    .gallery-grid a {
        display: block;
        border: 1px solid var(--border);
        border-radius: var(--border-radius);
        overflow: hidden;
        background: var(--bg-alt);
    }

    .gallery-grid img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        display: block;
    }

    .sidebar-box h3 {
        font-size: .95rem;
        margin-bottom: 14px;
    }

    .related-link {
        display: block;
        border: 1px solid var(--border);
        border-radius: var(--border-radius);
        padding: 10px 12px;
        margin-bottom: 8px;
        text-decoration: none;
        color: inherit;
    }

    .qr-section img {
        border: 1px solid var(--border);
        background: #fff;
        padding: 8px;
        width: 180px;
        height: 180px;
        object-fit: contain;
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
    <div>
        @foreach($modules as $module)
        @php
        $type = $module['type'] ?? null;
        $content = $module['content'] ?? [];
        $title = $module['title'] ?? '';
        $statusMap = [
        'sap_to_chuc' => ['class' => 'badge-primary', 'label' => 'Sắp tổ chức'],
        'dang_dien_ra' => ['class' => 'badge-success', 'label' => 'Đang diễn ra'],
        'da_ket_thuc' => ['class' => 'badge-secondary', 'label' => 'Đã kết thúc'],
        'huy' => ['class' => 'badge-danger', 'label' => 'Đã hủy'],
        ];
        $status = $suKien->trang_thai_thuc_te;
        $statusBadge = $statusMap[$status] ?? $statusMap['da_ket_thuc'];
        @endphp

        @if($type === 'banner')
        @php $bannerPath = $content['image_path'] ?? $suKien->anh_su_kien; @endphp
        <div class="module-box">
            @if($bannerPath)
            <img src="{{ asset('storage/' . $bannerPath) }}" alt="{{ $suKien->ten_su_kien }}" class="module-banner">
            @endif
            @if(!empty($content['caption']))
            <div class="module-note">{{ $content['caption'] }}</div>
            @endif
        </div>
        @endif

        @if($type === 'header')
        <div class="module-box">
            <div class="header-badges">
                @if($suKien->loaiSuKien)
                <span class="badge badge-primary">{{ $suKien->loaiSuKien->ten_loai }}</span>
                @endif
                <span class="badge {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
                @if(!empty($content['badge']))
                <span class="badge badge-secondary">{{ $content['badge'] }}</span>
                @endif
            </div>
            <h1 style="margin-bottom:12px;">{{ $content['title'] ?? $suKien->ten_su_kien }}</h1>
            @if(!empty($content['subtitle']))
            <div class="header-subtitle">{!! nl2br(e($content['subtitle'])) !!}</div>
            @endif
            @if($status === 'sap_to_chuc')
            <div id="countdown-timer" style="font-size:.875rem;color:var(--text-muted);font-weight:600;margin-top:12px;">
                <i class="bi bi-hourglass-split"></i> Sắp bắt đầu sau...
            </div>
            @endif
        </div>
        @endif

        @if($type === 'info')
        @php $items = $content['items'] ?? ['time', 'location', 'capacity', 'points']; @endphp
        <div class="module-box">
            <div class="module-title"><i class="bi bi-info-circle"></i> {{ $title ?: 'Thông tin sự kiện' }}</div>
            <div class="info-list">
                @if(in_array('time', $items, true))
                <div class="info-row">
                    <div class="info-label">Thời gian</div>
                    <div class="info-value">{{ $suKien->thoi_gian_bat_dau?->format('H:i d/m/Y') }} - {{ $suKien->thoi_gian_ket_thuc?->format('H:i d/m/Y') }}</div>
                </div>
                @endif
                @if(in_array('location', $items, true))
                <div class="info-row">
                    <div class="info-label">Địa điểm</div>
                    <div class="info-value">{{ $suKien->dia_diem ?: 'Chưa cập nhật' }}</div>
                </div>
                @endif
                @if(in_array('capacity', $items, true))
                <div class="info-row">
                    <div class="info-label">Số lượng</div>
                    <div class="info-value">{{ $suKien->so_luong_hien_tai }} / {{ $suKien->so_luong_toi_da ?: 'Không giới hạn' }}</div>
                </div>
                @endif
                @if(in_array('points', $items, true))
                <div class="info-row">
                    <div class="info-label">Điểm cộng</div>
                    <div class="info-value">+{{ $suKien->diem_cong }} điểm</div>
                </div>
                @endif
            </div>
            @if(!empty($content['custom_note']))
            <div class="module-note" style="margin-top:14px;">{!! nl2br(e($content['custom_note'])) !!}</div>
            @endif
        </div>
        @endif

        @if($type === 'description')
        @php $body = $content['body'] ?? null; @endphp
        @if($body || $suKien->mo_ta_chi_tiet)
        <div class="module-box">
            <div class="module-title"><i class="bi bi-file-text"></i> {{ $content['heading'] ?? $title ?: 'Nội dung chi tiết' }}</div>
            <div style="line-height:1.85;">
                @if($body)
                {!! nl2br(e($body)) !!}
                @else
                {!! $suKien->mo_ta_chi_tiet !!}
                @endif
            </div>
        </div>
        @endif
        @endif

        @if($type === 'gallery')
        @php
        $galleryImages = $content['images'] ?? [];
        if (empty($galleryImages)) {
        $galleryImages = $suKien->media->where('loai_tep', 'hinh_anh')->pluck('duong_dan_tep')->values()->all();
        }
        @endphp
        @if(!empty($galleryImages))
        <div class="module-box">
            <div class="module-title"><i class="bi bi-images"></i> {{ $title ?: 'Hình ảnh sự kiện' }}</div>
            <div class="gallery-grid">
                @foreach($galleryImages as $imagePath)
                <a href="{{ asset('storage/' . $imagePath) }}" target="_blank">
                    <img src="{{ asset('storage/' . $imagePath) }}" alt="Ảnh sự kiện">
                </a>
                @endforeach
            </div>
        </div>
        @endif
        @endif
        @endforeach
    </div>

    <div style="position:sticky;top:80px;">
        <div class="sidebar-box">
            <h3>Thông tin nhanh</h3>
            <div class="info-list">
                <div class="info-row">
                    <div class="info-label">Bắt đầu</div>
                    <div class="info-value">{{ $suKien->thoi_gian_bat_dau?->format('H:i, d/m/Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Kết thúc</div>
                    <div class="info-value">{{ $suKien->thoi_gian_ket_thuc?->format('H:i, d/m/Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Địa điểm</div>
                    <div class="info-value">{{ $suKien->dia_diem }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Đăng ký / tối đa</div>
                    <div class="info-value">{{ $suKien->so_luong_hien_tai }} / {{ $suKien->so_luong_toi_da ?: 'Không giới hạn' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Điểm cộng</div>
                    <div class="info-value">+{{ $suKien->diem_cong }} điểm</div>
                </div>
            </div>
        </div>

        @auth
        <div class="sidebar-box">
            @if($daDangKy)
            @if($suKien->trang_thai_thuc_te !== 'da_ket_thuc' && $suKien->trang_thai_thuc_te !== 'huy' && $suKien->thoi_gian_bat_dau > now())
            <form method="POST" action="{{ route('events.huy-dang-ky', $suKien->ma_su_kien) }}">
                @csrf
                <button type="submit" class="btn btn-danger w-full" onclick="return confirm('Hủy đăng ký sự kiện này?')">
                    <i class="bi bi-x-circle"></i> Hủy đăng ký
                </button>
            </form>
            @else
            <button disabled class="btn btn-secondary w-full" style="cursor:not-allowed;opacity:.65;">
                <i class="bi bi-lock"></i> Không thể hủy
            </button>
            @endif
            <div class="text-success text-sm" style="margin-top:10px;font-weight:600;">
                <i class="bi bi-check-circle"></i> Bạn đã đăng ký sự kiện này
            </div>
            @elseif($suKien->trang_thai_thuc_te === 'da_ket_thuc' || $suKien->trang_thai_thuc_te === 'huy')
            <button disabled class="btn btn-secondary w-full" style="cursor:not-allowed;opacity:.65;">Sự kiện đã kết thúc</button>
            @elseif($suKien->so_luong_toi_da > 0 && $suKien->so_luong_hien_tai >= $suKien->so_luong_toi_da)
            <button disabled class="btn btn-danger w-full" style="cursor:not-allowed;opacity:.65;">
                <i class="bi bi-exclamation-circle"></i> Đã đầy chỗ
            </button>
            @else
            <form method="POST" action="{{ route('events.dang-ky', $suKien->ma_su_kien) }}">
                @csrf
                <button type="submit" class="btn btn-primary w-full">
                    <i class="bi bi-plus-circle"></i> Đăng ký tham gia
                </button>
            </form>
            @endif
        </div>

        @if($daDangKy)
        <div class="sidebar-box qr-section" style="text-align:center;">
            <div class="info-label" style="margin-bottom:10px;">Mã QR điểm danh cá nhân</div>
            <img id="qr-image" alt="QR Code" style="display:none;margin:0 auto;">
            <div id="qr-loading"><i class="bi bi-arrow-repeat spin"></i> Đang tải mã QR...</div>
            <div id="qr-info" class="text-sm text-muted" style="margin-top:10px;display:none;">
                Đưa mã này cho Admin quét để điểm danh.<br>
                Mã sẽ tự làm mới sau <strong id="qr-countdown">20</strong>s.
            </div>
        </div>
        @endif
        @else
        <div class="sidebar-box">
            <a href="{{ route('login') }}" class="btn btn-primary w-full">
                <i class="bi bi-box-arrow-in-right"></i> Đăng nhập để đăng ký
            </a>
        </div>
        @endauth

        @if($suKienLienQuan->isNotEmpty())
        <div class="sidebar-box">
            <h3>Sự kiện liên quan</h3>
            @foreach($suKienLienQuan as $item)
            <a class="related-link" href="{{ route('events.show', $item->ma_su_kien) }}">
                <div style="font-weight:600;">{{ $item->ten_su_kien }}</div>
                <div class="text-muted text-sm">{{ $item->thoi_gian_bat_dau?->format('H:i d/m/Y') }}</div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
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
            if (timer) timer.innerHTML = `<i class="bi bi-hourglass-split"></i> Bắt đầu sau ${timeStr}`;
        }
    }
    updateCountdown();
    setInterval(updateCountdown, 1000);

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
            if (cdLabel) cdLabel.innerText = qrRefreshTimer;
        }
    }, 1000);
    @endif
    @endauth
</script>
@endsection