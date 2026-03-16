@extends('admin.layout')

@section('title','Tạo sự kiện')
@section('page-title','Tạo sự kiện')

@section('styles')

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<style>
    :root {
        --primary: #2563eb;
        --bg: #f8fafc;
        --card: #ffffff;
        --border: #e5e7eb;
        --text: #111827;
        --muted: #6b7280;
    }

    body {
        background: var(--bg);
    }

    .page-wrapper {
        max-width: 1400px;
        margin: auto;
    }

    /* Thanh hành động */

    .action-toolbar {
        background: white;
        border-bottom: 1px solid var(--border);
        padding: 16px 24px;
        margin-bottom: 24px;
    }

    /* Card */

    .card-ui {
        background: white;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 24px;
    }

    .card-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    /* Input */

    .form-control,
    .form-select {
        border-radius: 10px;
        border: 1px solid var(--border);
        padding: 12px 16px;
        font-size: 15px;
        height: auto;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        text-transform: none;
        letter-spacing: normal;
        color: var(--text);
        margin-bottom: 8px;
    }

    /* Layout builder */

    .sortable-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 10px 14px;
        background: white;
    }

    .drag-handle {
        cursor: grab;
        color: #9ca3af;
    }

    /* Upload */

    .upload-box {
        border: 2px dashed var(--border);
        border-radius: 12px;
        padding: 40px;
        text-align: center;
        cursor: pointer;
    }

    .upload-box:hover {
        border-color: var(--primary);
    }

    #editor-container {
        min-height: 400px;
        font-size: 15px;
    }

    .ql-toolbar {
        border-radius: 10px 10px 0 0 !important;
        padding: 12px !important;
    }

    .ql-container {
        border-radius: 0 0 10px 10px !important;
        font-size: 15px;
    }
</style>

@endsection

@section('content')

<form method="POST"
    action="{{ route('admin.su-kien.store') }}"
    enctype="multipart/form-data"
    id="suKienForm">

    @csrf

    <div class="page-wrapper">

        <!-- Thanh công cụ -->

        <div class="action-toolbar d-flex justify-content-between align-items-center">

            <div>
                <h5 class="fw-bold mb-0">Tạo sự kiện</h5>
                <small class="text-muted">Thiết lập thông tin cho sự kiện</small>
            </div>

            <div class="d-flex gap-2">

                <a href="{{ route('admin.su-kien.index') }}"
                    class="btn btn-light border">
                    Hủy
                </a>

                <button type="button" class="btn btn-primary" id="submitBtn">
                    Lưu sự kiện
                </button>

            </div>

        </div>

        <div class="row g-4">

            <!-- CỘT TRÁI - TOÀN BỘ CHIỀU RỘNG -->

            <div class="col-12">

                <!-- Thông tin sự kiện -->

                <div class="card-ui mb-4">

                    <div class="card-title">Thông tin sự kiện</div>

                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label">Tên sự kiện</label>

                            <input type="text"
                                name="ten_su_kien"
                                class="form-control"
                                placeholder="Nhập tên sự kiện"
                                required>
                        </div>

                        <div class="col-md-6">

                            <label class="form-label">Mẫu bài đăng</label>

                            <select id="templateSelect"
                                class="form-select">

                                <option value="">
                                    Mặc định
                                </option>

                                @foreach($templates as $tpl)

                                <option
                                    value="{{ $tpl->ma_mau }}"
                                    data-layout="{{ json_encode($tpl->bo_cuc ?? []) }}">

                                    {{ $tpl->ten_mau }}

                                </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">Loại sự kiện</label>

                            <select
                                name="ma_loai_su_kien"
                                class="form-select">

                                <option value="">
                                    Chọn loại sự kiện
                                </option>

                                @foreach($loai as $l)

                                <option
                                    value="{{ $l->ma_loai_su_kien }}">

                                    {{ $l->ten_loai }}

                                </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">Thời gian bắt đầu</label>

                            <input
                                type="datetime-local"
                                name="thoi_gian_bat_dau"
                                class="form-control">

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">Thời gian kết thúc</label>

                            <input
                                type="datetime-local"
                                name="thoi_gian_ket_thuc"
                                class="form-control">

                        </div>

                        <div class="col-12">

                            <label class="form-label">Địa điểm</label>

                            <input
                                type="text"
                                name="dia_diem"
                                class="form-control"
                                placeholder="Nhập địa điểm tổ chức">

                        </div>

                    </div>

                </div>

                <!-- Bố cục nội dung -->

                <div class="card-ui mb-4">

                    <div class="card-title">
                        Bố cục bài đăng
                    </div>

                    <p class="text-muted small mb-3">
                        Kéo thả để thay đổi thứ tự hiển thị
                    </p>

                    <div
                        id="layout-sortable"
                        class="d-flex flex-column gap-2">

                        <div
                            class="sortable-item"
                            data-id="banner">

                            <div
                                class="d-flex align-items-center gap-2">

                                <i class="bi bi-grip-vertical drag-handle"></i>

                                <span>Ảnh bìa</span>

                            </div>

                            <input
                                type="checkbox"
                                name="bo_cuc[]"
                                value="banner"
                                checked>

                        </div>

                        <div
                            class="sortable-item"
                            data-id="header">

                            <div
                                class="d-flex align-items-center gap-2">

                                <i class="bi bi-grip-vertical drag-handle"></i>

                                <span>Tiêu đề sự kiện</span>

                            </div>

                            <input
                                type="checkbox"
                                name="bo_cuc[]"
                                value="header"
                                checked>

                        </div>

                        <div
                            class="sortable-item"
                            data-id="info">

                            <div
                                class="d-flex align-items-center gap-2">

                                <i class="bi bi-grip-vertical drag-handle"></i>

                                <span>Thông tin sự kiện</span>

                            </div>

                            <input
                                type="checkbox"
                                name="bo_cuc[]"
                                value="info"
                                checked>

                        </div>

                        <div
                            class="sortable-item"
                            data-id="description">

                            <div
                                class="d-flex align-items-center gap-2">

                                <i class="bi bi-grip-vertical drag-handle"></i>

                                <span>Nội dung chi tiết</span>

                            </div>

                            <input
                                type="checkbox"
                                name="bo_cuc[]"
                                value="description"
                                checked>

                        </div>

                        <div
                            class="sortable-item"
                            data-id="gallery">

                            <div
                                class="d-flex align-items-center gap-2">

                                <i class="bi bi-grip-vertical drag-handle"></i>

                                <span>Thư viện ảnh</span>

                            </div>

                            <input
                                type="checkbox"
                                name="bo_cuc[]"
                                value="gallery"
                                checked>

                        </div>

                    </div>

                    <input
                        type="hidden"
                        name="bo_cuc_order"
                        id="bo_cuc_order">

                </div>

                <!-- Nội dung -->

                <div class="card-ui mb-4">

                    <div class="card-title">
                        Nội dung sự kiện
                    </div>

                    <input
                        type="hidden"
                        name="mo_ta_chi_tiet"
                        id="mo_ta_chi_tiet">

                    <div id="editor-container"></div>

                </div>

                <!-- Media -->

                <div class="card-ui">

                    <div class="card-title">
                        Hình ảnh
                    </div>

                    <div class="row g-4">

                        <div class="col-md-6">

                            <label class="form-label">
                                Ảnh bìa
                            </label>

                            <div
                                class="upload-box"
                                onclick="imgInput.click()">

                                <i class="bi bi-image fs-1 text-muted"></i>

                                <p class="mt-2 mb-0">
                                    Tải ảnh bìa
                                </p>

                                <input
                                    type="file"
                                    name="anh_su_kien"
                                    id="imgInput"
                                    class="d-none">

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Thư viện ảnh (Chọn nhiều)
                            </label>

                            <div
                                class="upload-box"
                                onclick="galleryInput.click()">

                                <i class="bi bi-images fs-1 text-muted"></i>

                                <p class="mt-2 mb-0">
                                    Tải nhiều ảnh
                                </p>

                                <input
                                    type="file"
                                    name="gallery[]"
                                    id="galleryInput"
                                    multiple
                                    accept="image/*"
                                    class="d-none">

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- SIDEBAR - DƯỚI FORM CHÍNH -->

            <div class="row g-4">

                <div class="col-lg-4">
                    Cấu hình
                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Số người tối đa
                    </label>

                    <input
                        type="number"
                        name="so_luong_toi_da"
                        class="form-control">

                </div>

                <div>

                    <label class="form-label">
                        Điểm rèn luyện
                    </label>

                    <input
                        type="number"
                        name="diem_cong"
                        class="form-control">

                </div>

            </div>

        </div>

    </div>

    </div>

</form>

@endsection


@section('scripts')

<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
    // Quill Editor
    const quill = new Quill('#editor-container', {
        theme: 'snow',
        placeholder: 'Viết nội dung sự kiện tại đây...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{
                    'header': 1
                }, {
                    'header': 2
                }],
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                ['link'],
                ['clean']
            ]
        }
    })

    quill.on('text-change', () => {
        document.getElementById('mo_ta_chi_tiet').value = quill.root.innerHTML
    })

    // Sortable for Layout Builder
    setTimeout(() => {
        const sortableElement = document.getElementById('layout-sortable')
        if (sortableElement && typeof Sortable !== 'undefined') {
            console.log('Initializing Sortable...')
            const sortable = Sortable.create(sortableElement, {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'sortable-ghost',
                forceFallback: false,
                onEnd: function(evt) {
                    console.log('Dragged from', evt.oldIndex, 'to', evt.newIndex)
                    updateLayout()
                }
            })
            console.log('Sortable initialized')
        } else {
            console.error('Sortable not found or element missing')
        }
    }, 500)

    function updateLayout() {
        const order = Array.from(document.querySelectorAll('#layout-sortable [data-id]'))
            .map(el => el.getAttribute('data-id'))
        document.getElementById('bo_cuc_order').value = JSON.stringify(order)
    }

    // Form Validation Warnings & Collision Detection
    document.getElementById('submitBtn')?.addEventListener('click', async function(e) {
        const tenSuKien = document.querySelector('input[name="ten_su_kien"]').value.trim()
        const loaiSuKien = document.querySelector('select[name="ma_loai_su_kien"]').value
        const thoiGianBatDau = document.querySelector('input[name="thoi_gian_bat_dau"]').value
        const thoiGianKetThuc = document.querySelector('input[name="thoi_gian_ket_thuc"]').value
        const diaDiem = document.querySelector('input[name="dia_diem"]').value.trim()
        const moTa = document.getElementById('mo_ta_chi_tiet').value.trim()

        let warnings = []

        // Kiểm tra thông tin bắt buộc
        if (!tenSuKien) warnings.push('• Chưa nhập tên sự kiện')
        if (!loaiSuKien) warnings.push('• Chưa chọn loại sự kiện')
        if (!thoiGianBatDau) warnings.push('• Chưa chọn thời gian bắt đầu')
        if (!thoiGianKetThuc) warnings.push('• Chưa chọn thời gian kết thúc')
        if (!diaDiem) warnings.push('• Chưa nhập địa điểm')
        if (!moTa || moTa === '<br>') warnings.push('• Chưa nhập nội dung sự kiện')

        // Kiểm tra logic ngày tháng
        if (thoiGianBatDau && thoiGianKetThuc) {
            const batDau = new Date(thoiGianBatDau)
            const ketThuc = new Date(thoiGianKetThuc)
            if (ketThuc <= batDau) {
                warnings.push('• Thời gian kết thúc phải sau thời gian bắt đầu')
            }
        }

        // Hiển thị warning nếu có
        if (warnings.length > 0) {
            e.preventDefault()
            alert('⚠️ CẢNH BÁO - Vui lòng điền đầy đủ thông tin:\n\n' + warnings.join('\n'))
            return false
        }

        // Kiểm tra sự kiện trùng lịch
        if (thoiGianBatDau && thoiGianKetThuc && diaDiem) {
            e.preventDefault()
            const checkBtn = this
            checkBtn.disabled = true
            checkBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Đang kiểm tra...'

            try {
                const response = await fetch('/admin/su-kien/check-collision', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        thoi_gian_bat_dau: thoiGianBatDau,
                        thoi_gian_ket_thuc: thoiGianKetThuc,
                        dia_diem: diaDiem
                    })
                })

                const result = await response.json()

                if (result.has_collision) {
                    checkBtn.disabled = false
                    checkBtn.innerHTML = 'Tạo sự kiện'
                    const collisionInfo = result.conflicts.map(sk =>
                        `• ${sk.ten_su_kien} (${new Date(sk.thoi_gian_bat_dau).toLocaleString('vi-VN')} - ${new Date(sk.thoi_gian_ket_thuc).toLocaleString('vi-VN')})`
                    ).join('\n')

                    const proceed = confirm(
                        '⚠️ CẢNH BÁO - Có sự kiện khác tổ chức cùng thời gian và địa điểm:\n\n' +
                        collisionInfo +
                        '\n\nBạn có chắc chắn muốn tiếp tục không?'
                    )

                    if (proceed) {
                        document.getElementById('suKienForm').submit()
                    }
                } else {
                    // Không có xung đột, tiếp tục submit
                    document.getElementById('suKienForm').submit()
                }
            } catch (error) {
                checkBtn.disabled = false
                checkBtn.innerHTML = 'Tạo sự kiện'
                console.error('Error checking collision:', error)
                // Nếu có lỗi API, vẫn cho phép submit
                document.getElementById('suKienForm').submit()
            }
        }
    })
</script>

@endsection