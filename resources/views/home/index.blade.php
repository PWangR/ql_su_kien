@extends('layouts.app')

@section('title', 'Trang chủ — Quản Lý Sự Kiện NTU')

@section('styles')
<style>
    .home-page {
        width: 100vw;
        margin-top: calc(-1 * var(--space-lg));
        margin-left: calc(50% - 50vw);
        overflow: hidden;
        background: #f6f9ff;
    }

    .home-hero {
        position: relative;
        height: calc(100svh - var(--header-h));
        min-height: 620px;
        padding: 22px 24px 76px;
        color: #fff;
        background:
            radial-gradient(circle at 50% 22%, rgba(45, 105, 255, .46), transparent 25%),
            radial-gradient(circle at 8% 42%, rgba(25, 110, 255, .42), transparent 30%),
            radial-gradient(circle at 91% 54%, rgba(0, 191, 255, .28), transparent 28%),
            linear-gradient(118deg, #1729dc 0%, #071d88 57%, #03135d 100%);
        isolation: isolate;
    }

    .home-hero::before,
    .home-hero::after {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
    }

    .home-hero::before {
        z-index: -2;
        opacity: .46;
        background:
            linear-gradient(32deg, transparent 47%, rgba(57, 158, 255, .22) 48%, transparent 49%) 0 0 / 72px 72px,
            linear-gradient(-32deg, transparent 47%, rgba(57, 158, 255, .15) 48%, transparent 49%) 0 0 / 72px 72px;
        mask-image: linear-gradient(to bottom, rgba(0, 0, 0, .85), transparent 88%);
    }

    .home-hero::after {
        z-index: -1;
        background:
            radial-gradient(circle at 18% 20%, #59c9ff 0 2px, transparent 3px),
            radial-gradient(circle at 77% 18%, #64d9ff 0 3px, transparent 4px),
            radial-gradient(circle at 91% 35%, #fff 0 2px, transparent 3px),
            radial-gradient(circle at 31% 11%, #fff 0 1px, transparent 2px),
            radial-gradient(circle at 24% 69%, #2ce0ff 0 2px, transparent 3px),
            radial-gradient(circle at 83% 72%, #50b9ff 0 2px, transparent 3px);
        filter: drop-shadow(0 0 8px #35c8ff);
    }

    .hero-orbit {
        position: absolute;
        border: 2px solid rgba(40, 184, 255, .34);
        border-radius: 50%;
        pointer-events: none;
    }

    .hero-orbit-one {
        width: 480px;
        height: 200px;
        left: -150px;
        top: 330px;
        transform: rotate(22deg);
    }

    .hero-orbit-two {
        width: 560px;
        height: 230px;
        right: -210px;
        top: 270px;
        transform: rotate(-24deg);
    }

    .hero-decor {
        position: absolute;
        z-index: 0;
        display: grid;
        place-items: center;
        color: #dff5ff;
        border: 1px solid rgba(110, 208, 255, .65);
        background: linear-gradient(145deg, rgba(73, 163, 255, .74), rgba(36, 52, 216, .55));
        box-shadow: 0 22px 45px rgba(0, 13, 93, .38), inset 0 0 22px rgba(97, 218, 255, .34);
        backdrop-filter: blur(5px);
        pointer-events: none;
    }

    .hero-decor i {
        filter: drop-shadow(0 0 8px rgba(255, 255, 255, .6));
    }

    .hero-calendar {
        width: 110px;
        height: 110px;
        top: 220px;
        left: max(5vw, calc(50% - 610px));
        border-radius: 24px;
        transform: rotate(-8deg);
        font-size: 52px;
    }

    .hero-qr {
        width: 116px;
        height: 116px;
        top: 170px;
        right: max(6vw, calc(50% - 610px));
        border-radius: 22px;
        transform: rotate(9deg);
        font-size: 62px;
    }

    .hero-ticket {
        width: 112px;
        height: 70px;
        right: max(4vw, calc(50% - 660px));
        top: 450px;
        border-radius: 16px;
        transform: rotate(-28deg);
        font-size: 42px;
        opacity: .72;
    }

    .hero-ring {
        position: absolute;
        left: max(4vw, calc(50% - 660px));
        top: 430px;
        width: 104px;
        height: 46px;
        border: 4px solid #15d8ff;
        border-radius: 50%;
        transform: rotate(17deg);
        box-shadow: 0 0 18px rgba(21, 216, 255, .82);
        opacity: .84;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 1050px;
        margin: 0 auto;
        text-align: center;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        min-height: 40px;
        margin-bottom: 10px;
        padding: 0 24px;
        border: 1px solid rgba(74, 190, 255, .8);
        border-radius: 12px;
        color: #d9f4ff;
        background: rgba(18, 66, 193, .3);
        box-shadow: inset 0 0 16px rgba(60, 171, 255, .13);
        font-size: .92rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .hero-title {
        max-width: 760px;
        margin: 0 auto;
        color: #fff;
        font-size: clamp(2.3rem, 3.8vw, 3.5rem);
        font-weight: 800;
        line-height: 1.08;
        letter-spacing: -.035em;
        text-shadow: 0 8px 30px rgba(0, 10, 82, .35);
    }

    .hero-title-line,
    .hero-title-subtitle {
        display: block;
    }

    .hero-title-subtitle {
        margin-top: 5px;
        font-size: clamp(1.55rem, 2.6vw, 2.25rem);
        line-height: 1.18;
        letter-spacing: -.02em;
    }

    .hero-title-gradient {
        color: #4bd4ff;
        background: linear-gradient(90deg, #58e2ff, #3788ff);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-spark {
        position: absolute;
        top: 48px;
        left: calc(50% + 215px);
        color: #ffd323;
        font-size: 50px;
        transform: rotate(-12deg);
        text-shadow: 0 0 14px rgba(255, 211, 35, .5);
    }

    .hero-divider {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 14px;
        width: min(280px, 75vw);
        margin: 10px auto 8px;
        color: #ffd62e;
    }

    .hero-divider::before,
    .hero-divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, transparent, #50cfff);
        box-shadow: 0 0 8px rgba(80, 207, 255, .75);
    }

    .hero-divider::after {
        background: linear-gradient(90deg, #50cfff, transparent);
    }

    .hero-description {
        max-width: 680px;
        margin: 0 auto 14px;
        color: rgba(255, 255, 255, .9);
        font-size: 1.02rem;
        line-height: 1.45;
    }

    .hero-actions {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 14px;
    }

    .hero-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 44px;
        padding: 0 24px;
        border: 1px solid #50aaff;
        border-radius: 10px;
        color: #fff;
        background: rgba(9, 35, 139, .4);
        box-shadow: 0 12px 28px rgba(0, 16, 92, .2);
        font-size: .9rem;
        font-weight: 800;
        text-transform: uppercase;
        transition: transform .2s ease, background .2s ease, box-shadow .2s ease;
    }

    .hero-action:hover {
        color: #fff;
        background: rgba(37, 99, 235, .62);
        box-shadow: 0 15px 34px rgba(0, 12, 80, .34);
        transform: translateY(-2px);
    }

    .hero-action-primary {
        border-color: #fff;
        color: #1261e8;
        background: #fff;
    }

    .hero-action-primary:hover {
        color: #0c4dcc;
        background: #eff8ff;
    }

    .hero-slider {
        position: relative;
        width: min(880px, calc(100vw - 48px));
        height: 180px;
        margin: 14px auto 0;
        overflow: hidden;
        border: 1px solid rgba(88, 171, 255, .5);
        border-radius: 16px;
        background: #06113a;
        box-shadow: 0 28px 55px rgba(0, 8, 60, .42);
    }

    .home-slider-track {
        display: flex;
        height: 100%;
        transition: transform .55s cubic-bezier(.22, .61, .36, 1);
    }

    .home-slide {
        position: relative;
        display: grid;
        flex: 0 0 100%;
        place-items: center;
        min-width: 100%;
        height: 100%;
        overflow: hidden;
    }

    .home-slide-image,
    .home-slide-placeholder {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
    }

    .home-slide-image {
        object-fit: cover;
    }

    .home-slide-placeholder {
        display: grid;
        place-items: center;
        color: rgba(255, 255, 255, .25);
        background:
            radial-gradient(circle at 65% 25%, rgba(64, 172, 255, .55), transparent 25%),
            linear-gradient(125deg, #07183d, #163d91);
        font-size: 90px;
    }

    .home-slide::before {
        content: "";
        position: absolute;
        z-index: 1;
        inset: 0;
        background: linear-gradient(90deg, rgba(1, 8, 32, .88), rgba(2, 15, 56, .55) 50%, rgba(1, 7, 28, .78));
    }

    .home-slide-content {
        position: relative;
        z-index: 2;
        max-width: 660px;
        padding: 14px 64px;
        text-align: center;
    }

    .slide-status {
        display: inline-flex;
        align-items: center;
        min-height: 26px;
        margin-bottom: 5px;
        padding: 0 16px;
        border-radius: 999px;
        color: #fff;
        background: linear-gradient(90deg, #2161f3, #43a7ff);
        box-shadow: 0 7px 18px rgba(33, 97, 243, .35);
        font-size: .7rem;
        font-weight: 800;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .home-slide-title {
        margin: 0 0 5px;
        color: #fff;
        font-size: clamp(1.35rem, 2.2vw, 1.8rem);
        line-height: 1.2;
        text-shadow: 0 4px 18px rgba(0, 0, 0, .5);
    }

    .home-slide-meta {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 7px 15px;
        color: rgba(255, 255, 255, .9);
        font-size: .82rem;
    }

    .home-slide-meta span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .home-slide-link {
        display: inline-flex;
        align-items: center;
        min-height: 36px;
        margin-top: 8px;
        padding: 0 20px;
        border: 1px solid #398cff;
        border-radius: 9px;
        color: #fff;
        background: rgba(7, 35, 123, .48);
        font-size: .82rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    .home-slide-link:hover {
        color: #fff;
        background: #1f64ed;
    }

    .slider-nav-btn {
        position: absolute;
        z-index: 4;
        top: 50%;
        display: grid;
        place-items: center;
        width: 42px;
        height: 42px;
        padding: 0;
        border: 0;
        border-radius: 50%;
        color: #0d2055;
        background: rgba(255, 255, 255, .94);
        box-shadow: 0 8px 18px rgba(0, 0, 0, .24);
        cursor: pointer;
        transform: translateY(-50%);
        transition: transform .2s ease, background .2s ease;
    }

    .slider-nav-btn:hover {
        background: #fff;
        transform: translateY(-50%) scale(1.06);
    }

    .slider-prev {
        left: 14px;
    }

    .slider-next {
        right: 14px;
    }

    .slider-dots {
        display: flex;
        justify-content: center;
        gap: 9px;
        margin-top: 10px;
    }

    .slider-dot {
        width: 30px;
        height: 5px;
        padding: 0;
        border: 0;
        border-radius: 999px;
        background: rgba(255, 255, 255, .28);
        cursor: pointer;
        transition: width .2s ease, background .2s ease;
    }

    .slider-dot.active {
        width: 38px;
        background: #fff;
    }

    .home-wave {
        position: absolute;
        z-index: 1;
        right: 0;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 64px;
        color: #f6f9ff;
    }

    .home-features {
        position: relative;
        z-index: 3;
        padding: 0 24px 48px;
        background: #f6f9ff;
    }

    .feature-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        max-width: 1200px;
        margin: -28px auto 0;
    }

    .feature-card {
        display: flex;
        align-items: center;
        gap: 16px;
        min-height: 116px;
        padding: 20px;
        border: 1px solid #e4eaf4;
        border-radius: 18px;
        background: rgba(255, 255, 255, .96);
        box-shadow: 0 16px 35px rgba(32, 62, 117, .09);
    }

    .feature-icon {
        display: grid;
        flex: 0 0 58px;
        place-items: center;
        width: 58px;
        height: 58px;
        border-radius: 18px;
        color: #2469f2;
        background: #edf3ff;
        font-size: 27px;
    }

    .feature-card:nth-child(2) .feature-icon {
        color: #9447ee;
        background: #f5edff;
    }

    .feature-card:nth-child(3) .feature-icon {
        color: #08b98c;
        background: #e8faf5;
    }

    .feature-card:nth-child(4) .feature-icon {
        color: #f39814;
        background: #fff4e5;
    }

    .feature-card h3 {
        margin: 0 0 5px;
        font-family: var(--font-sans);
        font-size: .95rem;
        font-weight: 800;
    }

    .feature-card p {
        margin: 0;
        color: #53627b;
        font-size: .8rem;
        line-height: 1.55;
    }

    .home-events {
        padding: 30px 24px 72px;
        background: #f6f9ff;
    }

    .home-events-inner {
        max-width: 1200px;
        margin: 0 auto;
    }

    .section-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
    }

    .section-header-row h2 {
        margin: 0;
        color: #112047;
        font-size: clamp(1.65rem, 3vw, 2.1rem);
    }

    .filter-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 24px;
        overflow-x: auto;
        scrollbar-width: none;
    }

    .filter-tabs::-webkit-scrollbar {
        display: none;
    }

    .filter-tab {
        flex: 0 0 auto;
        padding: 8px 16px;
        border: 1px solid #dbe4f3;
        border-radius: 999px;
        color: #52627c;
        background: #fff;
        font-size: .8rem;
        font-weight: 700;
    }

    .filter-tab:hover,
    .filter-tab.active {
        border-color: #2469f2;
        color: #fff;
        background: #2469f2;
    }

    .events-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 22px;
    }

    .home-events .event-card {
        border: 0;
        border-radius: 16px;
        box-shadow: 0 12px 28px rgba(31, 55, 103, .09);
        transition: transform .22s ease, box-shadow .22s ease;
    }

    .home-events .event-card:hover {
        border-color: transparent;
        box-shadow: 0 18px 38px rgba(31, 55, 103, .15);
        transform: translateY(-4px);
    }

    .home-events .event-card-img {
        height: 190px;
    }

    .home-empty {
        padding: 64px 20px;
        border: 1px dashed #cbd6e8;
        border-radius: 18px;
        color: var(--text-muted);
        background: #fff;
        text-align: center;
    }

    .home-empty i {
        display: block;
        margin-bottom: 12px;
        font-size: 48px;
        opacity: .35;
    }

    @media (max-width: 1050px) {

        .hero-calendar,
        .hero-qr,
        .hero-ticket,
        .hero-ring {
            opacity: .35;
            transform: scale(.82);
        }

        .feature-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 820px) {
        .home-hero {
            height: auto;
            min-height: 720px;
            padding-top: 42px;
        }

        .hero-title {
            font-size: clamp(2.05rem, 8vw, 2.8rem);
        }

        .hero-title-subtitle {
            font-size: clamp(1.45rem, 5.8vw, 2rem);
            white-space: nowrap;
        }

        .hero-spark {
            display: none;
        }

        .hero-calendar,
        .hero-qr,
        .hero-ticket,
        .hero-ring,
        .hero-orbit {
            display: none;
        }

        .events-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 600px) {
        .home-page {
            margin-top: -12px;
        }

        .home-hero {
            min-height: 700px;
            padding: 34px 16px 112px;
        }

        .hero-badge {
            min-height: 40px;
            padding: 0 15px;
            font-size: .72rem;
        }

        .hero-title {
            font-size: clamp(2rem, 8.5vw, 2.8rem);
            line-height: 1.06;
        }

        .hero-description {
            font-size: .9rem;
        }

        .hero-actions {
            align-items: stretch;
            flex-direction: column;
            width: min(100%, 340px);
            margin: 0 auto;
        }

        .hero-action {
            width: 100%;
            padding: 0 16px;
        }

        .hero-slider {
            width: 100%;
            height: 258px;
            margin-top: 26px;
            border-radius: 14px;
        }

        .home-slide-content {
            padding: 20px 52px;
        }

        .home-slide-title {
            font-size: 1.45rem;
        }

        .home-slide-meta {
            flex-direction: column;
            gap: 3px;
            font-size: .78rem;
        }

        .slider-nav-btn {
            width: 36px;
            height: 36px;
        }

        .slider-prev {
            left: 9px;
        }

        .slider-next {
            right: 9px;
        }

        .home-wave {
            height: 62px;
        }

        .home-features {
            padding: 0 16px 32px;
        }

        .feature-grid {
            grid-template-columns: 1fr;
            gap: 12px;
            margin-top: -16px;
        }

        .feature-card {
            min-height: 96px;
            padding: 16px;
        }

        .home-events {
            padding: 24px 16px 54px;
        }

        .section-header-row {
            align-items: flex-start;
            flex-direction: column;
        }

        .section-header-row .btn {
            width: auto;
        }

        .events-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="home-page">
    <section class="home-hero">
        <span class="hero-orbit hero-orbit-one" aria-hidden="true"></span>
        <span class="hero-orbit hero-orbit-two" aria-hidden="true"></span>
        <span class="hero-ring" aria-hidden="true"></span>
        <span class="hero-decor hero-calendar" aria-hidden="true"><i class="bi bi-calendar3"></i></span>
        <span class="hero-decor hero-qr" aria-hidden="true"><i class="bi bi-qr-code"></i></span>
        <span class="hero-decor hero-ticket" aria-hidden="true"><i class="bi bi-ticket-perforated"></i></span>

        <div class="hero-content">
            <div class="hero-badge">
                <i class="bi bi-mortarboard"></i>
                Khoa CNTT — ĐH Nha Trang
            </div>

            <span class="hero-spark" aria-hidden="true">⌁</span>

            <h1 class="hero-title">
                <span class="hero-title-line">Khám phá <span class="hero-title-gradient">Sự Kiện</span></span>
                <span class="hero-title-subtitle">Khoa Công nghệ thông tin</span>
            </h1>

            <div class="hero-divider" aria-hidden="true"><i class="bi bi-star-fill"></i></div>

            <p class="hero-description">
                Tham gia hội thảo, seminar, câu lạc bộ và các hoạt động ngoại khóa để
                tích lũy điểm và nâng cao kỹ năng.
            </p>

            <div class="hero-actions">
                <a href="{{ route('events.index') }}" class="hero-action hero-action-primary">
                    <i class="bi bi-calendar3"></i>
                    Xem tất cả sự kiện
                </a>
                @auth
                <a href="{{ route('events.scanner') }}" class="hero-action">
                    <i class="bi bi-qr-code-scan"></i>
                    Quét QR điểm danh
                </a>
                @endauth
                @guest
                <a href="{{ route('login') }}" class="hero-action">
                    <i class="bi bi-person"></i>
                    Đăng nhập ngay
                </a>
                @endguest
            </div>

            @if($suKienNoiBat->count())
            <div class="hero-slider" id="homeHeroSlider">
                <div class="home-slider-track" id="homeSliderTrack">
                    @foreach($suKienNoiBat as $sk)
                    <article class="home-slide">
                        @if($sk->anh_su_kien)
                        <img
                            class="home-slide-image"
                            src="{{ asset('storage/' . $sk->anh_su_kien) }}"
                            alt="{{ $sk->ten_su_kien }}">
                        @else
                        <div class="home-slide-placeholder" aria-hidden="true">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        @endif

                        <div class="home-slide-content">
                            <span class="slide-status">Sắp diễn ra</span>
                            <h2 class="home-slide-title">{{ Str::limit($sk->ten_su_kien, 55) }}</h2>
                            <div class="home-slide-meta">
                                @if($sk->thoi_gian_bat_dau)
                                <span>
                                    <i class="bi bi-calendar3"></i>
                                    {{ $sk->thoi_gian_bat_dau->format('d/m/Y H:i') }}
                                </span>
                                @endif
                                @if($sk->dia_diem)
                                <span>
                                    <i class="bi bi-geo-alt"></i>
                                    {{ Str::limit($sk->dia_diem, 38) }}
                                </span>
                                @endif
                            </div>
                            <a href="{{ route('events.show', $sk->ma_su_kien) }}" class="home-slide-link">
                                Xem chi tiết&nbsp; →
                            </a>
                        </div>
                    </article>
                    @endforeach
                </div>

                @if($suKienNoiBat->count() > 1)
                <button class="slider-nav-btn slider-prev" type="button" onclick="homeSlidePrev()" aria-label="Sự kiện trước">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="slider-nav-btn slider-next" type="button" onclick="homeSlideNext()" aria-label="Sự kiện tiếp theo">
                    <i class="bi bi-chevron-right"></i>
                </button>
                @endif
            </div>

            @if($suKienNoiBat->count() > 1)
            <div class="slider-dots" aria-label="Chọn sự kiện nổi bật">
                @foreach($suKienNoiBat as $index => $sk)
                <button
                    class="slider-dot {{ $index === 0 ? 'active' : '' }}"
                    type="button"
                    onclick="homeGoToSlide({{ $index }})"
                    aria-label="Sự kiện {{ $index + 1 }}"></button>
                @endforeach
            </div>
            @endif
            @endif
        </div>

        <svg class="home-wave" viewBox="0 0 1440 110" preserveAspectRatio="none" aria-hidden="true">
            <path fill="currentColor" d="M0,42 C220,112 460,102 720,94 C990,85 1190,42 1440,76 L1440,110 L0,110 Z"></path>
        </svg>
    </section>

    <section class="home-events">
        <div class="home-events-inner">
            <div class="section-header-row">
                <h2>Sự kiện mới nhất</h2>
                <a href="{{ route('events.index') }}" class="btn btn-outline btn-sm">
                    <i class="bi bi-grid"></i>
                    Xem tất cả
                </a>
            </div>

            @if($loaiSuKien->count())
            <div class="filter-tabs">
                <a href="{{ route('home') }}" class="filter-tab active">Tất cả</a>
                @foreach($loaiSuKien as $l)
                <a href="{{ route('events.index', ['loai' => $l->ma_loai_su_kien]) }}" class="filter-tab">
                    {{ $l->ten_loai }}
                </a>
                @endforeach
            </div>
            @endif

            @if($suKienMoi->count())
            <div class="events-grid">
                @foreach($suKienMoi as $sk)
                <article class="event-card">
                    <div class="event-card-img">
                        @if($sk->anh_su_kien)
                        <img src="{{ asset('storage/' . $sk->anh_su_kien) }}" alt="{{ $sk->ten_su_kien }}">
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
                            <div class="event-meta-item">
                                <i class="bi bi-clock"></i>
                                {{ $sk->thoi_gian_bat_dau->format('H:i, d/m/Y') }}
                            </div>
                            @endif
                            @if($sk->dia_diem)
                            <div class="event-meta-item">
                                <i class="bi bi-geo-alt"></i>
                                {{ Str::limit($sk->dia_diem, 35) }}
                            </div>
                            @endif
                            @if($sk->diem_cong > 0)
                            <div class="event-meta-item">
                                <i class="bi bi-star"></i>
                                +{{ $sk->diem_cong }} điểm
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="event-card-footer">
                        <span class="text-sm text-muted">
                            <i class="bi bi-people"></i>
                            {{ $sk->so_luong_hien_tai }}/{{ $sk->so_luong_toi_da ?: '∞' }}
                        </span>
                        <a href="{{ route('events.show', $sk->ma_su_kien) }}" class="btn btn-outline btn-sm">
                            Xem chi tiết <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </article>
                @endforeach
            </div>
            @else
            <div class="home-empty">
                <i class="bi bi-calendar-x"></i>
                <p>Chưa có sự kiện nào. Hãy quay lại sau!</p>
            </div>
            @endif
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    let homeCurrentSlide = 0;
    const homeSlides = document.querySelectorAll('.home-slide');
    const homeSliderTrack = document.getElementById('homeSliderTrack');
    const homeSliderDots = document.querySelectorAll('.slider-dot');
    const homeHeroSlider = document.getElementById('homeHeroSlider');
    let homeSliderTimer = null;

    function homeGoToSlide(index) {
        if (!homeSlides.length || !homeSliderTrack) {
            return;
        }

        homeCurrentSlide = (index + homeSlides.length) % homeSlides.length;
        homeSliderTrack.style.transform = `translateX(-${homeCurrentSlide * 100}%)`;

        homeSliderDots.forEach((dot, dotIndex) => {
            dot.classList.toggle('active', dotIndex === homeCurrentSlide);
        });
    }

    function homeSlideNext() {
        homeGoToSlide(homeCurrentSlide + 1);
    }

    function homeSlidePrev() {
        homeGoToSlide(homeCurrentSlide - 1);
    }

    function homeStartSlider() {
        if (homeSlides.length <= 1) {
            return;
        }

        homeSliderTimer = window.setInterval(homeSlideNext, 5000);
    }

    function homeStopSlider() {
        if (homeSliderTimer) {
            window.clearInterval(homeSliderTimer);
            homeSliderTimer = null;
        }
    }

    if (homeHeroSlider) {
        homeHeroSlider.addEventListener('mouseenter', homeStopSlider);
        homeHeroSlider.addEventListener('mouseleave', homeStartSlider);
    }

    homeStartSlider();
</script>
@endsection
