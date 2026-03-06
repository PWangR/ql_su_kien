<!-- Modal Chọn Mẫu -->
<div class="modal fade" id="templateModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-text text-primary"></i> Mẫu bài đăng có sẵn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="input-group mb-4 shadow-sm rounded">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" id="tplSearch" class="form-control border-start-0" placeholder="Tìm kiếm mẫu theo tên hoặc nội dung...">
                </div>
                <div style="max-height:450px; overflow-y:auto; display:flex; flex-direction:column; gap:12px;" id="tplList" class="custom-scrollbar">
                    @foreach($templates as $tpl)
                    <div class="tpl-item card border-0 shadow-sm" style="cursor:pointer; transition: transform 0.2s;" 
                        onclick="selectTemplate(`{{ e($tpl->noi_dung_mau ?? $tpl->noi_dung) }}`, `{{ e($tpl->dia_diem) }}`, '{{ $tpl->so_luong_toi_da }}', '{{ $tpl->diem_cong }}', '{{ $tpl->anh_su_kien }}')">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold text-primary mb-0">{{ $tpl->ten_mau }}</h6>
                                @if($tpl->loaiSuKien)
                                    <span class="badge bg-light text-secondary border small">{{ $tpl->loaiSuKien->ten_loai }}</span>
                                @endif
                            </div>
                            <p class="text-muted small mb-2 text-truncate-2">{{ Str::limit(strip_tags($tpl->noi_dung ?? $tpl->noi_dung_mau), 120) }}</p>
                            <div class="d-flex gap-3 mt-2">
                                @if($tpl->dia_diem)<span class="badge-input"><i class="bi bi-geo-alt"></i> Địa điểm</span>@endif
                                @if($tpl->diem_cong > 0)<span class="badge-input"><i class="bi bi-star"></i> +{{ $tpl->diem_cong }} điểm</span>@endif
                                @if($tpl->anh_su_kien)<span class="badge-input"><i class="bi bi-image"></i> Có ảnh bìa</span>@endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thư Viện Media -->
<div class="modal fade" id="mediaModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-images text-success"></i> Thư viện đa phương tiện</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    @forelse($mediaKho as $m)
                    <div class="col-md-2 col-sm-4 col-6">
                        <div class="media-item card border-2 shadow-none" style="cursor:pointer; overflow:hidden;" onclick="selectMedia('{{ $m->duong_dan_tep }}', this)">
                            <img src="{{ asset('storage/'.$m->duong_dan_tep) }}" style="width:100%; height:120px; object-fit:cover;">
                            <div class="p-2 bg-light border-top">
                                <div class="small fw-600 text-truncate">{{ $m->ten_tep }}</div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-camera-video fs-1 text-muted opacity-25"></i>
                        <p class="mt-2 text-muted">Chưa có tệp nào trong thư viện</p>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary border-0" data-bs-dismiss="modal" style="border-radius:8px;">Đóng</button>
            </div>
        </div>
    </div>
</div>

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
