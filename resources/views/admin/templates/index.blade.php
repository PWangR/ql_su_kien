@extends('admin.layout')

@section('title', 'Template bài đăng')
@section('page-title', 'Template bài đăng')

@section('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar.ql-snow { border-radius: 8px 8px 0 0; background: #f8fafc; border-color: var(--border); }
    .ql-container.ql-snow { border-radius: 0 0 8px 8px; border-color: var(--border); font-family: 'Inter', sans-serif; font-size: 14px; }
    .ql-editor { min-height: 200px; }
</style>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:1fr 450px;gap:20px;align-items:start;">

<!-- List -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-file-earmark-text-fill" style="color:var(--primary)"></i> Danh sách template</div>
    </div>
    <div class="card-body" style="padding:0;">
        @forelse($templates as $t)
        <div style="padding:16px 20px;border-bottom:1px solid var(--border);" id="template-{{ $t->ma_mau }}">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;">
                <div style="flex:1;">
                    <div style="font-weight:700;margin-bottom:4px;">{{ $t->ten_mau }}</div>
                    <div style="font-size:12px;color:var(--text-light);margin-bottom:8px;display:flex;gap:10px;flex-wrap:wrap;">
                        <span><i class="bi bi-tag"></i> {{ $t->loaiSuKien->ten_loai ?? 'Tất cả loại' }}</span>
                        <span><i class="bi bi-person"></i> {{ $t->nguoiTao->ho_ten ?? '—' }}</span>
                        @if($t->dia_diem)<span><i class="bi bi-geo-alt"></i> {{ $t->dia_diem }}</span>@endif
                        @if($t->diem_cong > 0)<span><i class="bi bi-star"></i> +{{ $t->diem_cong }} điểm</span>@endif
                        @if($t->so_luong_toi_da > 0)<span><i class="bi bi-people"></i> Tối đa: {{ $t->so_luong_toi_da }}</span>@endif
                        @if($t->anh_su_kien)<span><i class="bi bi-image" style="color:var(--success)"></i> Có ảnh bìa</span>@endif
                    </div>
                    <div style="font-size:13px;color:var(--text);background:var(--bg);border-radius:8px;padding:10px;max-height:80px;overflow:hidden;">
                        {{ Str::limit(strip_tags($t->noi_dung), 120) }}
                    </div>
                </div>
                <div style="display:flex;flex-direction:column;gap:6px;flex-shrink:0;">
                    <button class="btn btn-warning btn-sm" onclick="editTemplate({{ $t->ma_mau }}, `{{ addslashes($t->ten_mau) }}`, `{{ addslashes($t->noi_dung) }}`, '{{ $t->ma_loai_su_kien }}', `{{ addslashes($t->dia_diem) }}`, '{{ $t->so_luong_toi_da }}', '{{ $t->diem_cong }}')">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <form method="POST" action="{{ route('admin.templates.destroy', $t->ma_mau) }}" onsubmit="return confirm('Xóa template?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:40px;color:var(--text-light);">
            <i class="bi bi-file-earmark-plus" style="font-size:36px;display:block;margin-bottom:8px;opacity:0.3;"></i>
            Chưa có template nào
        </div>
        @endforelse
    </div>
    @if($templates->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);">{{ $templates->links() }}</div>
    @endif
</div>

<!-- Form thêm/sửa -->
<div class="card">
    <div class="card-header">
        <div class="card-title" id="formTitle"><i class="bi bi-plus-circle" style="color:var(--success)"></i> Tạo template mới</div>
    </div>
    <div class="card-body">
        <form method="POST" id="templateForm" action="{{ route('admin.templates.store') }}" enctype="multipart/form-data">
            @csrf
            <div id="methodField"></div>
            
            <div class="mb-3">
                <label class="form-label">Tên template <span style="color:var(--danger)">*</span></label>
                <input type="text" name="ten_mau" id="inputTenMau" class="form-control" required placeholder="VD: Mẫu Workshop IT">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Loại sự kiện (Tự động điền nếu loại khớp)</label>
                <select name="ma_loai_su_kien" id="inputLoai" class="form-control">
                    <option value="">-- Áp dụng cho mọi loại --</option>
                    @foreach($loaiSuKien as $l)
                    <option value="{{ $l->ma_loai_su_kien }}">{{ $l->ten_loai }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-3" style="background:#f8fafc;padding:12px;border-radius:8px;border:1px solid var(--border);">
                <p style="font-weight:600;font-size:13px;margin-bottom:10px;color:var(--primary);">Cấu hình tự động điền (tùy chọn)</p>
                
                <div class="mb-2">
                    <label class="form-label" style="font-size:13px;">Địa điểm mặc định</label>
                    <input type="text" name="dia_diem" id="inputDiaDiem" class="form-control form-control-sm" placeholder="VD: Hội trường A">
                </div>
                
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label class="form-label" style="font-size:13px;">Điểm cộng mặc định</label>
                        <input type="number" name="diem_cong" id="inputDiem" class="form-control form-control-sm" placeholder="0" min="0">
                    </div>
                    <div class="col-6">
                        <label class="form-label" style="font-size:13px;">Giới hạn người (0 = ∞)</label>
                        <input type="number" name="so_luong_toi_da" id="inputSoLuong" class="form-control form-control-sm" placeholder="0" min="0">
                    </div>
                </div>
                
                <div class="mb-0">
                    <label class="form-label" style="font-size:13px;">Ảnh nền mặc định (tải lên file)</label>
                    <input type="file" name="anh_su_kien" class="form-control form-control-sm" accept="image/*">
                    <small style="font-size:11px;color:var(--text-light);">Bỏ trống nếu không muốn đổi ảnh bìa khi chọn mẫu này.</small>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Nội dung mô tả <span style="color:var(--danger)">*</span></label>
                <input type="hidden" name="noi_dung" id="inputNoiDung" required>
                <div id="editor-container" style="background:#fff;"></div>
            </div>
            
            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check"></i> Lưu Template</button>
                <button type="button" onclick="resetForm()" class="btn btn-secondary">Đặt lại form</button>
            </div>
        </form>
    </div>
</div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
// Khởi tạo Quill Editor
var quill = new Quill('#editor-container', {
    theme: 'snow',
    placeholder: 'Nhập nội dung template...',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            ['link', 'clean']
        ]
    }
});

// Update hidden input liên tục
quill.on('text-change', function() {
    let html = quill.root.innerHTML;
    if (html === '<p><br></p>') html = '';
    document.getElementById('inputNoiDung').value = html;
});

function editTemplate(id, ten, nd, loai, diadiem, soluong, diem) {
    document.getElementById('formTitle').innerHTML = '<i class="bi bi-pencil" style="color:var(--warning)"></i> Chỉnh sửa template';
    document.getElementById('templateForm').action = '/admin/templates/' + id;
    document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    
    document.getElementById('inputTenMau').value = ten;
    quill.root.innerHTML = nd;
    document.getElementById('inputNoiDung').value = nd;
    document.getElementById('inputLoai').value = loai || '';
    
    document.getElementById('inputDiaDiem').value = diadiem || '';
    document.getElementById('inputSoLuong').value = soluong || 0;
    document.getElementById('inputDiem').value = diem || 0;
    
    window.scrollTo({top: 0, behavior: 'smooth'});
}

function resetForm() {
    document.getElementById('formTitle').innerHTML = '<i class="bi bi-plus-circle" style="color:var(--success)"></i> Tạo template mới';
    document.getElementById('templateForm').action = '{{ route("admin.templates.store") }}';
    document.getElementById('methodField').innerHTML = '';
    document.getElementById('templateForm').reset();
    quill.root.innerHTML = '';
    document.getElementById('inputNoiDung').value = '';
}
</script>
@endsection
