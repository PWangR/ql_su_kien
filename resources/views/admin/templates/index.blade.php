@extends('admin.layout')

@section('title', 'Template bài đăng')
@section('page-title', 'Template bài đăng')

@section('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar.ql-snow { border: 1px solid var(--border); border-bottom: none; background: var(--bg-alt); font-family: var(--font-sans); }
    .ql-container.ql-snow { border: 1px solid var(--border); font-family: var(--font-sans); font-size: 0.875rem; background: var(--card); }
    .ql-editor { min-height: 250px; }
</style>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:1fr 450px;gap:var(--space-lg);align-items:start;">

<!-- List -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-file-earmark-text"></i> Danh sách template</div>
    </div>
    <div class="card-body" style="padding:0;">
        @forelse($templates as $t)
        <div style="padding:16px 20px;border-bottom:1px solid var(--border-light);" id="template-{{ $t->ma_mau }}">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;">
                <div style="flex:1;">
                    <div style="font-weight:600;font-size:1rem;margin-bottom:4px;">{{ $t->ten_mau }}</div>
                    <div style="font-size:0.75rem;color:var(--text-muted);margin-bottom:8px;display:flex;gap:12px;flex-wrap:wrap;">
                        <span><i class="bi bi-tag"></i> {{ $t->loaiSuKien->ten_loai ?? 'Tất cả loại' }}</span>
                        <span><i class="bi bi-person"></i> {{ $t->nguoiTao->ho_ten ?? '—' }}</span>
                        @if($t->dia_diem)<span><i class="bi bi-geo-alt"></i> {{ $t->dia_diem }}</span>@endif
                        @if($t->diem_cong > 0)<span><i class="bi bi-star"></i> +{{ $t->diem_cong }} điểm</span>@endif
                        @if($t->so_luong_toi_da > 0)<span><i class="bi bi-people"></i> Tối đa: {{ $t->so_luong_toi_da }}</span>@endif
                        @if($t->anh_su_kien)<span><i class="bi bi-image" style="color:var(--success)"></i> Có thẻ ảnh</span>@endif
                    </div>
                    <div style="font-size:0.875rem;color:var(--text-light);background:var(--bg-alt);border:1px solid var(--border-light);padding:10px;max-height:80px;overflow:hidden;line-height:1.5;">
                        {{ Str::limit(strip_tags($t->noi_dung), 120) }}
                    </div>
                </div>
                <div class="btn-group" style="flex-direction:column;flex-shrink:0;">
                    <button class="btn btn-secondary btn-sm" onclick="editTemplate({{ $t->ma_mau }}, `{{ addslashes($t->ten_mau) }}`, `{{ addslashes($t->noi_dung) }}`, '{{ $t->ma_loai_su_kien }}', `{{ addslashes($t->dia_diem) }}`, '{{ $t->so_luong_toi_da }}', '{{ $t->diem_cong }}', `{{ json_encode($t->bo_cuc ?? ['banner','header','info','description','gallery']) }}`)">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <form method="POST" action="{{ route('admin.templates.destroy', $t->ma_mau) }}" onsubmit="return confirm('Xóa template này?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:var(--space-3xl);color:var(--text-muted);">
            <i class="bi bi-file-earmark-plus" style="font-size:36px;display:block;margin-bottom:8px;opacity:0.3;"></i>
            Chưa có template nào
        </div>
        @endforelse
    </div>
    @if($templates->hasPages())
    <div style="padding:var(--space-md) 20px;border-top:1px solid var(--border-light);">{{ $templates->links() }}</div>
    @endif
</div>

<!-- Form -->
<div class="card" style="position:sticky;top:20px;">
    <div class="card-header">
        <div class="card-title" id="formTitle"><i class="bi bi-plus-circle"></i> Tạo template mới</div>
    </div>
    <div class="card-body">
        <form method="POST" id="templateForm" action="{{ route('admin.templates.store') }}" enctype="multipart/form-data">
            @csrf
            <div id="methodField"></div>
            
            <div class="form-group">
                <label class="form-label">Tên template *</label>
                <input type="text" name="ten_mau" id="inputTenMau" class="form-control" required placeholder="VD: Mẫu sự kiện chuyên đề...">
            </div>
            
            <div class="form-group">
                <label class="form-label">Loại sự kiện</label>
                <select name="ma_loai_su_kien" id="inputLoai" class="form-control">
                    <option value="">-- Áp dụng cho mọi loại --</option>
                    @foreach($loaiSuKien as $l)
                    <option value="{{ $l->ma_loai_su_kien }}">{{ $l->ten_loai }}</option>
                    @endforeach
                </select>
            </div>

            <div style="background:var(--bg-alt);border:1px solid var(--border);padding:var(--space-md);margin-bottom:var(--space-lg);">
                <div class="form-group">
                    <label class="form-label text-sm fw-bold">Cấu trúc hiển thị</label>
                    <div style="display:flex;flex-wrap:wrap;gap:12px;">
                        @php
                            $components = [
                                'banner' => 'Ảnh bìa',
                                'header' => 'Tiêu đề',
                                'info' => 'Thông tin',
                                'description' => 'Mô tả',
                                'gallery' => 'Thư viện'
                            ];
                        @endphp
                        @foreach($components as $key => $label)
                        <label style="display:flex;align-items:center;gap:4px;font-size:0.8125rem;cursor:pointer;">
                            <input class="component-checkbox" type="checkbox" name="bo_cuc[]" value="{{ $key }}" id="chk-{{ $key }}" checked>
                            {{ $label }}
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="input-grid form-group mb-0">
                    <div>
                        <label class="form-label text-xs">Địa điểm mặc định</label>
                        <input type="text" name="dia_diem" id="inputDiaDiem" class="form-control" placeholder="Địa điểm...">
                    </div>
                    <div>
                        <label class="form-label text-xs">Tối đa</label>
                        <input type="number" name="so_luong_toi_da" id="inputSoLuong" class="form-control" placeholder="0" min="0">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Nội dung mô tả *</label>
                <input type="hidden" name="noi_dung" id="inputNoiDung" required>
                <div id="editor-container"></div>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;"><i class="bi bi-save"></i> Lưu Template</button>
                <button type="button" onclick="resetForm()" class="btn btn-outline">Làm lại</button>
            </div>
        </form>
    </div>
</div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
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

quill.on('text-change', function() {
    let html = quill.root.innerHTML;
    if (html === '<p><br></p>') html = '';
    document.getElementById('inputNoiDung').value = html;
});

function editTemplate(id, ten, nd, loai, diadiem, soluong, diem, layoutJson) {
    document.getElementById('formTitle').innerHTML = '<i class="bi bi-pencil"></i> Chỉnh sửa template';
    document.getElementById('templateForm').action = '/admin/templates/' + id;
    document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    
    document.getElementById('inputTenMau').value = ten;
    quill.root.innerHTML = nd;
    document.getElementById('inputNoiDung').value = nd;
    document.getElementById('inputLoai').value = loai || '';
    
    document.getElementById('inputDiaDiem').value = diadiem || '';
    document.getElementById('inputSoLuong').value = soluong || 0;

    const layout = JSON.parse(layoutJson || '[]');
    document.querySelectorAll('.component-checkbox').forEach(chk => {
        chk.checked = layout.includes(chk.value);
    });
    
    window.scrollTo({top: 0, behavior: 'smooth'});
}

function resetForm() {
    document.getElementById('formTitle').innerHTML = '<i class="bi bi-plus-circle"></i> Tạo template mới';
    document.getElementById('templateForm').action = '{{ route("admin.templates.store") }}';
    document.getElementById('methodField').innerHTML = '';
    document.getElementById('templateForm').reset();
    quill.root.innerHTML = '';
    document.getElementById('inputNoiDung').value = '';
    
    document.querySelectorAll('.component-checkbox').forEach(chk => chk.checked = true);
}
</script>
@endsection
