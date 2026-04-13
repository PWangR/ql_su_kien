<?php
$create = file_get_contents('d:/laragon/www/ql_su_kien/resources/views/admin/su_kien/create.blade.php');

// Replace create strings with edit strings
$newEdit = str_replace(
    ['Tạo sự kiện', "route('admin.su-kien.store')", "route('admin.su-kien.store')", '<form method="POST" action="{{ route(\'admin.su-kien.store\') }}" enctype="multipart/form-data" id="suKienForm">', 'Khởi tạo sự kiện', 'Tạo Khởi Kiện & Bố Cục'],
    ['Chỉnh sửa sự kiện', "route('admin.su-kien.update', \$suKien->ma_su_kien)", "route('admin.su-kien.update', \$suKien->ma_su_kien)", "<form method=\"POST\" action=\"{{ route('admin.su-kien.update', \$suKien->ma_su_kien) }}\" enctype=\"multipart/form-data\" id=\"suKienForm\">\n        @method('PUT')", 'Cập nhật sự kiện', 'Chỉnh sửa Sự Kiện & Bố Cục'],
    $create
);

// We need to insert existing gallery in the Media section
$galleryHtml = "
                            <div class=\"span-12\">
                                <hr class=\"section-rule\">
                                <label class=\"form-label\">Gallery hiện có</label>
                                <div class=\"existing-gallery\" id=\"existingGallery\" style=\"display:grid; grid-template-columns: repeat(auto-fill, minmax(132px, 1fr)); gap: 14px; margin-bottom: 20px;\">
                                    @forelse (\$suKien->media as \$img)
                                        <div class=\"existing-item\" id=\"media-item-{{ \$img->ma_phuong_tien }}\" style=\"position:relative;border-radius:18px;overflow:hidden;border:1px solid #dbe5f2;background:#fff\">
                                            <img src=\"{{ asset('storage/' . \$img->duong_dan_tep) }}\" alt=\"{{ \$img->ten_tep }}\" style=\"width:100%;height:118px;object-fit:cover;display:block\">
                                            <button type=\"button\" class=\"existing-remove\" data-existing-media=\"{{ \$img->ma_phuong_tien }}\" style=\"position:absolute;top:10px;right:10px;width:28px;height:28px;border:none;border-radius:999px;background:rgba(15,23,42,.82);color:#fff\"><i class=\"bi bi-trash3\"></i></button>
                                            <div class=\"existing-caption\" style=\"padding:10px 12px 12px;font-size:12px;color:#475569;line-height:1.5;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;\">{{ \$img->ten_tep ?: basename(\$img->duong_dan_tep) }}</div>
                                        </div>
                                    @empty
                                        <div class=\"empty-layout\" id=\"existingGalleryEmpty\" style=\"grid-column: 1 / -1; padding: 20px; text-align: center; border: 1px dashed var(--border); border-radius: var(--border-radius); color: var(--text-muted);\">Sự kiện này chưa có ảnh gallery nào.</div>
                                    @endforelse
                                </div>
                            </div>";

$newEdit = str_replace('<hr class="section-rule">
                                <label class="form-label">Bộ từ Thư viện Media Lưu Trữ sẵn</label>', $galleryHtml . "\n                                <label class=\"form-label\">Bộ từ Thư viện Media Lưu Trữ sẵn</label>", $newEdit);

// Replace defaultLayout old('bo_cuc', [...]) with $suKien->bo_cuc
$newEdit = preg_replace('/old\(\'bo_cuc\',\s*\[.*?\]\)/', "old('bo_cuc', \$suKien->bo_cuc ?: ['banner', 'header', 'info', 'description', 'gallery'])", $newEdit);

// Adjust values to $suKien values
// For input fields
$newEdit = preg_replace('/value="\{\{\s*old\(\'(.*?)\'\)\s*\}\}"/', 'value="{{ old(\'$1\', \$suKien->$1) }}"', $newEdit);
$newEdit = preg_replace('/value="\{\{\s*old\(\'(.*?)\',\s*(.*?)\)\s*\}\}"/', 'value="{{ old(\'$1\', \$suKien->$1) }}"', $newEdit); 

// For mo_ta_chi_tiet specifically which uses old('mo_ta_chi_tiet') without quotes sometimes if we didn't catch it
$newEdit = str_replace("old('mo_ta_chi_tiet')", "old('mo_ta_chi_tiet', \$suKien->mo_ta_chi_tiet)", $newEdit);

// Select options
$newEdit = str_replace("@selected(old('ma_loai_su_kien') == \$item->ma_loai_su_kien)", "@selected(old('ma_loai_su_kien', \$suKien->ma_loai_su_kien) == \$item->ma_loai_su_kien)", $newEdit);

// For thoi_gian format
$newEdit = str_replace("old('thoi_gian_bat_dau', \$suKien->thoi_gian_bat_dau)", "old('thoi_gian_bat_dau', \$suKien->thoi_gian_bat_dau?->format('Y-m-d\TH:i'))", $newEdit);
$newEdit = str_replace("old('thoi_gian_ket_thuc', \$suKien->thoi_gian_ket_thuc)", "old('thoi_gian_ket_thuc', \$suKien->thoi_gian_ket_thuc?->format('Y-m-d\TH:i'))", $newEdit);

// Add eventId in script
$newEdit = str_replace("const initialDescription = @json(old('mo_ta_chi_tiet', \$suKien->mo_ta_chi_tiet));", "const initialDescription = @json(old('mo_ta_chi_tiet', \$suKien->mo_ta_chi_tiet));\n        const initialCover = @json(\$suKien->anh_su_kien ? asset('storage/' . \$suKien->anh_su_kien) : null);\n        const eventId = @json(\$suKien->ma_su_kien);", $newEdit);

// Add initialization for cover
$newEdit = str_replace("renderLayout();", "renderLayout();\n        if (initialCover) {\n            const img = document.getElementById('coverPreviewImage');\n            if(img) { img.src = initialCover; document.getElementById('coverPreview').style.display = 'block'; document.getElementById('coverSurface').style.display = 'none'; }\n        }", $newEdit);

// Modify check-collision body to append bo_qua_id
$newEdit = str_replace("body: JSON.stringify({ thoi_gian_bat_dau: start, thoi_gian_ket_thuc: end, dia_diem: location })", "body: JSON.stringify({ thoi_gian_bat_dau: start, thoi_gian_ket_thuc: end, dia_diem: location, bo_qua_id: eventId })", $newEdit);

// Add code to delete existing gallery
$jsDelete = "
            const existingRemove = e.target.closest('[data-existing-media]');
            if (existingRemove) {
                const mediaId = existingRemove.dataset.existingMedia;
                if (!confirm('Xóa ảnh này khỏi gallery hiện tại?')) return;
                try {
                    fetch(`/admin/su-kien/xoa-anh/\${mediaId}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content }
                    }).then(res => {
                        if (res.ok) {
                            document.getElementById(`media-item-\${mediaId}`)?.remove();
                            if (!document.querySelector('#existingGallery .existing-item')) {
                                document.getElementById('existingGallery').innerHTML = '<div class=\"empty-layout\" id=\"existingGalleryEmpty\" style=\"grid-column: 1 / -1; padding: 20px; text-align: center; border: 1px dashed var(--border); border-radius: var(--border-radius); color: var(--text-muted);\">Sự kiện này chưa có ảnh gallery nào.</div>';
                            }
                        }
                    });
                } catch (error) { console.error(error) }
                return;
            }
";

$newEdit = str_replace("const mediaCard = e.target.closest('[data-media-id]');", $jsDelete . "            const mediaCard = e.target.closest('[data-media-id]');", $newEdit);

// Add Trang Thai field to edit page
$trangThaiHtml = "
                            <div class=\"span-6\">
                                <label class=\"form-label\" for=\"trang_thai\">Trạng thái</label>
                                <select class=\"form-control @error('trang_thai') border-danger @enderror\" id=\"trang_thai\" name=\"trang_thai\">
                                    <option value=\"sap_to_chuc\" @selected(old('trang_thai', \$suKien->trang_thai) === 'sap_to_chuc')>Sắp tổ chức</option>
                                    <option value=\"dang_dien_ra\" @selected(old('trang_thai', \$suKien->trang_thai) === 'dang_dien_ra')>Đang diễn ra</option>
                                    <option value=\"da_ket_thuc\" @selected(old('trang_thai', \$suKien->trang_thai) === 'da_ket_thuc')>Đã kết thúc</option>
                                    <option value=\"huy\" @selected(old('trang_thai', \$suKien->trang_thai) === 'huy')>Đã hủy</option>
                                </select>
                                @error('trang_thai')
                                    <span class=\"text-danger\" style=\"font-size: 0.8rem; margin-top: 4px; display: block;\">{{ \$message }}</span>
                                @enderror
                            </div>
";

$newEdit = str_replace('<div class="span-6">
                                <label class="form-label" for="ma_loai_su_kien">', $trangThaiHtml . '                            <div class="span-6">
                                <label class="form-label" for="ma_loai_su_kien">', $newEdit);

// Wait, the title was manually updated
$newEdit = str_replace("@section('title', 'Tạo sự kiện')", "@section('title', 'Chỉnh sửa sự kiện')", $newEdit);
$newEdit = str_replace("@section('page-title', 'Tạo sự kiện')", "@section('page-title', 'Chỉnh sửa sự kiện')", $newEdit);

// Finally, update the button in the collision check dialog
$newEdit = str_replace("Khởi tạo sự kiện", "Cập nhật sự kiện", $newEdit);
$newEdit = str_replace("force_create", "force_update", $newEdit);

file_put_contents('d:/laragon/www/ql_su_kien/resources/views/admin/su_kien/edit.blade.php', $newEdit);
echo "Done syncing.\n";
