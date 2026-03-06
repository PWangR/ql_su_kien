@extends('layouts.app')

@section('title', 'Trang chủ - Quản Lý Sự Kiện NTU')

@section('styles')
<style>
/* ==================== BANNER ==================== */
.banner {
    position: relative;
    background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 60%, #0f172a 100%);
    padding: 80px 24px;
    text-align: center;
    overflow: hidden;
}

.banner::before {
    content: '';
    position: absolute;
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(37,99,235,0.25) 0%, transparent 70%);
    top: -100px; left: 50%;
    transform: translateX(-50%);
    pointer-events: none;
}

.banner-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(37,99,235,0.3);
    border: 1px solid rgba(96,165,250,0.4);
    color: #93c5fd;
    padding: 4px 14px; border-radius: 20px;
    font-size: 12px; font-weight: 600; letter-spacing: 0.5px;
    margin-bottom: 16px;
}

.banner-title {
    font-family: 'Montserrat', sans-serif;
    font-size: clamp(28px, 5vw, 48px);
    font-weight: 800;
    color: #fff;
    line-height: 1.2;
    margin-bottom: 14px;
}

.banner-title span { color: #60a5fa; }

.banner-desc {
    font-size: 16px;
    color: #94a3b8;
    max-width: 560px;
    margin: 0 auto 28px;
    line-height: 1.6;
}

.banner-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }

.btn-banner-primary {
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: #fff; border: none; border-radius: 10px;
    padding: 12px 28px; font-size: 15px; font-weight: 700;
    cursor: pointer; text-decoration: none;
    box-shadow: 0 4px 14px rgba(37,99,235,0.4);
    transition: all 0.2s;
    display: inline-flex; align-items: center; gap: 8px;
}

.btn-banner-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37,99,235,0.5); }

.btn-banner-outline {
    background: transparent;
    color: #fff; border: 1.5px solid rgba(255,255,255,0.4);
    border-radius: 10px; padding: 12px 28px; font-size: 15px; font-weight: 600;
    cursor: pointer; text-decoration: none;
    transition: all 0.2s;
    display: inline-flex; align-items: center; gap: 8px;
}

.btn-banner-outline:hover { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.7); }

/* ==================== SLIDER ==================== */
.slider-wrap {
    position: relative;
    overflow: hidden;
    border-radius: 16px;
    margin: 40px auto 0;
    max-width: 700px;
    height: 220px;
}

.slider-track { display: flex; transition: transform 0.5s ease; height: 100%; }

.slide {
    min-width: 100%; height: 100%;
    position: relative; background: #1e3a8a; display: flex; align-items: flex-end;
}

.slide img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.6; }

.slide-info {
    position: relative; z-index: 1; padding: 16px 20px;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    width: 100%;
}

.slide-info h3 { font-size: 16px; font-weight: 700; color: #fff; margin-bottom: 4px; }
.slide-info p  { font-size: 12px; color: #cbd5e1; }
.slide-info a  { display: inline-block; margin-top: 8px; background: #2563eb; color: #fff; padding: 5px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none; }

.slider-nav {
    position: absolute; top: 50%; transform: translateY(-50%);
    background: rgba(255,255,255,0.9); border: none; border-radius: 50%;
    width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 14px; color: #1e293b;
    transition: all 0.2s; z-index: 10;
}

.slider-nav:hover { background: #fff; box-shadow: 0 2px 12px rgba(0,0,0,0.2); }
.slider-prev { left: 10px; }
.slider-next { right: 10px; }

/* ==================== SECTION ==================== */
.section { padding: 48px 0; }
.section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; }
.section-title { font-family: 'Montserrat', sans-serif; font-size: 22px; font-weight: 800; color: #0f172a; }
.section-title span { color: #2563eb; }

/* ==================== EVENT CARD ==================== */
.events-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(270px, 1fr)); gap: 20px; }

.event-card {
    background: #fff; border-radius: 16px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
}

.event-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,0.1); }

.event-img {
    height: 160px; background: linear-gradient(135deg, #dbeafe, #eff6ff);
    display: flex; align-items: center; justify-content: center;
    position: relative; overflow: hidden;
}

.event-img img { width: 100%; height: 100%; object-fit: cover; }
.event-img-icon { font-size: 44px; color: #93c5fd; }

.event-type-badge {
    position: absolute; top: 10px; left: 10px;
    background: rgba(37,99,235,0.9); color: #fff;
    font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 6px;
}

.event-body { padding: 16px; }
.event-title { font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 10px; line-height: 1.4; }

.event-meta { display: flex; flex-direction: column; gap: 5px; }
.event-meta-row { display: flex; align-items: center; gap: 6px; font-size: 12.5px; color: #64748b; }
.event-meta-row i { color: #2563eb; width: 14px; }

.event-footer { padding: 12px 16px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }

.btn-view { background: #eff6ff; color: #2563eb; border: none; border-radius: 8px; padding: 6px 16px; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none; transition: all 0.2s; display: inline-flex; align-items: center; gap: 5px; }
.btn-view:hover { background: #2563eb; color: #fff; }

/* ==================== CATEGORY CHIPS ==================== */
.category-chips { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 24px; }
.chip { background: #f1f5f9; color: #64748b; border: 1.5px solid #e2e8f0; padding: 5px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none; transition: all 0.2s; }
.chip:hover, .chip.active { background: #2563eb; color: #fff; border-color: #2563eb; }
</style>
@endsection

@section('content')
@parent
@endsection

@section('content')
<!-- Banner -->
<div style="margin: -24px -24px 0;">
<div class="banner">
    <div class="banner-badge"><i class="bi bi-stars"></i> Khoa CNTT - ĐH Nha Trang</div>
    <h1 class="banner-title">Khám phá <span>Sự Kiện</span><br>Của Khoa</h1>
    <p class="banner-desc">Tham gia hội thảo, seminar, câu lạc bộ và các hoạt động ngoại khóa để tích lũy điểm và nâng cao kỹ năng!</p>
    <div class="banner-actions">
        <a href="{{ route('events.index') }}" class="btn-banner-primary">
            <i class="bi bi-calendar3"></i> Xem tất cả sự kiện
        </a>
        @guest
        <a href="{{ route('login') }}" class="btn-banner-outline">
            <i class="bi bi-person-circle"></i> Đăng nhập ngay
        </a>
        @endguest
    </div>

    @if($suKienNoiBat->count())
    <!-- Slider -->
    <div class="slider-wrap">
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
                        @if($sk->dia_diem) &nbsp;|&nbsp; <i class="bi bi-geo-alt"></i> {{ $sk->dia_diem }} @endif
                    </p>
                    <a href="{{ route('events.show', $sk->ma_su_kien) }}">Xem chi tiết →</a>
                </div>
            </div>
            @endforeach
        </div>
        @if($suKienNoiBat->count() > 1)
        <button class="slider-nav slider-prev" onclick="slidePrev()"><i class="bi bi-chevron-left"></i></button>
        <button class="slider-nav slider-next" onclick="slideNext()"><i class="bi bi-chevron-right"></i></button>
        @endif
    </div>
    @endif
</div>
</div>

<!-- Danh sách sự kiện -->
<section class="section">
    <div class="section-header">
        <h2 class="section-title">Sự kiện <span>mới nhất</span></h2>
        <a href="{{ route('events.index') }}" class="btn-view"><i class="bi bi-grid"></i> Xem tất cả</a>
    </div>

    <!-- Loại sự kiện -->
    @if($loaiSuKien->count())
    <div class="category-chips">
        <a href="{{ route('home') }}" class="chip active">📋 Tất cả</a>
        @foreach($loaiSuKien as $l)
        <a href="{{ route('events.index', ['loai' => $l->ma_loai_su_kien]) }}" class="chip">{{ $l->ten_loai }}</a>
        @endforeach
    </div>
    @endif

    @if($suKienMoi->count())
    <div class="events-grid">
        @foreach($suKienMoi as $sk)
        <div class="event-card">
            <div class="event-img">
                @if($sk->anh_su_kien)
                <img src="{{ asset('storage/'.$sk->anh_su_kien) }}" alt="{{ $sk->ten_su_kien }}">
                @else
                <i class="bi bi-calendar-event event-img-icon"></i>
                @endif
                @if($sk->loaiSuKien)
                <span class="event-type-badge">{{ $sk->loaiSuKien->ten_loai }}</span>
                @endif
            </div>
            <div class="event-body">
                <h3 class="event-title">{{ Str::limit($sk->ten_su_kien, 50) }}</h3>
                <div class="event-meta">
                    @if($sk->thoi_gian_bat_dau)
                    <div class="event-meta-row"><i class="bi bi-clock-fill"></i> {{ $sk->thoi_gian_bat_dau->format('H:i, d/m/Y') }}</div>
                    @endif
                    @if($sk->dia_diem)
                    <div class="event-meta-row"><i class="bi bi-geo-alt-fill"></i> {{ Str::limit($sk->dia_diem, 35) }}</div>
                    @endif
                    @if($sk->diem_cong > 0)
                    <div class="event-meta-row"><i class="bi bi-star-fill" style="color:#f59e0b"></i> +{{ $sk->diem_cong }} điểm</div>
                    @endif
                </div>
            </div>
            <div class="event-footer">
                <div style="font-size:12px;color:#64748b;">
                    <i class="bi bi-people"></i> {{ $sk->so_luong_hien_tai }}/{{ $sk->so_luong_toi_da ?: '∞' }}
                </div>
                <a href="{{ route('events.show', $sk->ma_su_kien) }}" class="btn-view">
                    Xem chi tiết <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div style="text-align:center;padding:60px;color:#64748b;">
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
const track  = document.getElementById('sliderTrack');

function goTo(i) {
    current = (i + slides.length) % slides.length;
    if(track) track.style.transform = `translateX(-${current*100}%)`;
}

function slideNext() { goTo(current + 1); }
function slidePrev() { goTo(current - 1); }

// Auto slide
if(slides.length > 1) setInterval(slideNext, 4000);
</script>
@endsection
