@extends('layouts.app')

@section('title', 'Danh sách sự kiện')

@section('styles')
<style>
    .page-hero {
        background: linear-gradient(135deg, #0f172a, #1e3a8a);
        padding: 40px 0 32px;
        margin: -24px -24px 32px;
        color: #fff;
        text-align: center;
    }

    .page-hero h1 {
        font-family: 'Montserrat', sans-serif;
        font-size: 28px;
        font-weight: 800;
    }

    .page-hero p {
        color: #94a3b8;
        margin-top: 6px;
        font-size: 14px;
    }

    .filter-bar {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        padding: 16px 20px;
        margin-bottom: 28px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
    }

    .filter-input {
        padding: 9px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: border-color 0.2s;
    }

    .filter-input:focus {
        border-color: #2563eb;
    }

    .event-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }

    .event-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .event-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
    }

    .event-img {
        height: 160px;
        background: linear-gradient(135deg, #dbeafe, #eff6ff);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .event-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .event-body {
        padding: 16px;
    }

    .event-title {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .event-meta-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12.5px;
        color: #64748b;
        margin-bottom: 4px;
    }

    .event-meta-row i {
        color: #2563eb;
        width: 14px;
    }

    .event-footer {
        padding: 12px 16px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-detail {
        background: #eff6ff;
        color: #2563eb;
        border: none;
        border-radius: 8px;
        padding: 6px 16px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-detail:hover {
        background: #2563eb;
        color: #fff;
    }

    .badge-type {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(37, 99, 235, 0.9);
        color: #fff;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 6px;
    }

    .badge-full {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(239, 68, 68, 0.9);
        color: #fff;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 6px;
    }
</style>
@endsection

@section('content')

<div class="page-hero">
    <h1><i class="bi bi-calendar3"></i> Sự kiện</h1>
    <p>Khám phá các sự kiện của Khoa CNTT - ĐH Nha Trang</p>
</div>

<!-- Filter -->
<form method="GET" class="filter-bar">
    <input type="text" name="search" class="filter-input" placeholder="🔍 Tìm kiếm sự kiện..." value="{{ request('search') }}" style="flex:1;min-width:200px;">
    <select name="loai" class="filter-input" style="min-width:160px;">
        <option value="">-- Loại sự kiện --</option>
        @foreach($loaiSuKien as $l)
        <option value="{{ $l->ma_loai_su_kien }}" {{ request('loai') == $l->ma_loai_su_kien ? 'selected' : '' }}>{{ $l->ten_loai }}</option>
        @endforeach
    </select>
    <select name="trang_thai" class="filter-input">
        <option value="">-- Trạng thái --</option>
        <option value="sap_to_chuc" {{ request('trang_thai')=='sap_to_chuc'  ?'selected':'' }}>Sắp tổ chức</option>
        <option value="dang_dien_ra" {{ request('trang_thai')=='dang_dien_ra' ?'selected':'' }}>Đang diễn ra</option>
        <option value="da_ket_thuc" {{ request('trang_thai')=='da_ket_thuc'  ?'selected':'' }}>Đã kết thúc</option>
    </select>
    <button type="submit" style="background:#2563eb;color:#fff;border:none;border-radius:8px;padding:9px 18px;font-size:14px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;">
        <i class="bi bi-search"></i> Tìm
    </button>
    <a href="{{ route('events.index') }}" style="background:#f1f5f9;color:#475569;border-radius:8px;padding:9px 16px;font-size:14px;font-weight:600;text-decoration:none;display:flex;align-items:center;gap:5px;">
        <i class="bi bi-x"></i> Xóa
    </a>
</form>

@if($suKien->count())
<div class="event-grid">
    @foreach($suKien as $sk)
    <div class="event-card">
        <div class="event-img">
            @if($sk->anh_su_kien)
            <img src="{{ asset('storage/'.$sk->anh_su_kien) }}" alt="{{ $sk->ten_su_kien }}">
            @else
            <i class="bi bi-calendar-event-fill" style="font-size:44px;color:#93c5fd;"></i>
            @endif
            @if($sk->loaiSuKien)
            <span class="badge-type">{{ $sk->loaiSuKien->ten_loai }}</span>
            @endif
            @if($sk->so_luong_toi_da > 0 && $sk->so_luong_hien_tai >= $sk->so_luong_toi_da)
            <span class="badge-full">Đã đầy</span>
            @endif
        </div>
        <div class="event-body">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                <h3 class="event-title">{{ Str::limit($sk->ten_su_kien, 35) }}</h3>
                @php
                $colorMap = ['sap_to_chuc'=>['bg'=>'#dbeafe','t'=>'#1d4ed8','l'=>'Sắp'],'dang_dien_ra'=>['bg'=>'#dcfce7','t'=>'#15803d','l'=>'Đang'],'da_ket_thuc'=>['bg'=>'#f1f5f9','t'=>'#475569','l'=>'Kết thúc'],'huy'=>['bg'=>'#fee2e2','t'=>'#b91c1c','l'=>'Hủy']];
                $status = $sk->trang_thai_thuc_te;
                $c = $colorMap[$status] ?? $colorMap['da_ket_thuc'];
                @endphp
                <span style="background:{{ $c['bg'] }};color:{{ $c['t'] }};font-size:10px;font-weight:700;padding:2px 8px;border-radius:4px;white-space:nowrap;">{{ $c['l'] }}</span>
            </div>
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
        <div class="event-footer">
            <div style="font-size:12px;color:#64748b;">
                <i class="bi bi-people"></i> {{ $sk->so_luong_hien_tai }}/{{ $sk->so_luong_toi_da ?: '∞' }}
                @if(in_array($sk->ma_su_kien, $daDangKyIds))
                &nbsp;<span style="color:#16a34a;font-weight:600;"><i class="bi bi-check-circle-fill"></i> Đã đăng ký</span>
                @endif
            </div>
            <a href="{{ route('events.show', $sk->ma_su_kien) }}" class="btn-detail">
                Chi tiết <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
    @endforeach
</div>

<div style="margin-top:28px;">{{ $suKien->links() }}</div>
@else
<div style="text-align:center;padding:80px;color:#64748b;">
    <i class="bi bi-calendar-x" style="font-size:64px;display:block;margin-bottom:16px;opacity:0.3;"></i>
    <h3 style="font-size:18px;font-weight:700;margin-bottom:8px;">Không tìm thấy sự kiện</h3>
    <p>Thử tìm kiếm với từ khóa khác hoặc xem tất cả sự kiện.</p>
    <a href="{{ route('events.index') }}" class="btn-detail" style="margin-top:16px;display:inline-flex;">Xem tất cả</a>
</div>
@endif
@endsection