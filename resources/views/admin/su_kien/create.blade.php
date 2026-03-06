@extends('admin.layout')

@section('title', 'Tạo sự kiện')
@section('page-title', 'Tạo sự kiện mới')

@section('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    :root {
        --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    .form-section-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--secondary);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .card { border: none; box-shadow: var(--card-shadow); border-radius: 12px; }
    .card-header { background: #fff; border-bottom: 1px solid #f1f5f9; padding: 20px; border-radius: 12px 12px 0 0 !p; }
    
    .ql-toolbar.ql-snow { border-radius: 8px 8px 0 0; background: #f8fafc; border-color: var(--border); }
    .ql-container.ql-snow { border-radius: 0 0 8px 8px; border-color: var(--border); font-family: 'Inter', sans-serif; font-size: 14px; }
    .ql-editor { min-height: 350px; }

    .sticky-actions {
        position: sticky;
        top: 20px;
        z-index: 100;
    }
    
    .image-upload-wrapper {
        border: 2px dashed var(--border);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: all 0.2s;
        cursor: pointer;
        background: #f8fafc;
    }
    .image-upload-wrapper:hover {
        border-color: var(--primary);
        background: #eff6ff;
    }
    
    .gallery-preview {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 12px;
        margin-top: 15px;
    }
    .gallery-item {
        position: relative;
        aspect-ratio: 1;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--border);
    }
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .gallery-item .remove-btn {
        position: absolute;
        top: 4px;
        right: 4px;
        background: rgba(239, 68, 68, 0.9);
        color: white;
        border: none;
        border-radius: 4px;
        padding: 2px 6px;
        font-size: 10px;
        cursor: pointer;
    }
    
    .badge-input {
        background: #eff6ff;
        color: var(--primary);
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 12px;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.su-kien.store') }}" enctype="multipart/form-data" id="mainForm">
    @csrf
    <div class="row g-4">
        <!-- Cột trái: Nội dung chính -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body p-4">
                    <div class="form-section-title">
                        <i class="bi bi-info-circle-fill text-primary"></i> Nội dung sự kiện
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Tên sự kiện <span class="text-danger">*</span></label>
                        <input type="text" name="ten_su_kien" class="form-control form-control-lg @error('ten_su_kien') is-invalid @enderror"
                            placeholder="Nhập tiêu đề sự kiện hấp dẫn..." value="{{ old('ten_su_kien') }}" required>
                        @error('ten_su_kien')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-bold mb-0">Mô tả chi tiết</label>
                            <button type="button" class="btn btn-sm btn-outline-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#templateModal">
                                <i class="bi bi-magic"></i> Sử dụng mẫu bài viết
                            </button>
                        </div>
                        <input type="hidden" name="mo_ta_chi_tiet" id="mo_ta_chi_tiet" value="{{ old('mo_ta_chi_tiet') }}">
                        <div id="editor-container" style="background:#fff;">{!! old('mo_ta_chi_tiet') !!}</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-4">
                    <div class="form-section-title">
                        <i class="bi bi-images text-success"></i> Hình ảnh sự kiện
                    </div>
                    
                    <p class="text-muted small mb-4">Ảnh đầu tiên sẽ được dùng làm ảnh bìa chính (Thumbnail).</p>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase">Ảnh bìa chính</label>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="image-upload-wrapper mb-3" onclick="document.getElementById('imgInput').click()">
                                    <i class="bi bi-cloud-arrow-up fs-2 text-muted"></i>
                                    <p class="mb-0 small fw-600">Click để chọn ảnh bìa</p>
                                    <input type="file" name="anh_su_kien" class="d-none" accept="image/*" id="imgInput">
                                </div>
                                <div class="text-center">
                                    <span class="text-muted small">hoặc</span><br>
                                    <button type="button" class="btn btn-sm btn-link" data-bs-toggle="modal" data-bs-target="#mediaModal">Chọn từ thư viện</button>
                                </div>
                                <input type="hidden" name="media_duong_dan" id="media_duong_dan">
                            </div>
                            <div class="col-md-7">
                                <div id="imgPreview" class="text-center" style="display:none;">
                                    <img id="previewImg" src="" style="width:100%; max-height:180px; object-fit:cover; border-radius:12px; border:1px solid var(--border);">
                                    <span id="media_selected_text" class="d-block mt-2 small text-success fw-bold"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div>
                        <label class="form-label fw-bold small text-uppercase">Bộ sưu tập ảnh phụ (Gallery)</label>
                        <div class="image-upload-wrapper" onclick="document.getElementById('galleryInput').click()">
                            <i class="bi bi-plus-circle fs-3 text-muted"></i>
                            <p class="mb-0 small fw-600">Tải lên nhiều ảnh phụ</p>
                            <input type="file" name="gallery_files[]" class="d-none" accept="image/*" id="galleryInput" multiple>
                        </div>
                        <div id="gallery-preview" class="gallery-preview">
                            <!-- JS prepend items here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cột phải: Cấu hình -->
        <div class="col-lg-4">
            <div class="sticky-actions">
                <div class="card mb-4 py-2 px-3 bg-white d-flex flex-row align-items-center justify-content-between shadow-sm border-0">
                    <span class="fw-bold text-secondary">Trạng thái: <span class="badge bg-primary">Bản nháp</span></span>
                    <div class="d-flex gap-2">
                        <button type="submit" form="mainForm" class="btn btn-primary px-4"><i class="bi bi-check2-all"></i> Lưu</button>
                        <a href="{{ route('admin.su-kien.index') }}" class="btn btn-light border"><i class="bi bi-x"></i></a>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body p-4">
                        <div class="form-section-title mb-3">
                            <i class="bi bi-gear-fill text-secondary"></i> Cấu hình chung
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Loại sự kiện <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="ma_loai_su_kien" id="ma_loai_su_kien" class="form-select @error('ma_loai_su_kien') is-invalid @enderror" required>
                                    <option value="">-- Chọn loại --</option>
                                    @foreach($loai as $l)
                                    <option value="{{ $l->ma_loai_su_kien }}" {{ old('ma_loai_su_kien') == $l->ma_loai_su_kien ? 'selected' : '' }}>
                                        {{ $l->ten_loai }}
                                    </option>
                                    @endforeach
                                </select>
                                <button class="btn btn-outline-success" type="button" data-bs-toggle="modal" data-bs-target="#addLoaiModal"><i class="bi bi-plus"></i></button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Địa điểm</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" name="dia_diem" class="form-control border-start-0" 
                                    placeholder="Hội trường, phòng..." value="{{ old('dia_diem') }}">
                            </div>
                            <div id="overlap-alert" style="display:none;" class="alert alert-danger p-2 mt-2 mb-0 small border-0 shadow-sm align-items-center">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                                <span id="overlap-msg"></span>
                            </div>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-bold small">Tối đa người</label>
                                <input type="number" name="so_luong_toi_da" class="form-control" 
                                    placeholder="∞" min="0" value="{{ old('so_luong_toi_da', 0) }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold small">Điểm cộng</label>
                                <input type="number" name="diem_cong" class="form-control" 
                                    placeholder="0" min="0" value="{{ old('diem_cong', 0) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-4">
                        <div class="form-section-title mb-3">
                            <i class="bi bi-clock-fill text-warning"></i> Lịch diễn ra
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Thời gian bắt đầu <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="thoi_gian_bat_dau" 
                                class="form-control @error('thoi_gian_bat_dau') is-invalid @enderror" 
                                value="{{ old('thoi_gian_bat_dau') }}" required>
                        </div>
                        
                        <div>
                            <label class="form-label fw-bold small">Thời gian kết thúc <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="thoi_gian_ket_thuc" 
                                class="form-control @error('thoi_gian_ket_thuc') is-invalid @enderror" 
                                value="{{ old('thoi_gian_ket_thuc') }}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@include('admin.su_kien._modals') <!-- Tách các modal ra partial cho gọn -->

@endsection

@section('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Init Quill
var quill = new Quill('#editor-container', {
    theme: 'snow',
    placeholder: 'Viết nội dung thu hút người tham gia...',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike', 'blockquote'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'color': [] }, { 'background': [] }],
            ['link'],
            ['clean']
        ]
    }
});

quill.on('text-change', function() {
    let html = quill.root.innerHTML;
    document.getElementById('mo_ta_chi_tiet').value = (html === '<p><br></p>') ? '' : html;
});

// Thumbnail Preview
document.getElementById('imgInput').addEventListener('change', function() {
    document.getElementById('media_duong_dan').value = '';
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imgPreview').style.display = 'block';
            document.getElementById('media_selected_text').innerText = 'Dùng ảnh tải lên';
        };
        reader.readAsDataURL(file);
    }
});

// Gallery Preview
const galleryInput = document.getElementById('galleryInput');
const galleryContainer = document.getElementById('gallery-preview');

galleryInput.addEventListener('change', function() {
    // galleryContainer.innerHTML = ''; // Nếu muốn xóa cũ
    Array.from(this.files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'gallery-item shadow-sm';
            div.innerHTML = `
                <img src="${e.target.result}">
                <button type="button" class="remove-btn" onclick="this.parentElement.remove()">Xóa</button>
            `;
            galleryContainer.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
});

// Các hàm bổ trợ
function selectTemplate(content, diaDiem, soLuong, diemCong, anhSuKien) {
    quill.root.innerHTML = content;
    document.getElementById('mo_ta_chi_tiet').value = content;
    if (diaDiem) document.querySelector('input[name="dia_diem"]').value = diaDiem;
    if (soLuong > 0) document.querySelector('input[name="so_luong_toi_da"]').value = soLuong;
    if (diemCong > 0) document.querySelector('input[name="diem_cong"]').value = diemCong;
    
    if (anhSuKien) {
        document.getElementById('imgInput').value = '';
        document.getElementById('media_duong_dan').value = anhSuKien;
        let imgUrl = anhSuKien.startsWith('http') ? anhSuKien : "{{ asset('storage') }}/" + anhSuKien;
        document.getElementById('previewImg').src = imgUrl;
        document.getElementById('imgPreview').style.display = 'block';
        document.getElementById('media_selected_text').innerText = 'Auto-fill từ mẫu';
    }
    bootstrap.Modal.getInstance(document.getElementById('templateModal')).hide();
}

function selectMedia(path, element) {
    document.querySelectorAll('.media-item').forEach(el => el.style.borderColor = 'transparent');
    element.style.borderColor = 'var(--primary)';
    document.getElementById('imgInput').value = '';
    document.getElementById('media_duong_dan').value = path;
    document.getElementById('previewImg').src = "{{ asset('storage') }}/" + path;
    document.getElementById('imgPreview').style.display = 'block';
    document.getElementById('media_selected_text').innerText = 'Chọn từ thư viện';
    bootstrap.Modal.getInstance(document.getElementById('mediaModal')).hide();
}

// Logic check trùng lịch (debounce)
let timeoutOverlap;
const inputDiaDiem = document.querySelector('input[name="dia_diem"]');
const inputBatDau = document.querySelector('input[name="thoi_gian_bat_dau"]');
const inputKetThuc = document.querySelector('input[name="thoi_gian_ket_thuc"]');

function checkOverlap() {
    const dia_diem = inputDiaDiem.value.trim();
    const t_start = inputBatDau.value;
    const t_end = inputKetThuc.value;
    if (!dia_diem || !t_start || !t_end) return;

    fetch('{{ route("admin.su-kien.kiem-tra-trung-lich") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ dia_diem, thoi_gian_bat_dau: t_start, thoi_gian_ket_thuc: t_end })
    })
    .then(res => res.json())
    .then(data => {
        const alert = document.getElementById('overlap-alert');
        if (data.trung) {
            document.getElementById('overlap-msg').innerText = data.thong_bao;
            alert.style.display = 'flex';
        } else {
            alert.style.display = 'none';
        }
    });
}
inputDiaDiem.addEventListener('input', () => { clearTimeout(timeoutOverlap); timeoutOverlap = setTimeout(checkOverlap, 500); });
inputBatDau.addEventListener('change', checkOverlap);
inputKetThuc.addEventListener('change', checkOverlap);

function saveLoaiSuKien() {
    const tenLoai = document.getElementById('new_loai_ten').value;
    if(!tenLoai) return;
    fetch('{{ route("admin.su-kien.store-loai") }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: JSON.stringify({ ten_loai: tenLoai, mo_ta: document.getElementById('new_loai_mota').value })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            const sel = document.getElementById('ma_loai_su_kien');
            const opt = new Option(data.loai.ten_loai, data.loai.ma_loai_su_kien, true, true);
            sel.add(opt);
            bootstrap.Modal.getInstance(document.getElementById('addLoaiModal')).hide();
        }
    });
}
</script>
@endsection
