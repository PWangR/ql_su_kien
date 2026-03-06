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
    <!-- Thông tin chính -->
    <div class="card mb-3">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-info-circle" style="color:var(--primary)"></i> Thông tin sự kiện</div>
        </div>
        <div class="card-body">
            @if($suKien->anh_su_kien)
            <img src="{{ asset('storage/'.$suKien->anh_su_kien) }}" alt="Ảnh sự kiện"
                style="width:100%;max-height:280px;object-fit:cover;border-radius:12px;margin-bottom:20px;">
            @endif

            <h2 style="font-family:'Montserrat',sans-serif;font-size:20px;font-weight:800;color:var(--secondary);margin-bottom:12px;">
                {{ $suKien->ten_su_kien }}
            </h2>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                <div>
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-light);">Loại sự kiện</div>
                    <div style="font-size:14px;font-weight:600;">{{ $suKien->loaiSuKien->ten_loai ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-light);">Địa điểm</div>
                    <div style="font-size:14px;font-weight:600;">{{ $suKien->dia_diem ?: '—' }}</div>
                </div>
                <div>
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-light);">Thời gian bắt đầu</div>
                    <div style="font-size:14px;font-weight:600;">{{ $suKien->thoi_gian_bat_dau?->format('H:i d/m/Y') }}</div>
                </div>
                <div>
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-light);">Thời gian kết thúc</div>
                    <div style="font-size:14px;font-weight:600;">{{ $suKien->thoi_gian_ket_thuc?->format('H:i d/m/Y') }}</div>
                </div>
                <div>
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-light);">Số lượng</div>
                    <div style="font-size:14px;font-weight:600;">{{ $suKien->so_luong_hien_tai }}/{{ $suKien->so_luong_toi_da ?: '∞' }}</div>
                </div>
                <div>
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-light);">Điểm cộng</div>
                    <div style="font-size:14px;font-weight:600;">{{ $suKien->diem_cong ?: 'Không có' }}</div>
                </div>
            </div>

            @if($suKien->mo_ta_chi_tiet)
            <div class="mt-4">
                <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-light);margin-bottom:8px;">Mô tả</div>
                <div class="ql-snow">
                    <div class="ql-editor" style="padding:0;font-size:14px;color:var(--text);">{!! $suKien->mo_ta_chi_tiet !!}</div>
                </div>
            </div>
            @endif

            @if($suKien->media->where('loai_tep', 'hinh_anh')->count() > 0)
            <div class="mt-5 pt-3 border-top">
                <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-light);margin-bottom:15px;">Hình ảnh liên quan</div>
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(140px, 1fr)); gap:15px;">
                    @foreach($suKien->media->where('loai_tep', 'hinh_anh') as $img)
                    <div class="gallery-item" style="border-radius:12px; overflow:hidden; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1); border:1px solid #f1f5f9; cursor:zoom-in;">
                        <a href="{{ asset('storage/'.$img->duong_dan_tep) }}" target="_blank">
                            <img src="{{ asset('storage/'.$img->duong_dan_tep) }}" style="width:100%; height:100px; object-fit:cover; transition:transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
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
