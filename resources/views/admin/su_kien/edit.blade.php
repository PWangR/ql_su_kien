@extends('admin.layout')

@section('title', 'Sửa sự kiện')
@section('page-title', 'Chỉnh sửa sự kiện')

@section('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        --glass-bg: rgba(255, 255, 255, 0.85);
        --glass-border: rgba(255, 255, 255, 0.4);
        --premium-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
    }
    
    body { background-color: #f1f5f9; }

    .premium-card {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        box-shadow: var(--premium-shadow);
        transition: transform 0.3s ease;
    }
    
    .section-title {
        font-size: 1.1rem;
        font-weight: 800;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    /* Sortable Layout Styles */
    #layout-sortable {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .sortable-item {
        background: #fff;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        cursor: grab;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: all 0.2s;
    }
    .sortable-item:active { cursor: grabbing; }
    .sortable-item.ghost { opacity: 0.4; background: #eff6ff; border: 1px dashed #3b82f6; }
    .drag-handle { color: #cbd5e1; font-size: 1.2rem; }
    
    .component-status {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #cbd5e1;
    }
    .sortable-item.active .component-status { background: #22c55e; box-shadow: 0 0 8px #22c55e; }

    /* Action Toolbar */
    .action-toolbar {
        position: sticky;
        top: 20px;
        z-index: 1000;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(12px);
        border-radius: 16px;
        border: 1px solid var(--glass-border);
        padding: 12px 24px;
        margin-bottom: 30px;
        box-shadow: var(--premium-shadow);
    }

    /* Custom Input Styles */
    .form-control, .form-select {
        border-radius: 12px;
        padding: 10px 16px;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
    
    .image-preview-container {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    
    .ql-toolbar.ql-snow { border-top-left-radius: 12px; border-top-right-radius: 12px; border-color: #e2e8f0; }
    .ql-container.ql-snow { border-bottom-left-radius: 12px; border-bottom-right-radius: 12px; border-color: #e2e8f0; font-size: 15px; }

    .gallery-item {
        position: relative;
        width: 80px;
        height: 80px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }
    .gallery-item img { width: 100%; height: 100%; object-fit: cover; }
    .remove-btn {
        position: absolute;
        top: 2px;
        right: 2px;
        background: rgba(239, 68, 68, 0.9);
        color: white;
        border: none;
        border-radius: 4px;
        padding: 0 5px;
        font-size: 10px;
    }
</style>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.su-kien.update', $suKien->ma_su_kien) }}" enctype="multipart/form-data" id="mainForm">
    @csrf @method('PUT')
    
    <!-- Premium Action Toolbar -->
    <div class="action-toolbar d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary text-white rounded-3 p-2 shadow-sm">
                <i class="bi bi-pencil-fill fs-5"></i>
            </div>
            <div>
                <h5 class="mb-0 fw-bold">Chỉnh sửa sự kiện #{{ $suKien->ma_su_kien }}</h5>
                <p class="text-muted small mb-0">Cập nhật nội dung và điều chỉnh bố cục hiển thị</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.su-kien.index') }}" class="btn btn-light px-4 fw-600 rounded-pill border">Quay lại</a>
            <button type="submit" form="mainForm" class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm">Cập nhật thay đổi</button>
        </div>
    </div>

    <div class="row g-4">
        <!-- Cột trái: Nội dung chính -->
        <div class="col-lg-8">
            <div class="premium-card p-4 mb-4">
                <div class="section-title">
                    <i class="bi bi-info-circle-fill"></i> Thông tin cơ bản
                </div>
                
                <div class="row g-3">
                    <div class="col-12 mb-2">
                        <label class="form-label fw-800 small text-uppercase text-muted">Tên sự kiện</label>
                        <input type="text" name="ten_su_kien" class="form-control form-control-lg fw-bold border-0 bg-light"
                            placeholder="Tiêu đề sự kiện..." value="{{ old('ten_su_kien', $suKien->ten_su_kien) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-800 small text-uppercase text-muted">Mẫu bài đăng (Template)</label>
                        <select class="form-select border-0 bg-light" id="templateSelect" onchange="applyTemplateLayout(this)">
                            <option value="">-- Giữ nguyên hiện tại --</option>
                            @foreach($templates as $tpl)
                            <option value="{{ $tpl->ma_mau }}" 
                                data-layout="{{ json_encode($tpl->bo_cuc ?? ['banner', 'header', 'info', 'description', 'gallery']) }}"
                                data-location="{{ e($tpl->dia_diem) }}"
                                data-max="{{ $tpl->so_luong_toi_da }}"
                                data-points="{{ $tpl->diem_cong }}"
                                data-img="{{ $tpl->anh_su_kien }}">
                                {{ $tpl->ten_mau }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-800 small text-uppercase text-muted">Loại sự kiện</label>
                        <div class="input-group">
                            <select name="ma_loai_su_kien" id="ma_loai_su_kien" class="form-select border-0 bg-light" required>
                                @foreach($loai as $l)
                                <option value="{{ $l->ma_loai_su_kien }}" {{ old('ma_loai_su_kien', $suKien->ma_loai_su_kien) == $l->ma_loai_su_kien ? 'selected' : '' }}>
                                    {{ $l->ten_loai }}
                                </option>
                                @endforeach
                            </select>
                            <button class="btn btn-light border-0 bg-light text-primary" type="button" data-bs-toggle="modal" data-bs-target="#addLoaiModal">
                                <i class="bi bi-plus-circle"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nội dung chi tiết -->
            <div class="premium-card p-4 mb-4">
                <div class="section-title">
                    <i class="bi bi-card-text"></i> Nội dung bài viết
                </div>
                <input type="hidden" name="mo_ta_chi_tiet" id="mo_ta_chi_tiet" value="{{ old('mo_ta_chi_tiet', $suKien->mo_ta_chi_tiet) }}">
                <div id="editor-container" class="bg-white" style="height: 400px;">{!! old('mo_ta_chi_tiet', $suKien->mo_ta_chi_tiet) !!}</div>
            </div>

            <!-- Media Section -->
            <div class="premium-card p-4">
                <div class="section-title">
                    <i class="bi bi-images"></i> Media & Hình ảnh
                </div>
                
                <div class="row g-4">
                    <!-- Ảnh bìa chính -->
                    <div class="col-md-5">
                        <label class="form-label fw-800 small text-uppercase text-muted">Ảnh bìa hiện tại</label>
                        <div class="image-preview-container mb-3" id="imgPreview">
                            @php $mainImg = $suKien->anh_su_kien ? asset('storage/'.$suKien->anh_su_kien) : ''; @endphp
                            <img id="previewImg" src="{{ $mainImg }}" style="width:100%; height:100%; object-fit:cover; border-radius:12px; display: {{ $mainImg ? 'block' : 'none' }}">
                            <div id="imgPreviewPlaceholder" class="text-center text-muted" style="display: {{ $mainImg ? 'none' : 'block' }}">
                                <i class="bi bi-image fs-1 opacity-25"></i>
                                <p class="small mb-0">Chưa có ảnh</p>
                            </div>
                            <button type="button" id="btnDeletePreview" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-circle" 
                                style="display: {{ $mainImg ? 'block' : 'none' }}" onclick="clearPreview()">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm flex-grow-1 rounded-pill" onclick="document.getElementById('imgInput').click()">
                                <i class="bi bi-upload"></i> Thay ảnh
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm flex-grow-1 rounded-pill" onclick="toggleMediaLibrary()">
                                <i class="bi bi-folder2-open"></i> Thư viện
                            </button>
                        </div>
                        <input type="file" name="anh_su_kien" id="imgInput" class="d-none" accept="image/*">
                        <input type="hidden" name="media_duong_dan" id="media_duong_dan">
                    </div>

                    <!-- Gallery -->
                    <div class="col-md-7">
                        <label class="form-label fw-800 small text-uppercase text-muted">Bộ sưu tập ảnh phụ</label>
                        <div class="p-3 bg-light rounded-4 border mb-3" style="min-height: 150px;">
                            <div id="gallery-preview" class="d-flex flex-wrap gap-2">
                                @foreach($suKien->media->where('loai_tep', 'hinh_anh') as $img)
                                <div class="position-relative" id="media-item-{{ $img->ma_phuong_tien }}">
                                    <img src="{{ asset('storage/'.$img->duong_dan_tep) }}" style="width:70px; height:70px; object-fit:cover; border-radius:10px;">
                                    <button type="button" class="btn btn-danger btn-xs position-absolute top-0 end-0 rounded-circle" 
                                        style="padding:2px 5px; font-size:10px; transform:translate(30%, -30%)" onclick="removeGalleryImage({{ $img->ma_phuong_tien }})">×</button>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <button type="button" class="btn btn-light btn-sm w-100 rounded-pill border" onclick="document.getElementById('galleryInput').click()">
                            <i class="bi bi-plus-lg"></i> Thêm ảnh mới
                        </button>
                        <input type="file" name="gallery_files[]" id="galleryInput" class="d-none" multiple accept="image/*">
                    </div>
                </div>

                <!-- Inline Media Library -->
                <div id="media-library-inline" class="mt-4 p-3 bg-light rounded-4 border" style="display: none; max-height: 300px; overflow-y: auto;">
                    <h6 class="fw-bold mb-3 small text-uppercase">Chọn từ hệ thống</h6>
                    <div class="row g-2" id="media-list">
                        @foreach($mediaKho as $media)
                        <div class="col-3 col-md-2">
                            <div class="media-item-container cursor-pointer rounded-3 overflow-hidden border" 
                                onclick="selectMediaInline('{{ $media->duong_dan_tep }}', this)">
                                <img src="{{ asset('storage/' . $media->duong_dan_tep) }}" class="w-100" style="aspect-ratio:1; object-fit:cover;">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

            <!-- Hệ thống Bố cục (Drag & Drop) -->
            <div class="premium-card p-4 mb-4">
                <div class="section-title">
                    <i class="bi bi-stack"></i> Bố cục & Thứ tự hiển thị
                </div>
                
                <p class="text-muted small mb-4">Kéo thả để sắp xếp vị trí các thành phần bài đăng cho sự kiện này</p>
                
                <div id="layout-sortable">
                    @php
                        $allComponents = [
                            'banner' => ['label' => 'Ảnh bìa (Banner)', 'icon' => 'bi-image', 'desc' => 'Hiển thị ảnh chính ở đầu bài'],
                            'header' => ['label' => 'Tiêu đề & Loại', 'icon' => 'bi-type-h1', 'desc' => 'Tên sự kiện và phân loại gắn kèm'],
                            'info' => ['label' => 'Thông tin chi tiết', 'icon' => 'bi-calendar-event', 'desc' => 'Thời gian, địa điểm, điểm cộng'],
                            'description' => ['label' => 'Mô tả bài viết', 'icon' => 'bi-justify-left', 'desc' => 'Nội dung chi tiết từ trình soạn thảo'],
                            'gallery' => ['label' => 'Thư viện ảnh', 'icon' => 'bi-images', 'desc' => 'Tất cả các ảnh phụ đính kèm']
                        ];
                        
                        // Lấy bố cục đã lưu hoặc mặc định
                        $savedLayout = $suKien->bo_cuc ?? ['banner', 'header', 'info', 'description', 'gallery'];
                        
                        // Tạo danh sách đã sắp xếp. Những cái không có trong savedLayout (nếu có bổ sung sau này) sẽ đưa xuống cuối
                        $sortedKeys = array_unique(array_merge($savedLayout, array_keys($allComponents)));
                    @endphp
                    
                    @foreach($sortedKeys as $key)
                        @if(isset($allComponents[$key]))
                        @php $data = $allComponents[$key]; @endphp
                        <div class="sortable-item {{ in_array($key, $savedLayout) ? 'active' : '' }} shadow-sm" data-id="{{ $key }}" style="{{ in_array($key, $savedLayout) ? '' : 'opacity:0.6' }}">
                            <div class="drag-handle">
                                <i class="bi bi-grip-vertical"></i>
                            </div>
                            <div class="component-status" style="background: {{ in_array($key, $savedLayout) ? '#22c55e' : '#cbd5e1' }}"></div>
                            <div class="flex-grow-1">
                                <div class="fw-bold small">{{ $data['label'] }}</div>
                                <div class="text-muted" style="font-size: 11px;">{{ $data['desc'] }}</div>
                            </div>
                            <div class="form-check form-switch ms-3">
                                <input class="form-check-input component-checkbox" type="checkbox" name="bo_cuc[]" 
                                    value="{{ $key }}" id="chk-{{ $key }}" {{ in_array($key, $savedLayout) ? 'checked' : '' }} onchange="toggleComponentStatus(this)">
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
                <input type="hidden" name="bo_cuc_order" id="bo_cuc_order" value="{{ json_encode($savedLayout) }}">
            </div>

            <!-- Thời gian & Địa điểm (Premium Design) -->
            <div class="premium-card p-4 mb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-800 small text-uppercase text-muted">Bắt đầu</label>
                        <input type="datetime-local" name="thoi_gian_bat_dau" 
                            class="form-control border-0 bg-light" 
                            value="{{ old('thoi_gian_bat_dau', $suKien->thoi_gian_bat_dau?->format('Y-m-d\TH:i')) }}" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-800 small text-uppercase text-muted">Kết thúc</label>
                        <input type="datetime-local" name="thoi_gian_ket_thuc" 
                            class="form-control border-0 bg-light" 
                            value="{{ old('thoi_gian_ket_thuc', $suKien->thoi_gian_ket_thuc?->format('Y-m-d\TH:i')) }}" required>
                    </div>

                    <div class="col-12 mt-3">
                        <label class="form-label fw-800 small text-uppercase text-muted">Địa điểm tổ chức</label>
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-light text-muted"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" name="dia_diem" class="form-control border-0 bg-light" 
                                placeholder="Nhập địa điểm..." value="{{ old('dia_diem', $suKien->dia_diem) }}">
                        </div>
                        <div id="overlap-alert" style="display:none;" class="alert alert-danger p-2 mt-2 mb-0 small border-0 shadow-sm align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                            <span id="overlap-msg"></span>
                        </div>
                    </div>
                </div>
            </div>
        <!-- Cột phải: Sidebar -->
        <div class="col-lg-4">
            <div class="premium-card p-4 mb-4">
                <div class="section-title">
                    <i class="bi bi-gear-wide-connected"></i> Cấu hình hiện tại
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-800 small text-uppercase text-muted">Trạng thái</label>
                    <select name="trang_thai" class="form-select border-0 bg-light shadow-sm fw-bold">
                        <option value="sap_to_chuc"  {{ old('trang_thai', $suKien->trang_thai)=='sap_to_chuc'  ? 'selected':'' }}>Sắp tổ chức</option>
                        <option value="dang_dien_ra" {{ old('trang_thai', $suKien->trang_thai)=='dang_dien_ra' ? 'selected':'' }}>Đang diễn ra</option>
                        <option value="da_ket_thuc"  {{ old('trang_thai', $suKien->trang_thai)=='da_ket_thuc'  ? 'selected':'' }}>Đã kết thúc</option>
                        <option value="huy"          {{ old('trang_thai', $suKien->trang_thai)=='huy'          ? 'selected':'' }}>Đã hủy</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-800 small text-uppercase text-muted">Số người tối đa</label>
                    <div class="input-group bg-light rounded-pill px-3">
                        <span class="text-muted"><i class="bi bi-people"></i></span>
                        <input type="number" name="so_luong_toi_da" class="form-control border-0 bg-transparent shadow-none" 
                            placeholder="Vô hạn" min="0" value="{{ old('so_luong_toi_da', $suKien->so_luong_toi_da) }}">
                    </div>
                </div>

                <div class="mb-2">
                    <label class="form-label fw-800 small text-uppercase text-muted">Điểm rèn luyện cộng</label>
                    <div class="input-group bg-light rounded-pill px-3">
                        <span class="text-primary"><i class="bi bi-star-fill"></i></span>
                        <input type="number" name="diem_cong" class="form-control border-0 bg-transparent shadow-none fw-bold text-primary" 
                            placeholder="0" min="0" value="{{ old('diem_cong', $suKien->diem_cong) }}">
                    </div>
                </div>
            </div>

            <div class="premium-card p-4" style="background: linear-gradient(135deg, #475569 0%, #1e293b 100%);">
                <h6 class="text-white fw-bold mb-3"><i class="bi bi-info-circle me-2"></i> Thông tin hệ thống</h6>
                <div class="d-flex flex-column gap-2 text-white bg-opacity-75 small text-opacity-75 font-monospace">
                    <div>Ngày tạo: {{ $suKien->created_at->format('d/m/Y H:i') }}</div>
                    <div>Cập nhật: {{ $suKien->updated_at->format('d/m/Y H:i') }}</div>
                    <div>Đã có: {{ $suKien->dang_ky_count ?? 0 }} lượt đăng ký</div>
                </div>
            </div>
        </div>
    </div>
</form>

@include('admin.su_kien._modals')

@endsection

@section('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    // 1. Editor
    const quill = new Quill('#editor-container', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'blockquote', 'code-block'],
                ['clean']
            ]
        }
    });
    quill.on('text-change', () => {
        const html = quill.root.innerHTML;
        document.getElementById('mo_ta_chi_tiet').value = (html === '<p><br></p>') ? '' : html;
    });

    // 2. Sortable
    const el = document.getElementById('layout-sortable');
    const sortable = Sortable.create(el, {
        animation: 150,
        ghostClass: 'ghost',
        handle: '.drag-handle',
        onEnd: updateLayoutOrder
    });

    function updateLayoutOrder() {
        const order = sortable.toArray();
        document.getElementById('bo_cuc_order').value = JSON.stringify(order);
    }

    function toggleComponentStatus(chk) {
        const item = chk.closest('.sortable-item');
        if (chk.checked) {
            item.classList.add('active');
            item.style.opacity = '1';
            item.querySelector('.component-status').style.background = '#22c55e';
        } else {
            item.classList.remove('active');
            item.style.opacity = '0.6';
            item.querySelector('.component-status').style.background = '#cbd5e1';
        }
    }

    // 3. Template
    function applyTemplateLayout(select) {
        const opt = select.options[select.selectedIndex];
        if (!opt.value) return;

        const layout = JSON.parse(opt.getAttribute('data-layout') || '[]');
        const image = opt.getAttribute('data-img');
        
        if (layout.length > 0) {
            const container = document.getElementById('layout-sortable');
            const items = Array.from(container.children);
            layout.forEach(key => {
                const item = items.find(el => el.getAttribute('data-id') === key);
                if (item) {
                    container.appendChild(item);
                    const chk = item.querySelector('.component-checkbox');
                    chk.checked = true;
                    toggleComponentStatus(chk);
                }
            });
            items.forEach(item => {
                if (!layout.includes(item.getAttribute('data-id'))) {
                    const chk = item.querySelector('.component-checkbox');
                    chk.checked = false;
                    toggleComponentStatus(chk);
                }
            });
            updateLayoutOrder();
        }

        if (opt.getAttribute('data-location')) document.querySelector('input[name="dia_diem"]').value = opt.getAttribute('data-location');
        if (opt.getAttribute('data-max') > 0) document.querySelector('input[name="so_luong_toi_da"]').value = opt.getAttribute('data-max');
        if (opt.getAttribute('data-points') > 0) document.querySelector('input[name="diem_cong"]').value = opt.getAttribute('data-points');
        
        if (image) {
            document.getElementById('media_duong_dan').value = image;
            const imgUrl = image.startsWith('http') ? image : "{{ asset('storage') }}/" + image;
            showPreview(imgUrl);
        }
    }

    // 4. Media
    function toggleMediaLibrary() {
        const lib = document.getElementById('media-library-inline');
        lib.style.display = (lib.style.display === 'none') ? 'block' : 'none';
    }

    function selectMediaInline(path, element) {
        document.querySelectorAll('.media-item-container').forEach(el => el.classList.remove('border-primary', 'border-2'));
        element.classList.add('border-primary', 'border-2');
        document.getElementById('media_duong_dan').value = path;
        showPreview("{{ asset('storage') }}/" + path);
        setTimeout(toggleMediaLibrary, 200);
    }

    function showPreview(src) {
        const preview = document.getElementById('previewImg');
        const placeholder = document.getElementById('imgPreviewPlaceholder');
        const btnDelete = document.getElementById('btnDeletePreview');
        preview.src = src;
        preview.style.display = 'block';
        placeholder.style.display = 'none';
        btnDelete.style.display = 'block';
    }

    function clearPreview() {
        document.getElementById('imgInput').value = '';
        document.getElementById('media_duong_dan').value = '';
        document.getElementById('previewImg').style.display = 'none';
        document.getElementById('imgPreviewPlaceholder').style.display = 'block';
        document.getElementById('btnDeletePreview').style.display = 'none';
    }

    document.getElementById('imgInput').onchange = function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => showPreview(e.target.result);
            reader.readAsDataURL(this.files[0]);
        }
    };

    // Gallery
    document.getElementById('galleryInput').onchange = function() {
        const container = document.getElementById('gallery-preview');
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const div = document.createElement('div');
                div.className = 'position-relative';
                div.innerHTML = `
                    <img src="${e.target.result}" style="width:70px; height:70px; object-fit:cover; border-radius:10px;">
                    <button type="button" class="btn btn-danger btn-xs position-absolute top-0 end-0 rounded-circle" 
                        style="padding:2px 5px; font-size:10px; transform:translate(30%, -30%)" onclick="this.parentElement.remove()">×</button>
                `;
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    };

    function removeGalleryImage(id) {
        if(!confirm('Xóa ảnh này khỏi bộ sưu tập?')) return;
        fetch(`{{ url('admin/su-kien/xoa-anh') }}/${id}`, {
            method: 'DELETE',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) document.getElementById(`media-item-${id}`).remove();
        });
    }

    // 5. Overlap
    const checkInputs = ['input[name="dia_diem"]', 'input[name="thoi_gian_bat_dau"]', 'input[name="thoi_gian_ket_thuc"]'];
    checkInputs.forEach(s => {
        const el = document.querySelector(s);
        if(el) {
            el.onchange = () => {
                const dia_diem = document.querySelector('input[name="dia_diem"]').value;
                const t_start = document.querySelector('input[name="thoi_gian_bat_dau"]').value;
                const t_end = document.querySelector('input[name="thoi_gian_ket_thuc"]').value;
                if (dia_diem && t_start && t_end) {
                    fetch('{{ route("admin.su-kien.kiem-tra-trung-lich") }}', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        body: JSON.stringify({ dia_diem, thoi_gian_bat_dau: t_start, thoi_gian_ket_thuc: t_end, bo_qua_id: {{ $suKien->ma_su_kien }} })
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
            };
        }
    });

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
            if(data.success) location.reload();
        });
    }
</script>
@endsection
