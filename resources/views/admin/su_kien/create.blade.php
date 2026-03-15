@extends('admin.layout')

@section('title','Tạo sự kiện')
@section('page-title','Tạo sự kiện')

@section('styles')

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<style>

:root{
--primary:#2563eb;
--bg:#f8fafc;
--card:#ffffff;
--border:#e5e7eb;
--text:#111827;
--muted:#6b7280;
}

body{
background:var(--bg);
}

.page-wrapper{
max-width:1400px;
margin:auto;
}

/* Thanh hành động */

.action-toolbar{
background:white;
border-bottom:1px solid var(--border);
padding:16px 24px;
margin-bottom:24px;
}

/* Card */

.card-ui{
background:white;
border:1px solid var(--border);
border-radius:12px;
padding:24px;
}

.card-title{
font-size:16px;
font-weight:600;
margin-bottom:20px;
}

/* Input */

.form-control,
.form-select{
border-radius:10px;
border:1px solid var(--border);
padding:10px 12px;
}

.form-label{
font-size:12px;
text-transform:uppercase;
letter-spacing:0.04em;
color:var(--muted);
}

/* Layout builder */

.sortable-item{
display:flex;
align-items:center;
justify-content:space-between;
border:1px solid var(--border);
border-radius:10px;
padding:10px 14px;
background:white;
}

.drag-handle{
cursor:grab;
color:#9ca3af;
}

/* Upload */

.upload-box{
border:2px dashed var(--border);
border-radius:12px;
padding:40px;
text-align:center;
cursor:pointer;
}

.upload-box:hover{
border-color:var(--primary);
}

#editor-container{
min-height:240px;
}

</style>

@endsection

@section('content')

<form method="POST"
action="{{ route('admin.su-kien.store') }}"
enctype="multipart/form-data">

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

<button class="btn btn-primary">
Lưu sự kiện
</button>

</div>

</div>

<div class="row g-4">

<!-- CỘT TRÁI -->

<div class="col-lg-8">

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
Thư viện ảnh
</label>

<div
class="upload-box"
onclick="galleryInput.click()">

<i class="bi bi-images fs-1 text-muted"></i>

<p class="mt-2 mb-0">
Thêm ảnh
</p>

<input
type="file"
name="gallery_files[]"
id="galleryInput"
multiple
class="d-none">

</div>

</div>

</div>

</div>

</div>

<!-- SIDEBAR -->

<div class="col-lg-4">

<div class="card-ui">

<div class="card-title">
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

const quill =
new Quill('#editor-container',{
theme:'snow',
placeholder:'Viết nội dung sự kiện tại đây...'
})

quill.on('text-change',()=>{

document.getElementById('mo_ta_chi_tiet').value
= quill.root.innerHTML

})

const sortable =
Sortable.create(
document.getElementById('layout-sortable'),
{
animation:150,
handle:'.drag-handle',
onEnd:updateLayout
}
)

function updateLayout(){

const order =
sortable.toArray()

document.getElementById('bo_cuc_order')
.value =
JSON.stringify(order)

}

</script>

@endsection