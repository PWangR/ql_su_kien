@extends('admin.layout')

@section('title', 'Thư viện Media')
@section('page-title', 'Thư viện Media')

@section('styles')
<style>
.modal-layer { position:fixed; inset:0; display:none; align-items:center; justify-content:center; background:rgba(0,0,0,0.4); z-index:999; padding:18px; }
.modal-layer.show { display:flex; }
.modal-card { background:var(--card); border:1px solid var(--border); border-radius:var(--border-radius-md); width:100%; max-width:480px; overflow:hidden; }
.modal-card header { padding:16px 20px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; }
.modal-card .body { padding:20px; }
.media-pagination {
    margin-top: var(--space-xl);
    display: flex;
    justify-content: center;
}
</style>
@endsection

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-images" style="color:var(--accent)"></i> Thư viện đa phương tiện</div>
        <button class="btn btn-primary" onclick="document.getElementById('uploadModal').classList.add('show')">
            <i class="bi bi-upload"></i> Upload file
        </button>
    </div>

    <div class="card-body">
        <p class="text-sm text-muted" style="margin-bottom:16px;">Thư viện này hiển thị các tệp gốc để tái sử dụng. Ảnh được gắn vào sự kiện sẽ không tạo thêm bản sao trong kho media.</p>
        @if($media->count())
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;">
            @foreach($media as $m)
            <div style="border:1px solid var(--border);background:var(--card);transition:border-color 0.2s;">
                @if($m->loai_tep === 'hinh_anh')
                <div style="height:140px;border-bottom:1px solid var(--border);">
                    <img src="{{ asset('storage/'.$m->duong_dan_tep) }}" alt="{{ $m->ten_tep }}" style="width:100%;height:100%;object-fit:cover;">
                </div>
                @else
                <div style="height:140px;background:var(--bg-alt);border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-{{ $m->loai_tep==='video' ? 'play-circle' : ($m->loai_tep==='tai_lieu' ? 'file-earmark-pdf' : 'file-earmark') }}"
                        style="font-size:36px;color:var(--border);"></i>
                </div>
                @endif
                <div style="padding:12px;">
                    <div style="font-size:0.875rem;font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $m->ten_tep }}">{{ $m->ten_tep }}</div>
                    <div class="text-xs text-muted" style="margin-top:2px;">{{ $m->loaiTepLabel }}</div>
                    <div style="margin-top:12px;display:flex;justify-content:flex-end;">
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
        <div class="media-pagination">{{ $media->links() }}</div>
        @endif
        @else
        <div style="text-align:center;padding:var(--space-3xl);color:var(--text-muted);">
            <i class="bi bi-images" style="font-size:48px;display:block;margin-bottom:12px;opacity:0.3;"></i>
            <p>Chưa có file nào trong thư viện</p>
        </div>
        @endif
    </div>
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="modal-layer">
    <div class="modal-card">
        <header>
            <h3 style="font-size:1rem;font-weight:600;">Upload file</h3>
            <button onclick="document.getElementById('uploadModal').classList.remove('show')" style="background:none;border:none;font-size:18px;cursor:pointer;color:var(--text-light);">&times;</button>
        </header>
        <form method="POST" action="{{ route('admin.media.upload') }}" enctype="multipart/form-data" class="body">
            @csrf
            <div class="form-group">
                <label class="form-label">Chọn file *</label>
                <input type="file" name="file" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Loại file</label>
                <select name="loai_tep" class="form-control">
                    <option value="">Tự động nhận diện</option>
                    <option value="hinh_anh">Hình ảnh</option>
                    <option value="video">Video</option>
                    <option value="tai_lieu">Tài liệu</option>
                    <option value="khac">Khác</option>
                </select>
                <div class="text-xs text-muted" style="margin-top:6px;">Nếu để trống, hệ thống sẽ tự phân loại theo định dạng tệp.</div>
            </div>
            <div class="form-group">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:0.875rem;">
                    <input type="checkbox" name="la_cong_khai" value="1"> Công khai
                </label>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:var(--space-md);">
                <button type="button" onclick="document.getElementById('uploadModal').classList.remove('show')" class="btn btn-secondary">Hủy</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Upload</button>
            </div>
        </form>
    </div>
</div>
@endsection
