@extends('admin.layout')

@section('title', 'Thư viện Media')
@section('page-title', 'Thư viện Media')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-images" style="color:var(--primary)"></i> Thư viện đa phương tiện</div>
        <button class="btn btn-primary" onclick="document.getElementById('uploadModal').style.display='flex'">
            <i class="bi bi-upload"></i> Upload file
        </button>
    </div>

    <div style="padding:20px;">
        @if($media->count())
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:14px;">
            @foreach($media as $m)
            <div style="border:1px solid var(--border);border-radius:12px;overflow:hidden;background:#fff;transition:box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow='none'">
                @if($m->loai_tep === 'hinh_anh')
                <img src="{{ asset('storage/'.$m->duong_dan_tep) }}" alt="{{ $m->ten_tep }}"
                    style="width:100%;height:130px;object-fit:cover;">
                @else
                <div style="width:100%;height:130px;background:var(--bg);display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-{{ $m->loai_tep==='video' ? 'play-circle-fill' : ($m->loai_tep==='tai_lieu' ? 'file-earmark-pdf-fill' : 'file-fill') }}"
                        style="font-size:40px;color:var(--primary);opacity:0.6;"></i>
                </div>
                @endif
                <div style="padding:10px 12px;">
                    <div style="font-size:12px;font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $m->ten_tep }}">{{ $m->ten_tep }}</div>
                    <div style="font-size:11px;color:var(--text-light);margin-top:2px;">{{ $m->loaiTepLabel }}</div>
                    <div style="margin-top:8px;display:flex;justify-content:flex-end;">
                        <form method="POST" action="{{ route('admin.media.destroy', $m->ma_phuong_tien) }}" onsubmit="return confirm('Xóa file này?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($media->hasPages())
        <div style="margin-top:20px;">{{ $media->links() }}</div>
        @endif
        @else
        <div style="text-align:center;padding:60px;color:var(--text-light);">
            <i class="bi bi-images" style="font-size:48px;display:block;margin-bottom:12px;opacity:0.3;"></i>
            <p>Chưa có file nào trong thư viện</p>
        </div>
        @endif
    </div>
</div>

<!-- Upload Modal -->
<div id="uploadModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:999;align-items:center;justify-content:center;padding:20px;">
    <div style="background:#fff;border-radius:16px;width:100%;max-width:480px;">
        <div style="padding:20px 24px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
            <h3 style="font-size:16px;font-weight:700;">Upload file</h3>
            <button onclick="document.getElementById('uploadModal').style.display='none'" style="background:none;border:none;font-size:20px;cursor:pointer;">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.media.upload') }}" enctype="multipart/form-data" style="padding:24px;">
            @csrf
            <div class="mb-3">
                <label class="form-label">Chọn file *</label>
                <input type="file" name="file" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Loại file *</label>
                <select name="loai_tep" class="form-control" required>
                    <option value="hinh_anh">Hình ảnh</option>
                    <option value="video">Video</option>
                    <option value="tai_lieu">Tài liệu</option>
                    <option value="khac">Khác</option>
                </select>
            </div>
            <div class="mb-3">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                    <input type="checkbox" name="la_cong_khai" value="1"> Công khai
                </label>
            </div>
            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Upload</button>
                <button type="button" onclick="document.getElementById('uploadModal').style.display='none'" class="btn btn-secondary">Hủy</button>
            </div>
        </form>
    </div>
</div>
@endsection
