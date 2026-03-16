@extends('admin.layout')

@section('title', 'Chi tiết sự kiện')
@section('page-title', 'Chi tiết sự kiện')

@section('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<style>

.event-banner{
    width:100%;
    height:320px;
    object-fit:cover;
    border-radius:14px;
}

.event-title{
    font-size:28px;
    font-weight:800;
    margin-top:10px;
}

.section-title{
    font-size:13px;
    font-weight:700;
    letter-spacing:.6px;
    text-transform:uppercase;
    color:#64748b;
    margin-bottom:15px;
}

.info-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
    gap:18px;
}

.info-box{
    background:#f8fafc;
    border-radius:10px;
    padding:14px;
}

.info-label{
    font-size:11px;
    text-transform:uppercase;
    color:#94a3b8;
    font-weight:700;
}

.info-value{
    font-size:14px;
    font-weight:600;
    margin-top:4px;
}

.gallery-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(180px,1fr));
    gap:14px;
}

.gallery-grid img{
    width:100%;
    height:140px;
    object-fit:cover;
    border-radius:10px;
    transition:.25s;
}

.gallery-grid img:hover{
    transform:scale(1.06);
}

.participant-table td{
    font-size:13px;
}

</style>
@endsection


@section('content')

<div class="d-flex justify-content-between mb-3">
    <div></div>

    <div class="d-flex gap-2">
        <a href="{{ route('admin.su-kien.edit',$suKien->ma_su_kien) }}"
           class="btn btn-warning">
            <i class="bi bi-pencil"></i> Chỉnh sửa
        </a>

        <a href="{{ route('admin.su-kien.index') }}"
           class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
</div>


<div class="row g-4">

{{-- LEFT CONTENT --}}
<div class="col-lg-8">

@php
$layout = $suKien->bo_cuc ?? ['banner','header','info','description','gallery'];
@endphp

@foreach($layout as $component)

@switch($component)

{{-- BANNER --}}
@case('banner')
@if($suKien->anh_su_kien)
<div class="card border-0 shadow-sm mb-3">
<div class="card-body p-2">

<img class="event-banner"
     src="{{ asset('storage/'.$suKien->anh_su_kien) }}">

</div>
</div>
@endif
@break


{{-- HEADER --}}
@case('header')

<div class="card shadow-sm border-0 mb-3">
<div class="card-body">

<div class="d-flex gap-2 mb-2">

<span class="badge bg-primary">
{{ $suKien->loaiSuKien->ten_loai ?? 'Sự kiện' }}
</span>

<span class="badge bg-{{ $suKien->trang_thai_color }}">
{{ $suKien->trang_thai_label }}
</span>

</div>

<div class="event-title">
{{ $suKien->ten_su_kien }}
</div>

</div>
</div>

@break


{{-- INFO --}}
@case('info')

<div class="card shadow-sm border-0 mb-3">
<div class="card-body">

<div class="section-title">Thông tin sự kiện</div>

<div class="info-grid">

<div class="info-box">
<div class="info-label">Địa điểm</div>
<div class="info-value">
<i class="bi bi-geo-alt text-danger"></i>
{{ $suKien->dia_diem ?: '—' }}
</div>
</div>

<div class="info-box">
<div class="info-label">Bắt đầu</div>
<div class="info-value">
<i class="bi bi-calendar-event text-primary"></i>
{{ $suKien->thoi_gian_bat_dau?->format('H:i d/m/Y') }}
</div>
</div>

<div class="info-box">
<div class="info-label">Kết thúc</div>
<div class="info-value">
<i class="bi bi-calendar-check text-success"></i>
{{ $suKien->thoi_gian_ket_thuc?->format('H:i d/m/Y') }}
</div>
</div>

<div class="info-box">
<div class="info-label">Quy mô</div>
<div class="info-value">
<i class="bi bi-people text-info"></i>
{{ $suKien->so_luong_hien_tai }}/{{ $suKien->so_luong_toi_da ?: '∞' }}
</div>
</div>

<div class="info-box">
<div class="info-label">Điểm cộng</div>
<div class="info-value">
<i class="bi bi-star text-warning"></i>
+{{ $suKien->diem_cong ?: 0 }}
</div>
</div>

</div>

</div>
</div>

@break


{{-- DESCRIPTION --}}
@case('description')

@if($suKien->mo_ta_chi_tiet)

<div class="card shadow-sm border-0 mb-3">

<div class="card-body">

<div class="section-title">
Nội dung chi tiết
</div>

<div class="ql-snow">
<div class="ql-editor" style="padding:0">
{!! $suKien->mo_ta_chi_tiet !!}
</div>
</div>

</div>
</div>

@endif

@break


{{-- GALLERY --}}
@case('gallery')

@if($suKien->media->where('loai_tep','hinh_anh')->count())

<div class="card shadow-sm border-0 mb-3">

<div class="card-body">

<div class="section-title">
Hình ảnh sự kiện
</div>

<div class="gallery-grid">

@foreach($suKien->media->where('loai_tep','hinh_anh') as $img)

<a href="{{ asset('storage/'.$img->duong_dan_tep) }}" target="_blank">

<img src="{{ asset('storage/'.$img->duong_dan_tep) }}">

</a>

@endforeach

</div>

</div>
</div>

@endif

@break

@endswitch

@endforeach

</div>


{{-- SIDEBAR --}}
<div class="col-lg-4">

<div class="card shadow-sm mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <i class="bi bi-qr-code-scan"></i>
            QR Ä‘iá»ƒm danh
        </div>
        <a href="{{ route('events.qr-checkin', $suKien->qr_checkin_token) }}" target="_blank" class="btn btn-secondary btn-sm">
            <i class="bi bi-box-arrow-up-right"></i> Má»Ÿ link
        </a>
    </div>
    <div class="card-body text-center">
        @if($suKien->qr_code_path)
            <img src="{{ asset('storage/'.$suKien->qr_code_path) }}" alt="QR check-in" style="max-width:220px;width:100%;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,0.08);">
            <div class="mt-3 d-flex justify-content-center gap-2">
                <a class="btn btn-primary btn-sm" href="{{ asset('storage/'.$suKien->qr_code_path) }}" download="qr-sukien-{{ $suKien->ma_su_kien }}.svg">
                    <i class="bi bi-download"></i> Táº£i xuá»‘ng
                </a>
                <a class="btn btn-secondary btn-sm" href="{{ route('events.qr-checkin', $suKien->qr_checkin_token) }}" target="_blank">
                    <i class="bi bi-phone"></i> QuÃ©t thá»­
                </a>
            </div>
        @else
            <div class="text-muted">ChÆ°a cÃ³ mÃ£ QR</div>
        @endif
    </div>
</div>

<div class="card shadow-sm">

<div class="card-header d-flex justify-content-between align-items-center">

<div>
<i class="bi bi-people"></i>
Danh sách đăng ký
</div>

<span class="badge bg-primary">
{{ $suKien->dangKy->count() }}
</span>

</div>


<div style="max-height:420px;overflow:auto">

<table class="table table-hover participant-table mb-0">

<thead>
<tr>
<th>Họ tên</th>
<th>Trạng thái</th>
</tr>
</thead>

<tbody>

@forelse($suKien->dangKy as $dk)

@php
$statMap=[
'da_dang_ky'=>['bg'=>'#dbeafe','t'=>'#1d4ed8','l'=>'Đã đăng ký'],
'da_tham_gia'=>['bg'=>'#dcfce7','t'=>'#15803d','l'=>'Đã tham gia'],
'vang_mat'=>['bg'=>'#fef3c7','t'=>'#92400e','l'=>'Vắng mặt'],
'huy'=>['bg'=>'#fee2e2','t'=>'#b91c1c','l'=>'Đã hủy']
];
$s=$statMap[$dk->trang_thai_tham_gia] ?? $statMap['da_dang_ky'];
@endphp

<tr>

<td>

{{ $dk->nguoiDung->ho_ten ?? '—' }}

<br>

<small class="text-muted">
{{ $dk->nguoiDung->ma_sinh_vien ?? '' }}
</small>

</td>

<td>

<span class="badge"
style="background:{{ $s['bg'] }};color:{{ $s['t'] }}">

{{ $s['l'] }}

</span>

</td>

</tr>

@empty

<tr>
<td colspan="2" class="text-center p-4 text-muted">
Chưa có người đăng ký
</td>
</tr>

@endforelse

</tbody>

</table>

</div>

</div>

</div>

</div>

@endsection
