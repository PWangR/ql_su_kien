@extends('layouts.app')

@section('title', $suKien->ten_su_kien)

@section('styles')
<style>
.event-banner { width:100%;max-height:360px;object-fit:cover;border-radius:16px;margin-bottom:24px; }
.event-banner-placeholder { height:240px;background:linear-gradient(135deg,#dbeafe,#eff6ff);border-radius:16px;display:flex;align-items:center;justify-content:center;margin-bottom:24px; }
.info-box { background:#fff;border-radius:14px;border:1px solid #e2e8f0;padding:20px;margin-bottom:16px; }
.info-row { display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid #f1f5f9;font-size:14px; }
.info-row:last-child { border-bottom:none; }
.info-row i { color:#2563eb;font-size:16px;width:20px; }
.info-label { color:#64748b;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.4px; }
.info-value { font-weight:600; }
.btn-register-danger { background:#fee2e2;color:#b91c1c;border:1.5px solid #fecaca;border-radius:10px;padding:12px 28px;font-size:14px;font-weight:700;cursor:pointer;width:100%;display:flex;align-items:center;justify-content:center;gap:8px;transition:all .2s;font-family:'Inter',sans-serif; }
.btn-register-danger:hover { background:#fecaca; }
.btn-register-primary { background:linear-gradient(135deg,#2563eb,#3b82f6);color:#fff;border:none;border-radius:10px;padding:12px 28px;font-size:14px;font-weight:700;cursor:pointer;width:100%;display:flex;align-items:center;justify-content:center;gap:8px;transition:all .2s;font-family:'Inter',sans-serif;box-shadow:0 4px 14px rgba(37,99,235,.3); }
.btn-register-primary:hover { transform:translateY(-1px);box-shadow:0 6px 20px rgba(37,99,235,.4); }
.badge-trang-thai { display:inline-flex;align-items:center;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600; }
</style>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start;">

<!-- Main -->
<div>
    @if($suKien->anh_su_kien)
    <img src="{{ asset('storage/'.$suKien->anh_su_kien) }}" alt="{{ $suKien->ten_su_kien }}" class="event-banner">
    @else
    <div class="event-banner-placeholder">
        <i class="bi bi-calendar-event-fill" style="font-size:72px;color:#93c5fd;"></i>
    </div>
    @endif

    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:16px;flex-wrap:wrap;">
        <div>
            @if($suKien->loaiSuKien)
            <span style="background:#eff6ff;color:#2563eb;font-size:12px;font-weight:600;padding:3px 12px;border-radius:6px;display:inline-block;margin-bottom:8px;">
                {{ $suKien->loaiSuKien->ten_loai }}
            </span>
            @endif
            <h1 style="font-family:'Montserrat',sans-serif;font-size:24px;font-weight:800;color:#0f172a;line-height:1.3;">
                {{ $suKien->ten_su_kien }}
            </h1>
        </div>
        @php
            $colorMap = ['sap_to_chuc'=>['bg'=>'#dbeafe','t'=>'#1d4ed8','l'=>'Sắp tổ chức'],'dang_dien_ra'=>['bg'=>'#dcfce7','t'=>'#15803d','l'=>'Đang diễn ra'],'da_ket_thuc'=>['bg'=>'#f1f5f9','t'=>'#475569','l'=>'Đã kết thúc'],'huy'=>['bg'=>'#fee2e2','t'=>'#b91c1c','l'=>'Đã hủy']];
            $c = $colorMap[$suKien->trang_thai] ?? $colorMap['da_ket_thuc'];
        @endphp
        <span class="badge-trang-thai" style="background:{{ $c['bg'] }};color:{{ $c['t'] }};">{{ $c['l'] }}</span>
    </div>

    @if($suKien->mo_ta_chi_tiet)
    <div class="info-box">
        <h3 style="font-size:15px;font-weight:700;margin-bottom:12px;color:#1e293b;"><i class="bi bi-file-text" style="color:#2563eb;"></i> Mô tả</h3>
        <div style="font-size:14px;line-height:1.8;color:#374151;white-space:pre-line;">{{ $suKien->mo_ta_chi_tiet }}</div>
    </div>
    @endif

    <!-- Sự kiện liên quan -->
    @if($suKienLienQuan->count())
    <div class="info-box">
        <h3 style="font-size:15px;font-weight:700;margin-bottom:16px;"><i class="bi bi-link-45deg" style="color:#2563eb;"></i> Sự kiện liên quan</h3>
        <div style="display:flex;flex-direction:column;gap:10px;">
            @foreach($suKienLienQuan as $sk)
            <a href="{{ route('events.show', $sk->ma_su_kien) }}" style="display:flex;align-items:center;gap:12px;text-decoration:none;padding:10px;border-radius:10px;border:1px solid #e2e8f0;transition:background .2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                <div style="width:48px;height:48px;border-radius:10px;background:linear-gradient(135deg,#dbeafe,#eff6ff);display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;">
                    @if($sk->anh_su_kien)<img src="{{ asset('storage/'.$sk->anh_su_kien) }}" style="width:100%;height:100%;object-fit:cover;">@else<i class="bi bi-calendar-event-fill" style="color:#93c5fd;"></i>@endif
                </div>
                <div>
                    <div style="font-size:13px;font-weight:600;color:#1e293b;">{{ Str::limit($sk->ten_su_kien, 45) }}</div>
                    @if($sk->thoi_gian_bat_dau)<div style="font-size:12px;color:#64748b;">{{ $sk->thoi_gian_bat_dau->format('d/m/Y') }}</div>@endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Sidebar -->
<div style="position:sticky;top:90px;">
    <div class="info-box">
        <div class="info-label" style="margin-bottom:12px;">Thông tin sự kiện</div>

        @if($suKien->thoi_gian_bat_dau)
        <div class="info-row">
            <i class="bi bi-clock-fill"></i>
            <div>
                <div class="info-label">Bắt đầu</div>
                <div class="info-value">{{ $suKien->thoi_gian_bat_dau->format('H:i, d/m/Y') }}</div>
            </div>
        </div>
        @endif

        @if($suKien->thoi_gian_ket_thuc)
        <div class="info-row">
            <i class="bi bi-clock"></i>
            <div>
                <div class="info-label">Kết thúc</div>
                <div class="info-value">{{ $suKien->thoi_gian_ket_thuc->format('H:i, d/m/Y') }}</div>
            </div>
        </div>
        @endif

        @if($suKien->dia_diem)
        <div class="info-row">
            <i class="bi bi-geo-alt-fill"></i>
            <div>
                <div class="info-label">Địa điểm</div>
                <div class="info-value">{{ $suKien->dia_diem }}</div>
            </div>
        </div>
        @endif

        <div class="info-row">
            <i class="bi bi-people-fill"></i>
            <div>
                <div class="info-label">Đăng ký / Tối đa</div>
                <div class="info-value">{{ $suKien->so_luong_hien_tai }} / {{ $suKien->so_luong_toi_da ?: 'Không giới hạn' }}</div>
            </div>
        </div>

        @if($suKien->diem_cong > 0)
        <div class="info-row">
            <i class="bi bi-star-fill" style="color:#f59e0b;"></i>
            <div>
                <div class="info-label">Điểm cộng</div>
                <div class="info-value" style="color:#d97706;">+{{ $suKien->diem_cong }} điểm</div>
            </div>
        </div>
        @endif

        <!-- Nút đăng ký -->
        @auth
        <div style="margin-top:16px;">
            @if($daDangKy)
            <form method="POST" action="{{ route('events.huy-dang-ky', $suKien->ma_su_kien) }}">
                @csrf
                <button type="submit" class="btn-register-danger" onclick="return confirm('Hủy đăng ký sự kiện này?')">
                    <i class="bi bi-x-circle-fill"></i> Hủy đăng ký
                </button>
            </form>
            <div style="text-align:center;margin-top:10px;font-size:13px;color:#16a34a;font-weight:600;">
                <i class="bi bi-check-circle-fill"></i> Bạn đã đăng ký sự kiện này
            </div>
            @elseif($suKien->trang_thai === 'da_ket_thuc' || $suKien->trang_thai === 'huy')
            <button disabled style="background:#f1f5f9;color:#94a3b8;border:none;border-radius:10px;padding:12px 28px;font-size:14px;font-weight:700;width:100%;cursor:not-allowed;">
                Sự kiện đã kết thúc
            </button>
            @elseif($suKien->so_luong_toi_da > 0 && $suKien->so_luong_hien_tai >= $suKien->so_luong_toi_da)
            <button disabled style="background:#fee2e2;color:#b91c1c;border:none;border-radius:10px;padding:12px 28px;font-size:14px;font-weight:700;width:100%;cursor:not-allowed;">
                <i class="bi bi-exclamation-circle-fill"></i> Đã đầy chỗ
            </button>
            @else
            <form method="POST" action="{{ route('events.dang-ky', $suKien->ma_su_kien) }}">
                @csrf
                <button type="submit" class="btn-register-primary">
                    <i class="bi bi-plus-circle-fill"></i> Đăng ký tham gia
                </button>
            </form>
            @endif
        </div>
        @else
        <div style="margin-top:16px;text-align:center;">
            <a href="{{ route('login') }}" class="btn-register-primary" style="text-decoration:none;display:flex;">
                <i class="bi bi-box-arrow-in-right"></i> Đăng nhập để đăng ký
            </a>
        </div>
        @endauth
    </div>
</div>

</div>
@endsection
