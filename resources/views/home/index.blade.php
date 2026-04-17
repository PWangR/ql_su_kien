@extends('layouts.app')

@section('title', 'Trang chủ — Quản Lý Sự Kiện NTU')

@section('styles')
<style>
/* Slider within hero */
.hero-slider {
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,0.15);
    margin: var(--space-xl) auto 0;
    max-width: 680px;
    height: 200px;
}
.hero-slider .slider-track { display: flex; transition: transform 0.5s ease; height: 100%; }
.hero-slider .slide { min-width: 100%; height: 100%; position: relative; background: #111; display: flex; align-items: flex-end; }
.hero-slider .slide img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.5; }
.hero-slider .slide-info { position: relative; z-index: 1; padding: var(--space-md); background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); width: 100%; }
.hero-slider .slide-info h3 { font-family: var(--font-serif); font-size: 1rem; font-weight: 600; color: #fff; margin-bottom: 4px; }
.hero-slider .slide-info p { font-size: 0.75rem; color: #ccc; }
.hero-slider .slide-info a { display: inline-block; margin-top: 8px; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: #fff; padding: 4px 14px; font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; text-decoration: none; }
.hero-slider .slide-info a:hover { background: rgba(255,255,255,0.25); }
.slider-nav-btn { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.9); border: 1px solid var(--border); width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 12px; color: var(--text); z-index: 10; border-radius: 0; }
.slider-prev { left: 8px; }
.slider-next { right: 8px; }

/* Hero badge */
.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid rgba(255,255,255,0.3);
    color: rgba(255,255,255,0.7);
    padding: 4px 14px;
    font-size: 0.6875rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    margin-bottom: var(--space-md);
}

.hero-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}

/* Section with side lines */
.section-header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: var(--space-lg);
    padding-bottom: var(--space-sm);
    border-bottom: 1px solid var(--border);
}

/* Filter tabs */
.filter-tabs {
    display: flex;
    gap: 0;
    border-bottom: 1px solid var(--border);
    margin-bottom: var(--space-lg);
    overflow-x: auto;
}
.filter-tab {
    padding: 10px 18px;
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--text-light);
    text-decoration: none;
    border-bottom: 2px solid transparent;
    transition: all 0.2s;
    white-space: nowrap;
}
.filter-tab:hover,
.filter-tab.active {
    color: var(--accent);
    border-bottom-color: var(--accent);
}

/* Events grid */
.events-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: var(--space-lg);
}
</style>
@endsection

@section('content')
<!-- Hero Banner -->
<div style="margin: calc(-1 * var(--space-lg)) calc(-1 * var(--space-lg)) 0;">
<div class="hero-banner">
    <div class="hero-badge"><i class="bi bi-mortarboard"></i> Khoa CNTT — ĐH Nha Trang</div>
    <h1>Khám phá Sự Kiện<br>Của Khoa</h1>
    <p>Tham gia hội thảo, seminar, câu lạc bộ và các hoạt động ngoại khóa để tích lũy điểm và nâng cao kỹ năng.</p>
    <div class="hero-actions">
        <a href="{{ route('events.index') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-calendar3"></i> Xem tất cả sự kiện
        </a>
        @auth
        <a href="{{ route('events.scanner') }}" class="btn btn-outline btn-lg">
            <i class="bi bi-qr-code-scan"></i> Quét QR điểm danh
        </a>
        @endauth
        @guest
        <a href="{{ route('login') }}" class="btn btn-outline btn-lg">
            <i class="bi bi-person"></i> Đăng nhập ngay
        </a>
        @endguest
    </div>

    @if($suKienNoiBat->count())
    <div class="hero-slider">
        <div class="slider-track" id="sliderTrack">
            @foreach($suKienNoiBat as $sk)
            <div class="slide">
                @if($sk->anh_su_kien)
                <img src="{{ asset('storage/'.$sk->anh_su_kien) }}" alt="{{ $sk->ten_su_kien }}">
                @endif
                <div class="slide-info">
                    <h3>{{ Str::limit($sk->ten_su_kien, 50) }}</h3>
                    <p>
                        @if($sk->thoi_gian_bat_dau) <i class="bi bi-clock"></i> {{ $sk->thoi_gian_bat_dau->format('d/m/Y H:i') }} @endif
                        @if($sk->dia_diem) &nbsp;·&nbsp; <i class="bi bi-geo-alt"></i> {{ $sk->dia_diem }} @endif
                    </p>
                    <a href="{{ route('events.show', $sk->ma_su_kien) }}">Xem chi tiết →</a>
                </div>
            </div>
            @endforeach
        </div>
        @if($suKienNoiBat->count() > 1)
        <button class="slider-nav-btn slider-prev" onclick="slidePrev()"><i class="bi bi-chevron-left"></i></button>
        <button class="slider-nav-btn slider-next" onclick="slideNext()"><i class="bi bi-chevron-right"></i></button>
        @endif
    </div>
    @endif
</div>
</div>

<!-- Events List -->
<section style="padding: var(--space-2xl) 0;">
    <div class="section-header-row">
        <h2 style="margin:0;">Sự kiện mới nhất</h2>
        <a href="{{ route('events.index') }}" class="btn btn-outline btn-sm">
            <i class="bi bi-grid"></i> Xem tất cả
        </a>
    </div>

    @if($loaiSuKien->count())
    <div class="filter-tabs">
        <a href="{{ route('home') }}" class="filter-tab active">Tất cả</a>
        @foreach($loaiSuKien as $l)
        <a href="{{ route('events.index', ['loai' => $l->ma_loai_su_kien]) }}" class="filter-tab">{{ $l->ten_loai }}</a>
        @endforeach
    </div>
    @endif

    @if($suKienMoi->count())
    <div class="events-grid">
        @foreach($suKienMoi as $sk)
        <div class="event-card">
            <div class="event-card-img">
                @if($sk->anh_su_kien)
                <img src="{{ asset('storage/'.$sk->anh_su_kien) }}" alt="{{ $sk->ten_su_kien }}">
                @else
                <i class="bi bi-calendar-event placeholder-icon"></i>
                @endif
                @if($sk->loaiSuKien)
                <span class="event-type-label">{{ $sk->loaiSuKien->ten_loai }}</span>
                @endif
            </div>
            <div class="event-card-body">
                <h3 class="event-card-title">{{ Str::limit($sk->ten_su_kien, 50) }}</h3>
                <div class="event-card-meta">
                    @if($sk->thoi_gian_bat_dau)
                    <div class="event-meta-item"><i class="bi bi-clock"></i> {{ $sk->thoi_gian_bat_dau->format('H:i, d/m/Y') }}</div>
                    @endif
                    @if($sk->dia_diem)
                    <div class="event-meta-item"><i class="bi bi-geo-alt"></i> {{ Str::limit($sk->dia_diem, 35) }}</div>
                    @endif
                    @if($sk->diem_cong > 0)
                    <div class="event-meta-item"><i class="bi bi-star"></i> +{{ $sk->diem_cong }} điểm</div>
                    @endif
                </div>
            </div>
            <div class="event-card-footer">
                <span class="text-sm text-muted">
                    <i class="bi bi-people"></i> {{ $sk->so_luong_hien_tai }}/{{ $sk->so_luong_toi_da ?: '∞' }}
                </span>
                <a href="{{ route('events.show', $sk->ma_su_kien) }}" class="btn btn-outline btn-sm">
                    Xem chi tiết <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div style="text-align:center;padding:var(--space-3xl);color:var(--text-muted);">
        <i class="bi bi-calendar-x" style="font-size:48px;display:block;margin-bottom:12px;opacity:0.3;"></i>
        <p>Chưa có sự kiện nào. Hãy quay lại sau!</p>
    </div>
    @endif
</section>
@endsection

@section('scripts')
<script>
let current = 0;
const slides = document.querySelectorAll('.slide');
const track = document.getElementById('sliderTrack');

function goTo(i) {
    current = (i + slides.length) % slides.length;
    if(track) track.style.transform = `translateX(-${current*100}%)`;
}

function slideNext() { goTo(current + 1); }
function slidePrev() { goTo(current - 1); }

if(slides.length > 1) setInterval(slideNext, 5000);
</script>
@endsection
