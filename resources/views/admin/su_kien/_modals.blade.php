<!-- Modal Thêm Loại Sự Kiện -->
<div class="modal fade" id="addLoaiModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-tags-fill text-success"></i> Thêm loại sự kiện nhanh</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên loại sự kiện <span class="text-danger">*</span></label>
                    <input type="text" id="new_loai_ten" class="form-control" placeholder="VD: Hội thảo công nghệ...">
                    <div class="invalid-feedback" id="loai_err"></div>
                </div>
                <div class="mb-0">
                    <label class="form-label fw-bold">Mô tả loại</label>
                    <textarea id="new_loai_mota" class="form-control" rows="3" placeholder="Mô tả tóm tắt ý nghĩa của loại sự kiện này..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary px-4" id="btnSaveLoai" onclick="saveLoaiSuKien()">Lưu Loại</button>
            </div>
        </div>
    </div>
</div>

<style>
.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
.tpl-item:hover { transform: translateY(-3px); border-color: var(--primary) !important; }
</style>
