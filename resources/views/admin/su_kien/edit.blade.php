@extends('admin.layout')

@section('title','Sửa sự kiện')
@section('page-title','Chỉnh sửa sự kiện')

@section('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<style>
    body {
        background: #f1f5f9;
    }

    .card-premium {
        background: white;
        border-radius: 18px;
        padding: 25px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        margin-bottom: 25px;
    }

    .section-title {
        font-weight: 700;
        margin-bottom: 15px;
        font-size: 18px;
    }

    .image-preview {
        height: 220px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
    }

    .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .gallery-item {
        width: 70px;
        height: 70px;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .remove-btn {
        position: absolute;
        top: 2px;
        right: 2px;
        background: red;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 10px;
    }

    #layout-sortable {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .sortable-item {
        padding: 12px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        cursor: grab;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .drop-zone {
        border: 2px dashed #cbd5e1;
        padding: 35px;
        text-align: center;
        border-radius: 16px;
        background: #f8fafc;
        transition: 0.2s;
    }

    .drop-zone.drag {
        border-color: #3b82f6;
        background: #eff6ff;
    }
</style>
@endsection

@section('content')

<form method="POST"
    action="{{ route('admin.su-kien.update',$suKien->ma_su_kien) }}"
    enctype="multipart/form-data"
    id="mainForm">

    @csrf
    @method('PUT')

    <div class="d-flex justify-content-between mb-4">

        <h4 class="fw-bold">
            Chỉnh sửa sự kiện #{{ $suKien->ma_su_kien }}
        </h4>

        <div>

            <a href="{{ route('admin.su-kien.index') }}"
                class="btn btn-light">
                Quay lại
            </a>

            <button class="btn btn-primary">
                Cập nhật
            </button>

            <button type="button"
                class="btn btn-dark"
                onclick="previewEvent()">
                Xem trước
            </button>

        </div>

    </div>


    <div class="row">

        <div class="col-lg-8">

            {{-- THÔNG TIN --}}
            <div class="card-premium">

                <div class="section-title">
                    Thông tin cơ bản
                </div>

                <input type="text"
                    class="form-control mb-3"
                    name="ten_su_kien"
                    value="{{ old('ten_su_kien',$suKien->ten_su_kien) }}"
                    placeholder="Tên sự kiện"
                    required>


                <select class="form-select mb-3"
                    name="ma_loai_su_kien">

                    @foreach($loai as $l)

                    <option value="{{ $l->ma_loai_su_kien }}"
                        {{ $suKien->ma_loai_su_kien==$l->ma_loai_su_kien?'selected':'' }}>
                        {{ $l->ten_loai }}
                    </option>

                    @endforeach

                </select>

                <input type="text"
                    class="form-control mb-3"
                    name="dia_diem"
                    value="{{ old('dia_diem',$suKien->dia_diem) }}"
                    placeholder="Địa điểm (N.v: Lab A5, Phòng 101)"
                    required>

                <div class="row">
                    <div class="col-6">
                        <input type="datetime-local"
                            class="form-control mb-3"
                            name="thoi_gian_bat_dau"
                            value="{{ old('thoi_gian_bat_dau', $suKien->thoi_gian_bat_dau?->format('Y-m-d\TH:i')) }}"
                            required>
                    </div>
                    <div class="col-6">
                        <input type="datetime-local"
                            class="form-control mb-3"
                            name="thoi_gian_ket_thuc"
                            value="{{ old('thoi_gian_ket_thuc', $suKien->thoi_gian_ket_thuc?->format('Y-m-d\TH:i')) }}"
                            required>
                    </div>
                </div>


                {{-- EDITOR --}}
                <div class="card-premium">

                    <div class="section-title">
                        Nội dung sự kiện
                    </div>

                    <input type="hidden"
                        name="mo_ta_chi_tiet"
                        id="mo_ta_chi_tiet"
                        value="{{ old('mo_ta_chi_tiet',$suKien->mo_ta_chi_tiet) }}">

                    <div id="editor-container"
                        style="height:350px">
                        {!! $suKien->mo_ta_chi_tiet !!}
                    </div>

                </div>


                {{-- MEDIA --}}
                <div class="card-premium">

                    <div class="section-title">
                        Hình ảnh
                    </div>

                    <label>Ảnh banner</label>

                    <div class="image-preview mb-2">

                        @if($suKien->anh_su_kien)

                        <img id="previewImg"
                            src="{{ asset('storage/'.$suKien->anh_su_kien) }}">

                        @else

                        <span class="text-muted">
                            Chưa có ảnh
                        </span>

                        @endif

                    </div>

                    <input type="file"
                        name="anh_su_kien"
                        id="imgInput"
                        class="form-control mb-4"
                        accept="image/*">


                    <label>Gallery</label>

                    <div id="gallery-preview"
                        class="d-flex gap-2 flex-wrap mb-3">

                        @foreach($suKien->media as $img)

                        <div class="gallery-item"
                            id="media-item-{{ $img->ma_phuong_tien }}">

                            <img src="{{ asset('storage/'.$img->duong_dan_tep) }}">

                            <button type="button"
                                class="remove-btn"
                                onclick="removeGalleryImage({{ $img->ma_phuong_tien }})">
                                x
                            </button>

                        </div>

                        @endforeach

                    </div>


                    <div id="drop-zone" class="drop-zone mb-3">
                        Kéo ảnh vào đây để upload
                    </div>

                    <input type="file"
                        name="gallery_files[]"
                        multiple
                        id="galleryInput"
                        class="form-control"
                        accept="image/*">

                </div>


                {{-- LAYOUT --}}
                <div class="card-premium">

                    <div class="section-title">
                        Bố cục hiển thị
                    </div>

                    <div id="layout-sortable">

                        @php

                        $components=[
                        'banner'=>'Banner',
                        'header'=>'Tiêu đề',
                        'info'=>'Thông tin',
                        'description'=>'Mô tả',
                        'gallery'=>'Gallery'
                        ];

                        @endphp

                        @foreach($components as $key=>$name)

                        <div class="sortable-item"
                            data-id="{{ $key }}">

                            <span>
                                {{ $name }}
                            </span>

                            <input type="checkbox"
                                name="bo_cuc[]"
                                value="{{ $key }}"
                                checked>

                        </div>

                        @endforeach

                    </div>

                    <input type="hidden"
                        name="bo_cuc_order"
                        id="bo_cuc_order">

                </div>


                {{-- TIME --}}
                <div class="card-premium">

                    <div class="section-title">
                        Thời gian
                    </div>

                    <input type="datetime-local"
                        class="form-control mb-3"
                        name="thoi_gian_bat_dau"
                        value="{{ $suKien->thoi_gian_bat_dau->format('Y-m-d\TH:i') }}">


                    <input type="datetime-local"
                        class="form-control"
                        name="thoi_gian_ket_thuc"
                        value="{{ $suKien->thoi_gian_ket_thuc->format('Y-m-d\TH:i') }}">

                </div>


            </div>


            <div class="col-lg-4">


                <div class="card-premium">

                    <div class="section-title">
                        Cấu hình
                    </div>

                    <select class="form-select mb-3"
                        name="trang_thai">

                        <option value="sap_to_chuc">Sắp tổ chức</option>
                        <option value="dang_dien_ra">Đang diễn ra</option>
                        <option value="da_ket_thuc">Đã kết thúc</option>

                    </select>


                    <input type="number"
                        class="form-control mb-3"
                        name="so_luong_toi_da"
                        value="{{ $suKien->so_luong_toi_da }}"
                        placeholder="Số lượng tối đa">


                    <input type="number"
                        class="form-control"
                        name="diem_cong"
                        value="{{ $suKien->diem_cong }}"
                        placeholder="Điểm cộng">


                </div>


            </div>

        </div>

</form>


{{-- PREVIEW MODAL --}}

<div class="modal fade" id="previewModal">

    <div class="modal-dialog modal-xl">

        <div class="modal-content">

            <div class="modal-header">

                <h5>Xem trước sự kiện</h5>

                <button class="btn-close"
                    data-bs-dismiss="modal"></button>

            </div>

            <div class="modal-body">

                <div id="preview-content"></div>

            </div>

        </div>

    </div>

</div>

@endsection



@section('scripts')

<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
    const quill = new Quill('#editor-container', {
        theme: 'snow'
    });

    quill.on('text-change', () => {

        document.getElementById('mo_ta_chi_tiet').value = quill.root.innerHTML;

    });


    // preview banner
    imgInput.onchange = e => {

        const reader = new FileReader();

        reader.onload = x => {
            previewImg.src = x.target.result
        }

        reader.readAsDataURL(e.target.files[0]);

    }


    // sortable layout

    const sortable = Sortable.create(
        document.getElementById('layout-sortable'), {

            animation: 150,

            onEnd: () => {
                document.getElementById('bo_cuc_order')
                    .value = JSON.stringify(sortable.toArray())
            }

        });


    // preview event

    function previewEvent() {

        const title = document.querySelector('[name=ten_su_kien]').value;

        const desc = quill.root.innerHTML;

        const html = `

<h2>${title}</h2>

<div>${desc}</div>

`;

        document.getElementById('preview-content').innerHTML = html;

        new bootstrap.Modal('#previewModal').show();

    }


    // drag drop upload

    const dropZone = document.getElementById('drop-zone');

    dropZone.addEventListener('dragover', e => {
        e.preventDefault();
        dropZone.classList.add('drag');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('drag');
    });

    dropZone.addEventListener('drop', e => {
        e.preventDefault();

        dropZone.classList.remove('drag');

        const files = e.dataTransfer.files;

        document.getElementById('galleryInput').files = files;

    });


    // remove gallery

    function removeGalleryImage(id) {

        if (!confirm('Xóa ảnh?')) return;

        fetch(`/admin/su-kien/xoa-anh/${id}`, {

            method: 'DELETE',

            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }

        }).then(() => {

            document.getElementById('media-item-' + id).remove()

        })

    }
</script>

@endsection