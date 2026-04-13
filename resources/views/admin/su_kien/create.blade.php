@extends('admin.layout')

@section('title', 'Tạo sự kiện')
@section('page-title', 'Tạo sự kiện')

@php
    $defaultLayout = old('bo_cuc', ['banner', 'header', 'info', 'description', 'gallery']);
    $moduleMap = [
        'banner' => ['Ảnh bìa', 'bi-card-image', 'Hero và hình ảnh mở đầu'],
        'header' => ['Tiêu đề', 'bi-type-h1', 'Tiêu đề và đoạn dẫn nhập'],
        'info' => ['Thông tin', 'bi-info-circle', 'Thời gian, địa điểm, chỉ tiêu'],
        'description' => ['Nội dung', 'bi-text-paragraph', 'Phần mô tả chi tiết'],
        'gallery' => ['Gallery', 'bi-images', 'Thư viện ảnh hỗ trợ'],
    ];
    $templatePayload = $templates->map(function ($tpl) use ($defaultLayout) {
        return [
            'id' => $tpl->ma_su_kien,
            'name' => $tpl->ten_su_kien,
            'type' => $tpl->ma_loai_su_kien,
            'location' => $tpl->dia_diem,
            'capacity' => $tpl->so_luong_toi_da,
            'points' => $tpl->diem_cong,
            'content' => $tpl->mo_ta_chi_tiet,
            'layout' => $tpl->bo_cuc ?: $defaultLayout,
        ];
    })->keyBy('id');
    $selectedMedia = collect(old('selected_media_ids', []))->map(fn($id) => (int) $id)->all();
@endphp

@section('styles')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <style>
        /* Sử dụng biến CSS từ giao diện monochrome chung */
        .create-event {
            max-width: var(--max-width);
            margin: 0 auto;
            padding-bottom: calc(var(--space-2xl) * 2);
        }

        .hero-card {
            background: var(--bg-alt);
            padding: var(--space-xl);
            border-radius: var(--border-radius-md);
            border: 1px solid var(--border);
            margin-bottom: var(--space-lg);
            text-align: center;
        }

        .hero-card h2 {
            font-family: var(--font-serif);
            margin-bottom: var(--space-sm);
            color: var(--text);
        }

        .hero-card p {
            color: var(--text-muted);
        }

        .panel {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--border-radius-md);
            padding: var(--space-xl);
            margin-bottom: var(--space-lg);
        }

        .panel-head {
            margin-bottom: var(--space-lg);
            border-bottom: 1px solid var(--border-light);
            padding-bottom: var(--space-sm);
        }

        .panel-title {
            font-family: var(--font-serif);
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text);
        }

        .panel-subtitle {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(12, minmax(0, 1fr));
            gap: var(--space-md);
        }

        .span-12 {
            grid-column: span 12;
        }

        .span-6 {
            grid-column: span 6;
        }

        .span-4 {
            grid-column: span 4;
        }

        .field-note {
            color: var(--text-muted);
            font-size: 0.8rem;
            margin-top: var(--space-xs);
            display: block;
        }

        .dropzone {
            border: 2px dashed var(--border);
            padding: var(--space-xl);
            text-align: center;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: all 0.3s;
            background: var(--bg);
        }

        .dropzone.drag {
            border-color: var(--accent);
            background: var(--accent-bg);
        }

        .dropzone i {
            font-size: 2rem;
            color: var(--accent);
        }

        .upload-title {
            font-weight: 600;
            margin-top: var(--space-sm);
            color: var(--text);
        }

        .cover-preview,
        .gallery-preview {
            display: none;
            margin-top: var(--space-md);
        }

        .cover-preview img {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: var(--border-radius);
            border: 1px solid var(--border);
        }

        .gallery-preview {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: var(--space-sm);
        }

        .gallery-item {
            position: relative;
        }

        .gallery-item img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: var(--border-radius);
        }

        .gallery-item button {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger);
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .library-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: var(--space-sm);
        }

        .library-item {
            border: 1px solid var(--border);
            border-radius: var(--border-radius);
            padding: var(--space-xs);
            cursor: pointer;
            text-align: center;
            background: var(--card);
            transition: all 0.2s;
            position: relative;
        }

        .library-item img {
            width: 100%;
            height: 90px;
            object-fit: cover;
            border-radius: var(--border-radius);
            margin-bottom: var(--space-xs);
        }

        .library-item.selected {
            border-color: var(--success);
            background: var(--success-bg);
        }

        .library-check {
            position: absolute;
            top: var(--space-xs);
            right: var(--space-xs);
            background: var(--success);
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .library-item.selected .library-check {
            display: flex;
        }

        .library-caption {
            font-size: 0.75rem;
            color: var(--text-muted);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .chip-row {
            display: flex;
            gap: var(--space-sm);
            flex-wrap: wrap;
            margin-bottom: var(--space-md);
        }

        .module-chip {
            background: var(--bg-alt);
            border: 1px solid var(--border);
            padding: var(--space-sm) var(--space-md);
            border-radius: 20px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .module-chip:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .layout-list {
            display: flex;
            flex-direction: column;
            gap: var(--space-sm);
        }

        .layout-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--card);
            border: 1px solid var(--border);
            padding: var(--space-sm) var(--space-md);
            border-radius: var(--border-radius);
        }

        .layout-item-left {
            display: flex;
            align-items: center;
            gap: var(--space-md);
        }

        .layout-handle {
            cursor: grab;
            color: var(--text-muted);
            font-size: 1.2rem;
        }

        .layout-remove {
            background: var(--danger-bg);
            color: var(--danger);
            border: none;
            padding: var(--space-xs) var(--space-sm);
            border-radius: var(--border-radius);
            cursor: pointer;
        }

        .layout-badge i {
            font-size: 1.2rem;
            color: var(--accent);
        }

        .ql-toolbar,
        .ql-container {
            border-color: var(--border) !important;
            font-family: var(--font-sans) !important;
        }

        .ql-container {
            min-height: 300px;
            font-size: 1rem !important;
        }

        .submit-bar {
            position: fixed;
            bottom: 0;
            left: var(--sidebar-w);
            right: 0;
            background: var(--card);
            border-top: 1px solid var(--border);
            padding: var(--space-md) var(--space-lg);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.05);
        }

        @media (max-width: 991.98px) {
            .submit-bar {
                left: 0;
                flex-direction: column;
                gap: var(--space-sm);
            }

            .span-6,
            .span-4 {
                grid-column: span 12;
            }
        }
    </style>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.su-kien.store') }}" enctype="multipart/form-data" id="suKienForm">
        @csrf
        <input type="hidden" name="mo_ta_chi_tiet" id="mo_ta_chi_tiet" value="{{ old('mo_ta_chi_tiet') }}">
        <div id="selected-media-inputs"></div>

        <div class="create-event">
            @if ($errors->any())
                <div class="alert alert-error mb-lg">
                    <strong>Vui lòng kiểm tra lại thông tin trước khi tạo sự kiện.</strong>
                    <ul class="mb-0 mt-sm pl-md">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="hero-card">
                <h2>Tạo Khởi Kiện & Bố Cục</h2>
                <p>Xây dựng trang sự kiện để điền thông tin nhanh và rõ ràng. Khối nội dung được sắp xếp logic bằng cấu trúc
                    mô-đun kéo thả linh hoạt.</p>
            </div>

            <div class="form-grid">
                <div class="span-12">
                    <section class="panel">
                        <div class="panel-head">
                            <div class="panel-title">Thông tin cơ bản</div>
                            <p class="panel-subtitle">Điền tên, loại, lịch trình thời gian và thuộc tính của sự kiện.</p>
                        </div>
                        <div class="form-grid">
                            <div class="span-12">
                                <label class="form-label" for="ten_su_kien">Tên sự kiện <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('ten_su_kien') border-danger @enderror" id="ten_su_kien" name="ten_su_kien"
                                    value="{{ old('ten_su_kien') }}" placeholder="Ví dụ: Lễ hội công nghệ TECHX" required>
                                @error('ten_su_kien')
                                    <span class="text-danger" style="font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="span-6">
                                <label class="form-label" for="ma_loai_su_kien">Loại sự kiện <span
                                        class="text-danger">*</span></label>
                                <select class="form-control @error('ma_loai_su_kien') border-danger @enderror" id="ma_loai_su_kien" name="ma_loai_su_kien" required>
                                    <option value="">Chọn loại sự kiện</option>
                                    @foreach ($loai as $item)
                                        <option value="{{ $item->ma_loai_su_kien }}"
                                            @selected(old('ma_loai_su_kien') == $item->ma_loai_su_kien)>{{ $item->ten_loai }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ma_loai_su_kien')
                                    <span class="text-danger" style="font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="span-6">
                                <label class="form-label" for="dia_diem">Địa điểm <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('dia_diem') border-danger @enderror" id="dia_diem" name="dia_diem"
                                    value="{{ old('dia_diem') }}" placeholder="VD: Hội trường A, Nhà E" required>
                                @error('dia_diem')
                                    <span class="text-danger" style="font-size: 0.8rem; margin-top: 4px; display: block;"><i class="bi bi-exclamation-triangle"></i> {{ $message }}</span>
                                @else
                                    <span class="field-note">Hệ thống sẽ phát cảnh báo nếu phát hiện trùng lịch sự kiện tại cùng
                                        khu vực.</span>
                                @enderror
                            </div>
                            <div class="span-6">
                                <label class="form-label" for="thoi_gian_bat_dau">Thời gian bắt đầu <span
                                        class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('thoi_gian_bat_dau') border-danger @enderror" id="thoi_gian_bat_dau"
                                    name="thoi_gian_bat_dau" value="{{ old('thoi_gian_bat_dau') }}" required>
                                @error('thoi_gian_bat_dau')
                                    <span class="text-danger" style="font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="span-6">
                                <label class="form-label" for="thoi_gian_ket_thuc">Thời gian kết thúc <span
                                        class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('thoi_gian_ket_thuc') border-danger @enderror" id="thoi_gian_ket_thuc"
                                    name="thoi_gian_ket_thuc" value="{{ old('thoi_gian_ket_thuc') }}" required>
                                @error('thoi_gian_ket_thuc')
                                    <span class="text-danger" style="font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="span-6">
                                <label class="form-label" for="so_luong_toi_da">Số lượng vé / người tối đa <span class="text-danger">*</span></label>
                                <input type="number" min="1" class="form-control @error('so_luong_toi_da') border-danger @enderror" id="so_luong_toi_da"
                                    name="so_luong_toi_da" value="{{ old('so_luong_toi_da') }}" placeholder="VD: 300" required>
                                @error('so_luong_toi_da')
                                    <span class="text-danger" style="font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="span-6">
                                <label class="form-label" for="diem_cong">Điểm cộng rèn luyện <span class="text-danger">*</span></label>
                                <input type="number" min="0" class="form-control @error('diem_cong') border-danger @enderror" id="diem_cong" name="diem_cong"
                                    value="{{ old('diem_cong') }}" placeholder="VD: 5" required>
                                @error('diem_cong')
                                    <span class="text-danger" style="font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </section>
                </div>

                <div class="span-12">
                    <section class="panel">
                        <div class="panel-head">
                            <div class="panel-title">Áp dụng Mẫu (Template) & Cấu trúc</div>
                            <p class="panel-subtitle">Kế thừa nhanh bố cục, nội dung có sẵn và phân chia mô-đun hiển thị cho
                                một sự kiện.</p>
                        </div>

                        <div class="form-group mb-lg">
                            <label class="form-label">Kho thiết kế (Template)</label>
                            <div style="display:flex; gap:var(--space-sm);">
                                <select class="form-control" id="templateSelect" style="flex:1;">
                                    <option value="">Mặc định không sử dụng mẫu</option>
                                    @foreach ($templates as $tpl)
                                        <option value="{{ $tpl->ma_su_kien }}">{{ $tpl->ten_su_kien }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline" id="applyTemplateBtn">
                                    <i class="bi bi-lightning-charge"></i> Áp dụng
                                </button>
                            </div>
                            <span class="field-note">Lưu ý: Thao tác này sẽ ghi đè lên nội dung, địa điểm và danh sách
                                mô-đun theo mẫu được chọn.</span>
                        </div>

                        <label class="form-label mt-md">Danh sách Mô-đun phụ trợ (Kéo thả để sắp xếp)</label>
                        <div class="chip-row">
                            @foreach ($moduleMap as $key => $module)
                                <button type="button" class="module-chip" data-add-module="{{ $key }}">
                                    <i class="bi {{ $module[1] }}"></i> Thêm {{ $module[0] }}
                                </button>
                            @endforeach
                        </div>
                        <div class="layout-list" id="layoutList"></div>
                    </section>
                </div>

                <div class="span-12">
                    <section class="panel">
                        <div class="panel-head">
                            <div class="panel-title">Soạn thảo văn bản chi tiết <span class="text-danger">*</span></div>
                            <p class="panel-subtitle">Khung soạn thảo trọn vẹn dành cho các thông tin, giới thiệu, chương
                                trình liên quan.</p>
                        </div>
                        <div id="editor-container" class="@error('mo_ta_chi_tiet') border-danger @enderror"></div>
                        @error('mo_ta_chi_tiet')
                            <span class="text-danger" style="font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </section>
                </div>

                <div class="span-12">
                    <section class="panel">
                        <div class="panel-head">
                            <div class="panel-title">Media & Thư viện hình ảnh</div>
                            <p class="panel-subtitle">Hỗ trợ gắn đa phương tiện, băng rôn, gallery hình chụp cho sự kiện
                                này.</p>
                        </div>
                        <div class="form-grid">
                            <div class="span-6">
                                <label class="form-label">Ảnh bìa (Cover Image)</label>
                                <div class="dropzone" id="coverDropzone">
                                    <div class="upload-surface" id="coverSurface">
                                        <i class="bi bi-cloud-arrow-up"></i>
                                        <div class="upload-title">Kéo ảnh vào đây hoặc nhấn để chọn file</div>
                                        <span class="field-note">Đề xuất sử dụng ảnh ngang sắc nét, tỷ lệ 16:9.</span>
                                    </div>
                                    <div class="cover-preview" id="coverPreview">
                                        <img id="coverPreviewImage" alt="Cover preview">
                                        <div class="text-center" style="margin-top:var(--space-sm);">
                                            <button type="button" class="btn btn-outline text-danger"
                                                onclick="clearCover()">
                                                <i class="bi bi-trash"></i> Xóa ảnh bìa
                                            </button>
                                        </div>
                                    </div>
                                    <input type="file" style="display:none;" id="coverInput" name="anh_su_kien"
                                        accept="image/*">
                                </div>
                            </div>

                            <div class="span-6">
                                <label class="form-label">Album Tải lên trực tiếp</label>
                                <div class="dropzone" id="galleryDropzone">
                                    <div class="upload-surface" id="gallerySurface">
                                        <i class="bi bi-collection"></i>
                                        <div class="upload-title">Kéo thả bộ sưu tập vào đây</div>
                                        <span class="field-note">Bạn có thể chọn nhiều ảnh cùng lúc, không giới hạn.</span>
                                    </div>
                                    <input type="file" style="display:none;" id="galleryInput" name="gallery[]"
                                        accept="image/*" multiple>
                                </div>
                                <div class="gallery-preview" id="galleryPreview"></div>
                            </div>

                            <div class="span-12">
                                <hr class="section-rule">
                                <label class="form-label">Bộ từ Thư viện Media Lưu Trữ sẵn</label>
                                <p class="panel-subtitle" style="margin-bottom:var(--space-md);">Những tập tin phương tiện
                                    này đã được tải lên trước đây, chỉ việc tích chọn để gắn kèm.</p>
                                <div class="library-grid" id="libraryGrid">
                                    @forelse ($mediaKho as $media)
                                        @php $picked = in_array($media->ma_phuong_tien, $selectedMedia, true); @endphp
                                        <button type="button" class="library-item {{ $picked ? 'selected' : '' }}"
                                            data-media-id="{{ $media->ma_phuong_tien }}">
                                            <img src="{{ asset('storage/' . $media->duong_dan_tep) }}"
                                                alt="{{ $media->ten_tep }}">
                                            <span class="library-check"><i class="bi bi-check2"></i></span>
                                            <div class="library-caption" title="{{ $media->ten_tep }}">
                                                {{ $media->ten_tep ?: basename($media->duong_dan_tep) }}</div>
                                        </button>
                                    @empty
                                        <div class="p-md text-center text-muted"
                                            style="border: 1px dashed var(--border); border-radius: var(--border-radius); grid-column: 1 / -1; padding: var(--space-md);">
                                            Chưa có thông tin hồ sơ ảnh nghệ thuật nào lưu sẵn.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <div class="submit-bar">
            <span class="text-muted text-sm d-none d-lg-block">Vui lòng rà soát đầy đủ và cẩn thận mọi thông tin trước khi
                chính thức đưa lên hệ thống.</span>
            <div style="display:flex; gap:var(--space-sm);">
                <a href="{{ route('admin.su-kien.index') }}" class="btn btn-outline"
                    style="min-width: 120px; text-align:center;">Hủy và Quay lại</a>
                <button type="submit" class="btn btn-primary" id="submitBtn" style="min-width: 160px;">
                    <i class="bi bi-check2-circle"></i> Khởi tạo sự kiện
                </button>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
        const moduleCatalog = @json($moduleMap);
        const templatePayload = @json($templatePayload);
        const initialLayout = @json(array_values($defaultLayout));
        const initialDescription = @json(old('mo_ta_chi_tiet'));
        const initialSelectedMedia = @json($selectedMedia);

        const form = document.getElementById('suKienForm');
        const coverInput = document.getElementById('coverInput');
        const coverDropzone = document.getElementById('coverDropzone');
        const coverSurface = document.getElementById('coverSurface');
        const coverPreview = document.getElementById('coverPreview');
        const coverPreviewImage = document.getElementById('coverPreviewImage');
        const galleryInput = document.getElementById('galleryInput');
        const galleryDropzone = document.getElementById('galleryDropzone');
        const galleryPreview = document.getElementById('galleryPreview');
        const layoutList = document.getElementById('layoutList');
        const selectedMediaInputs = document.getElementById('selected-media-inputs');
        const submitBtn = document.getElementById('submitBtn');

        const quill = new Quill('#editor-container', {
            theme: 'snow',
            placeholder: 'Soạn thảo nội dung sự kiện ở đây...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['blockquote', 'link'],
                    ['clean']
                ]
            }
        });
        if (initialDescription) { quill.clipboard.dangerouslyPasteHTML(initialDescription); }
        document.getElementById('mo_ta_chi_tiet').value = initialDescription || '';
        quill.on('text-change', () => document.getElementById('mo_ta_chi_tiet').value = quill.root.innerHTML);

        let layoutState = [...initialLayout];
        let selectedMedia = new Set(initialSelectedMedia);
        let galleryFiles = [];

        function renderLayout() {
            if (!layoutState.length) {
                layoutList.innerHTML = '<div style="border: 1px dashed var(--border); padding: var(--space-md); text-align: center; color: var(--text-muted); border-radius: var(--border-radius);">Khung chức năng đang trống trơn. Hãy ấn "Thêm" để chọn khối chức năng.</div>';
                return;
            }
            layoutList.innerHTML = layoutState.map((key, index) => {
                const item = moduleCatalog[key] || ['Mô-đun', 'bi-grid', ''];
                return `
            <div class="layout-item" data-index="${index}">
                <div class="layout-item-left">
                    <i class="bi bi-grip-vertical layout-handle" title="Kéo thả điều hướng"></i>
                    <div class="layout-badge"><i class="bi ${item[1]}"></i></div>
                    <div>
                        <strong>${item[0]}</strong>
                        <div style="font-size:0.8rem;color:var(--text-muted);">${item[2]}</div>
                    </div>
                </div>
                <input type="hidden" name="bo_cuc[]" value="${key}">
                <button type="button" class="layout-remove" data-remove-index="${index}" title="Huỷ khối này"><i class="bi bi-x-lg"></i></button>
            </div>`;
            }).join('');
        }

        function syncSelectedMedia() {
            selectedMediaInputs.innerHTML = [...selectedMedia].map(id => `<input type="hidden" name="selected_media_ids[]" value="${id}">`).join('');
            document.querySelectorAll('[data-media-id]').forEach(item => {
                const active = selectedMedia.has(Number(item.dataset.mediaId));
                item.classList.toggle('selected', active);
            });
        }

        function renderGallery() {
            galleryPreview.style.display = galleryFiles.length ? 'grid' : 'none';
            galleryPreview.innerHTML = galleryFiles.map((file, index) => `
            <div class="gallery-item">
                <img src="${URL.createObjectURL(file)}" alt="gallery image">
                <button type="button" data-remove-gallery="${index}"><i class="bi bi-x"></i></button>
                <div style="font-size:0.75rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin-top:4px;" class="text-muted">${file.name}</div>
            </div>
        `).join('');

            try {
                const dt = new DataTransfer();
                galleryFiles.forEach(file => dt.items.add(file));
                galleryInput.files = dt.files;
            } catch (e) { console.log(e); }
        }

        function showCover(file) {
            if (!file) { clearCover(); return; }
            const reader = new FileReader();
            reader.onload = e => {
                coverPreviewImage.src = e.target.result;
                coverPreview.style.display = 'block';
                coverSurface.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }

        function clearCover() {
            coverInput.value = '';
            coverPreview.style.display = 'none';
            coverSurface.style.display = 'block';
        }

        function bindDropzone(zone, input, onFiles) {
            ['dragenter', 'dragover'].forEach(evt => zone.addEventListener(evt, e => {
                e.preventDefault(); zone.classList.add('drag');
            }));
            ['dragleave', 'drop'].forEach(evt => zone.addEventListener(evt, e => {
                e.preventDefault(); zone.classList.remove('drag');
            }));
            zone.addEventListener('click', e => {
                if (e.target.closest('button')) return;
                input.click();
            });
            input.addEventListener('change', e => {
                if (e.target.files?.length) onFiles(e.target.files);
            });
            zone.addEventListener('drop', async e => {
                e.preventDefault();
                zone.classList.remove('drag');
                if (e.dataTransfer?.files?.length) {
                    onFiles(e.dataTransfer.files);
                    return;
                }
                
                // Hỗ trợ kéo thả ảnh từ trang web khác (Image URL)
                const html = e.dataTransfer.getData('text/html');
                const url = e.dataTransfer.getData('URL') || e.dataTransfer.getData('text/uri-list');
                
                let imgSrc = null;
                if (html) {
                    const match = html.match(/<img.*?src=["'](.*?)["']/);
                    if (match) imgSrc = match[1];
                }
                if (!imgSrc && url && url.match(/\.(jpeg|jpg|gif|png|webp)(\?.*)?$/i)) {
                    imgSrc = url;
                }
                
                if (imgSrc) {
                    try {
                        // Tạo thông báo tải
                        const originHtml = zone.innerHTML;
                        zone.innerHTML = '<div class="upload-surface"><div class="spinner-border text-primary" role="status"></div><div class="mt-2 text-primary">Đang kéo ảnh từ web...</div></div>';
                        
                        const res = await fetch(imgSrc);
                        if (!res.ok) throw new Error('Network response was not ok');
                        const blob = await res.blob();
                        const filename = imgSrc.split('/').pop().split('?')[0] || 'web-image.jpg';
                        const file = new File([blob], filename, { type: blob.type });
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        
                        zone.innerHTML = originHtml;
                        onFiles(dt.files);
                    } catch(err) {
                        zone.innerHTML = '<div class="upload-surface text-danger"><i class="bi bi-x-circle text-danger"></i><div class="mt-2">Lỗi tải ảnh từ nguồn khác (CORS chặn). Vui lòng lưu hình về máy trước khi kéo thả tải lên.</div></div>';
                        setTimeout(() => {
                            // Phục hồi UI gốc sau 3s
                            const title = zone.id === 'coverDropzone' ? 'Kéo ảnh vào đây hoặc nhấn để chọn file' : 'Kéo thả bộ sưu tập vào đây';
                            zone.innerHTML = `<div class="upload-surface" id="${zone.id.replace('Dropzone', 'Surface')}"><i class="bi bi-cloud-arrow-up"></i><div class="upload-title">${title}</div></div>`;
                        }, 4000);
                    }
                }
            });
        }

        bindDropzone(coverDropzone, coverInput, files => {
            const file = [...files].find(f => f.type.startsWith('image/'));
            if (!file) return;
            try {
                const dt = new DataTransfer();
                dt.items.add(file);
                coverInput.files = dt.files;
            } catch (e) { }
            showCover(file);
        });

        bindDropzone(galleryDropzone, galleryInput, files => {
            galleryFiles = [...galleryFiles, ...[...files].filter(f => f.type.startsWith('image/'))];
            renderGallery();
        });

        document.addEventListener('click', e => {
            const addButton = e.target.closest('[data-add-module]');
            if (addButton) {
                layoutState.push(addButton.dataset.addModule);
                renderLayout();
                return;
            }
            const removeButton = e.target.closest('[data-remove-index]');
            if (removeButton) {
                layoutState.splice(Number(removeButton.dataset.removeIndex), 1);
                renderLayout();
                return;
            }
            const removeGallery = e.target.closest('[data-remove-gallery]');
            if (removeGallery) {
                galleryFiles.splice(Number(removeGallery.dataset.removeGallery), 1);
                renderGallery();
                return;
            }
            const mediaCard = e.target.closest('[data-media-id]');
            if (mediaCard) {
                const mediaId = Number(mediaCard.dataset.mediaId);
                selectedMedia.has(mediaId) ? selectedMedia.delete(mediaId) : selectedMedia.add(mediaId);
                syncSelectedMedia();
            }
        });

        new Sortable(layoutList, {
            animation: 150,
            handle: '.layout-handle',
            onEnd() {
                layoutState = [...layoutList.querySelectorAll('input[name="bo_cuc[]"]')].map(input => input.value);
                renderLayout();
            }
        });

        document.getElementById('applyTemplateBtn').addEventListener('click', () => {
            const id = document.getElementById('templateSelect').value;
            if (!id || !templatePayload[id]) return;
            const tpl = templatePayload[id];
            document.getElementById('ma_loai_su_kien').value = tpl.type || '';
            document.getElementById('dia_diem').value = tpl.location || '';
            document.getElementById('so_luong_toi_da').value = tpl.capacity || '';
            document.getElementById('diem_cong').value = tpl.points || '';

            quill.root.innerHTML = '';
            if (tpl.content) { quill.clipboard.dangerouslyPasteHTML(tpl.content); }
            document.getElementById('mo_ta_chi_tiet').value = quill.root.innerHTML;

            layoutState = tpl.layout?.length ? [...tpl.layout] : ['banner', 'header', 'info', 'description', 'gallery'];
            renderLayout();
            alert('Áp dụng mẫu bố cục thành công!');
        });

        form.addEventListener('submit', async e => {
            if (form.dataset.collisionChecked === '1') return;
            const start = document.getElementById('thoi_gian_bat_dau').value;
            const end = document.getElementById('thoi_gian_ket_thuc').value;
            const location = document.getElementById('dia_diem').value.trim();

            if (!start || !end || !location) return;

            e.preventDefault();
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" style="margin-right: 5px;"></span> Điểm tra lịch...';

            try {
                const res = await fetch('{{ route('admin.su-kien.check-collision') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ thoi_gian_bat_dau: start, thoi_gian_ket_thuc: end, dia_diem: location })
                });
                const data = await res.json();

                if (data.has_collision) {
                    const detail = (data.conflicts || []).map(item => `- Sự kiện: ${item.ten_su_kien} (${new Date(item.thoi_gian_bat_dau).toLocaleString('vi-VN')} - ${new Date(item.thoi_gian_ket_thuc).toLocaleString('vi-VN')})`).join('\n');
                    if (!confirm(`Phát hiện TRÙNG LỊCH tại địa điểm này:\n\n${detail}\n\nBạn vẫn muốn tạo sự kiện này bất chấp trùng lịch?`)) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="bi bi-check2-circle"></i> Khởi tạo sự kiện';
                        return;
                    }
                    
                    // Add force_create field to bypass backend verification
                    const forceInput = document.createElement('input');
                    forceInput.type = 'hidden';
                    forceInput.name = 'force_create';
                    forceInput.value = '1';
                    form.appendChild(forceInput);
                }
            } catch (err) {
                console.error('Lỗi kiểm tra trùng lịch:', err);
            }

            form.dataset.collisionChecked = '1';
            form.submit();
        });

        renderLayout();
        syncSelectedMedia();
    </script>
@endsection