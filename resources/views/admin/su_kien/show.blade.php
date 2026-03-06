@extends('admin.layout')

@section('title', 'Chi tiết sự kiện')
@section('page-title', 'Chi tiết sự kiện')

@section('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div></div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.su-kien.edit', $suKien->ma_su_kien) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Chỉnh sửa</a>
        <a href="{{ route('admin.su-kien.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 360px;gap:20px;">

<div>
@php
    $layout = $suKien->bo_cuc ?? ['banner', 'header', 'info', 'description', 'gallery'];
@endphp

@foreach($layout as $component)
    @switch($component)
        @case('banner')
            @if($suKien->anh_su_kien)
            <img src="{{ asset('storage/'.$suKien->anh_su_kien) }}" alt="Ảnh sự kiện"
                style="width:100%;max-height:350px;object-fit:cover;border-radius:16px;margin-bottom:25px;box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            @endif
            @break

        @case('header')
            <div class="mb-4">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-soft-primary text-primary px-3 py-2" style="font-size: 12px; border-radius: 8px; background-color: #eef2ff; border: 1px solid #e0e7ff;">
                        {{ $suKien->loaiSuKien->ten_loai ?? 'Sự kiện' }}
                    </span>
                    <span class="badge bg-{{ $suKien->trang_thai_color }} px-3 py-2" style="font-size: 12px; border-radius: 8px;">
                        {{ $suKien->trang_thai_label }}
                    </span>
                </div>
                <h2 style="font-family:'Montserrat',sans-serif;font-size:28px;font-weight:800;color:var(--secondary);margin-bottom:12px; line-height: 1.3;">
                    {{ $suKien->ten_su_kien }}
                </h2>
            </div>
            @break

        @case('info')
            <div class="card border-0 shadow-sm mb-4" style="background-color: #f8fafc; border-radius: 12px;">
                <div class="card-body p-4">
                    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));gap:20px;">
                        <div>
                            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-light); margin-bottom: 4px;">Địa điểm</div>
                            <div style="font-size:14px;font-weight:600;"><i class="bi bi-geo-alt text-danger me-1"></i> {{ $suKien->dia_diem ?: '—' }}</div>
                        </div>
                        <div>
                            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-light); margin-bottom: 4px;">Thời gian bắt đầu</div>
                            <div style="font-size:14px;font-weight:600;"><i class="bi bi-calendar-event text-primary me-1"></i> {{ $suKien->thoi_gian_bat_dau?->format('H:i d/m/Y') }}</div>
                        </div>
                        <div>
                            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-light); margin-bottom: 4px;">Thời gian kết thúc</div>
                            <div style="font-size:14px;font-weight:600;"><i class="bi bi-calendar-check text-success me-1"></i> {{ $suKien->thoi_gian_ket_thuc?->format('H:i d/m/Y') }}</div>
                        </div>
                        <div>
                            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-light); margin-bottom: 4px;">Quy mô & Điểm</div>
                            <div style="font-size:14px;font-weight:600;">
                                <i class="bi bi-people text-info me-1"></i> {{ $suKien->so_luong_hien_tai }}/{{ $suKien->so_luong_toi_da ?: '∞' }} 
                                <span class="mx-2 text-muted">|</span>
                                <i class="bi bi-plus-circle text-warning me-1"></i> +{{ $suKien->diem_cong ?: '0' }} điểm
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @break

        @case('description')
            @if($suKien->mo_ta_chi_tiet)
            <div class="mb-5">
                <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-light);margin-bottom:15px; display: flex; align-items: center;">
                    <span style="height: 1px; width: 30px; background: #e2e8f0; margin-right: 10px;"></span>
                    Nội dung chi tiết
                </div>
                <div class="ql-snow">
                    <div class="ql-editor" style="padding:0;font-size:15px;color:var(--text); line-height: 1.8;">{!! $suKien->mo_ta_chi_tiet !!}</div>
                </div>
            </div>
            @endif
            @break

        @case('gallery')
            @if($suKien->media->where('loai_tep', 'hinh_anh')->count() > 0)
            <div class="mb-5">
                <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-light);margin-bottom:15px; display: flex; align-items: center;">
                    <span style="height: 1px; width: 30px; background: #e2e8f0; margin-right: 10px;"></span>
                    Hình ảnh liên quan
                </div>
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(180px, 1fr)); gap:15px;">
                    @foreach($suKien->media->where('loai_tep', 'hinh_anh') as $img)
                    <div class="gallery-item" style="border-radius:12px; overflow:hidden; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1); border:1px solid #f1f5f9; cursor:zoom-in;">
                        <a href="{{ asset('storage/'.$img->duong_dan_tep) }}" target="_blank">
                            <img src="{{ asset('storage/'.$img->duong_dan_tep) }}" style="width:100%; height:140px; object-fit:cover; transition:transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @break
    @endswitch
@endforeach
</div>

<!-- Sidebar info -->
<div>
    <div class="card mb-3">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-people-fill" style="color:var(--success)"></i> Danh sách đăng ký</div>
            <span class="badge badge-primary">{{ $suKien->dangKy->count() }}</span>
        </div>
        <div style="max-height:400px;overflow-y:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Họ tên</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suKien->dangKy as $dk)
                    @php
                        $statMap = ['da_dang_ky'=>['bg'=>'#dbeafe','t'=>'#1d4ed8','l'=>'Đã đăng ký'],'da_tham_gia'=>['bg'=>'#dcfce7','t'=>'#15803d','l'=>'Đã tham gia'],'vang_mat'=>['bg'=>'#fef3c7','t'=>'#92400e','l'=>'Vắng mặt'],'huy'=>['bg'=>'#fee2e2','t'=>'#b91c1c','l'=>'Đã hủy']];
                        $s = $statMap[$dk->trang_thai_tham_gia] ?? $statMap['da_dang_ky'];
                    @endphp
                    <tr>
                        <td style="font-size:13px;">{{ $dk->nguoiDung->ho_ten ?? '—' }}<br><small style="color:var(--text-light);">{{ $dk->nguoiDung->ma_sinh_vien ?? '' }}</small></td>
                        <td><span class="badge" style="background:{{ $s['bg'] }};color:{{ $s['t'] }};">{{ $s['l'] }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="2" style="text-align:center;padding:20px;color:var(--text-light);">Chưa có người đăng ký</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>
@endsection
