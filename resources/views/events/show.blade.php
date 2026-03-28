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

.info-box-title i { color: var(--accent); font-size: 14px; }

.info-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 0;
    border-bottom: 1px solid var(--border-light);
    font-size: 0.875rem;
}

.info-row:last-child { border-bottom: none; }
.info-row i { color: var(--accent); font-size: 14px; width: 18px; flex-shrink: 0; }

.info-label {
    font-size: 0.6875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--text-muted);
}

.info-value { font-weight: 600; }

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

.gallery-item:hover img { opacity: 0.7; }

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

.related-item:hover { background: var(--bg-alt); }
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

.qr-section canvas { border: 1px solid var(--border); background: #fff; padding: 8px; }

@media (max-width: 768px) {
    .event-detail-grid { grid-template-columns: 1fr; }
}
</style>
@endsection

@section('content')
<div class="event-detail-grid">

    <!-- Main Content -->
    <div>
        @if($suKien->anh_su_kien)
        <img src="{{ asset('storage/'.$suKien->anh_su_kien) }}" alt="{{ $suKien->ten_su_kien }}" class="event-banner">
        @else
        <div class="event-banner-placeholder">
            <i class="bi bi-calendar-event" style="font-size:64px;color:var(--border);"></i>
        </div>
        @endif

        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:var(--space-md);flex-wrap:wrap;">
            <div>
                @if($suKien->loaiSuKien)
                <span class="badge badge-primary" style="margin-bottom:8px;">{{ $suKien->loaiSuKien->ten_loai }}</span>
                @endif
                <h1 style="font-size:1.5rem;margin-bottom:0;">{{ $suKien->ten_su_kien }}</h1>
            </div>
            @php
            $statusMap = [
                'sap_to_chuc'  => ['class' => 'badge-primary', 'label' => 'Sắp tổ chức'],
                'dang_dien_ra' => ['class' => 'badge-success', 'label' => 'Đang diễn ra'],
                'da_ket_thuc'  => ['class' => 'badge-secondary', 'label' => 'Đã kết thúc'],
                'huy'          => ['class' => 'badge-danger', 'label' => 'Đã hủy'],
            ];
            $status = $suKien->trang_thai_thuc_te;
            $s = $statusMap[$status] ?? $statusMap['da_ket_thuc'];
            @endphp
            <div>
                <span class="badge {{ $s['class'] }}">{{ $s['label'] }}</span>
                @if($status === 'sap_to_chuc')
                <div id="countdown-timer" style="font-size:0.6875rem;color:var(--text-muted);font-weight:600;margin-top:4px;">
                    <i class="bi bi-hourglass-split"></i> Sắp bắt đầu...
                </div>
                @endif
            </div>
        </div>

        @if($suKien->mo_ta_chi_tiet)
        <div class="info-box">
            <h3 class="info-box-title"><i class="bi bi-file-text"></i> Mô tả</h3>
            <div style="font-size:0.875rem;line-height:1.8;color:var(--text);">
                {!! nl2br(e($suKien->mo_ta_chi_tiet)) !!}
            </div>
        </div>
        @endif

        {{-- Gallery --}}
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

        {{-- Related events --}}
        @if($suKienLienQuan->count())
        <div class="info-box">
            <h3 class="info-box-title"><i class="bi bi-link-45deg"></i> Sự kiện liên quan</h3>
            @foreach($suKienLienQuan as $sk)
            <a href="{{ route('events.show', $sk->ma_su_kien) }}" class="related-item">
                <div class="related-item-img">
                    @if($sk->anh_su_kien)
                    <img src="{{ asset('storage/'.$sk->anh_su_kien) }}" style="width:100%;height:100%;object-fit:cover;">
                    @else
                    <i class="bi bi-calendar-event" style="color:var(--border);"></i>
                    @endif
                </div>
                <div>
                    <div style="font-size:0.8125rem;font-weight:600;color:var(--text);">{{ Str::limit($sk->ten_su_kien, 45) }}</div>
                    @if($sk->thoi_gian_bat_dau)<div class="text-sm text-muted">{{ $sk->thoi_gian_bat_dau->format('d/m/Y') }}</div>@endif
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div style="position:sticky;top:80px;">
        <div class="info-box">
            <div class="info-label" style="margin-bottom:12px;">Thông tin sự kiện</div>

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

            {{-- Register button --}}
            @auth
            <div style="margin-top:var(--space-md);">
                @if($daDangKy)
                <form method="POST" action="{{ route('events.huy-dang-ky', $suKien->ma_su_kien) }}">
                    @csrf
                    <button type="submit" class="btn btn-danger w-full" onclick="return confirm('Hủy đăng ký sự kiện này?')" style="padding:12px;">
                        <i class="bi bi-x-circle"></i> Hủy đăng ký
                    </button>
                </form>
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
            <div style="margin-top:var(--space-md);">
                <a href="{{ route('login') }}" class="btn btn-primary w-full" style="padding:12px;">
                    <i class="bi bi-box-arrow-in-right"></i> Đăng nhập để đăng ký
                </a>
            </div>
            @endauth
        </div>

        {{-- QR Code section --}}
        @auth
        @if($daDangKy)
        <div class="qr-section">
            <div class="info-label" style="margin-bottom:var(--space-sm);">QR Check-in</div>
            <div id="qr-code-display" style="margin-bottom:var(--space-sm);">
                {{-- QR will be rendered here --}}
            </div>
            <button class="btn btn-outline btn-sm" id="generateQrBtn" onclick="generateQR()">
                <i class="bi bi-qr-code"></i> Tạo QR Check-in
            </button>
            <div id="qr-info" class="text-sm text-muted" style="margin-top:var(--space-sm);display:none;"></div>
        </div>
        @endif
        @endauth
    </div>

</div>
@endsection

@section('scripts')
{{-- QR Code library --}}
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

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

// QR Code generation
function generateQR() {
    const maSuKien = '{{ $suKien->ma_su_kien }}';
    const thoiGianTao = new Date().toISOString();
    const qrData = JSON.stringify({
        ma_su_kien: maSuKien,
        thoi_gian_tao_qr: thoiGianTao
    });

    const container = document.getElementById('qr-code-display');
    container.innerHTML = '';

    const canvas = document.createElement('canvas');
    container.appendChild(canvas);

    QRCode.toCanvas(canvas, qrData, {
        width: 180,
        margin: 2,
        color: { dark: '#002060', light: '#FFFFFF' }
    });

    const infoDiv = document.getElementById('qr-info');
    infoDiv.style.display = 'block';
    infoDiv.innerHTML = `Mã SK: <strong>${maSuKien}</strong><br>Tạo lúc: <strong>${new Date(thoiGianTao).toLocaleString('vi-VN')}</strong>`;

    document.getElementById('generateQrBtn').innerHTML = '<i class="bi bi-arrow-clockwise"></i> Tạo lại QR';
}
</script>
@endsection