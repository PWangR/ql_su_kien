@php
    $eventModel = $eventModel ?? null;
    $selectedTemplate = $selectedTemplate ?? null;
    $effectiveModuleSchema = old('module_schema_json')
        ? \App\Support\EventTemplateSupport::normalizeTemplateModules(old('module_schema_json'))
        : $moduleSchema;
    $eventModulesById = collect($effectiveModuleSchema)->keyBy('id');

    $fieldValue = function (string $name, $default = null) {
        return old($name, $default);
    };

    $renderModuleCard = function (array $module) use ($eventModel, $eventModulesById, $moduleCatalog) {
        $moduleContent = $eventModulesById[$module['id']]['content'] ?? [];
        $moduleMeta = $moduleCatalog[$module['type']];
        $moduleTitle = $module['title'] ?: $moduleMeta['label'];
        ob_start();
        ?>
        <div class="card mb-lg module-card-editor"
            data-module-card="<?= e($module['id']) ?>"
            data-module-id="<?= e($module['id']) ?>"
            data-module-type="<?= e($module['type']) ?>">
            <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;gap:var(--space-sm);">
                <div class="card-title">
                    <i class="bi <?= e($moduleMeta['icon']) ?>"></i>
                    <span data-module-card-title><?= e($moduleTitle) ?></span>
                </div>
                <div class="text-muted text-sm"><?= e($moduleMeta['label']) ?></div>
            </div>
            <div class="card-body">
                <?php if ($module['type'] === 'banner'): ?>
                    <?php $existingImage = $moduleContent['image_path'] ?? null; ?>
                    <div class="form-group">
                        <label class="form-label" data-module-label="caption_label"><?= e($module['settings']['caption_label'] ?? 'Chú thích ảnh') ?></label>
                        <input class="form-control"
                            name="<?= e("module_content[{$module['id']}][caption]") ?>"
                            value="<?= e(old("module_content.{$module['id']}.caption", $moduleContent['caption'] ?? '')) ?>"
                            placeholder="Ví dụ: Banner hero dành cho landing page">
                    </div>
                    <?php if ($existingImage): ?>
                        <div class="form-group">
                            <label style="display:flex;align-items:center;gap:8px;">
                                <input type="checkbox" name="<?= e("module_content[{$module['id']}][keep_existing]") ?>" value="1" checked>
                                Giữ ảnh hiện tại
                            </label>
                            <div style="margin-top:10px;">
                                <img src="<?= e(asset('storage/' . $existingImage)) ?>" alt="<?= e($moduleTitle) ?>" style="max-width:280px;border:1px solid var(--border);border-radius:var(--border-radius);">
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label class="form-label">Ảnh cho module này</label>
                        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                            <input class="form-control" type="file" name="<?= e("module_banner[{$module['id']}]") ?>" accept="image/*" style="flex:1;min-width:0;">
                            <button type="button" class="btn btn-outline btn-sm" style="white-space:nowrap;flex-shrink:0;"
                                onclick="openMediaPicker('banner','<?= e($module['id']) ?>','single')">
                                <i class="bi bi-images"></i> Chọn từ thư viện
                            </button>
                        </div>
                        <input type="hidden" name="<?= e("module_banner_media_path[{$module['id']}]") ?>" id="banner-media-path-<?= e($module['id']) ?>">
                        <div id="banner-media-preview-<?= e($module['id']) ?>" style="margin-top:8px;display:none;">
                            <div style="position:relative;display:inline-block;">
                                <img id="banner-media-img-<?= e($module['id']) ?>" src="" alt="preview" style="max-width:220px;border:1px solid var(--border);border-radius:var(--border-radius);">
                                <button type="button" onclick="clearBannerMedia('<?= e($module['id']) ?>')"
                                    style="position:absolute;top:4px;right:4px;background:rgba(0,0,0,.55);color:#fff;border:none;border-radius:50%;width:22px;height:22px;cursor:pointer;font-size:0.8rem;line-height:1;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($module['type'] === 'header'): ?>
                    <div class="input-grid">
                        <div class="form-group">
                            <label class="form-label">Tiêu đề hiển thị</label>
                            <input class="form-control"
                                name="<?= e("module_content[{$module['id']}][title]") ?>"
                                value="<?= e(old("module_content.{$module['id']}.title", $moduleContent['title'] ?? ($eventModel->ten_su_kien ?? ''))) ?>"
                                placeholder="Ví dụ: Ngày hội công nghệ sinh viên 2026">
                        </div>
                        <div class="form-group">
                            <label class="form-label" data-module-label="badge_label"><?= e($module['settings']['badge_label'] ?? 'Badge') ?></label>
                            <input class="form-control"
                                name="<?= e("module_content[{$module['id']}][badge]") ?>"
                                value="<?= e(old("module_content.{$module['id']}.badge", $moduleContent['badge'] ?? '')) ?>"
                                placeholder="Ví dụ: Sự kiện nổi bật">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" data-module-label="subtitle_label"><?= e($module['settings']['subtitle_label'] ?? 'Phụ đề') ?></label>
                        <textarea class="form-control" rows="3"
                            name="<?= e("module_content[{$module['id']}][subtitle]") ?>"
                            placeholder="Ví dụ: Chương trình dành cho sinh viên năm nhất..."><?= e(old("module_content.{$module['id']}.subtitle", $moduleContent['subtitle'] ?? '')) ?></textarea>
                    </div>
                <?php endif; ?>

                <?php if ($module['type'] === 'info'): ?>
                    <?php
                        $selectedItems = old("module_content.{$module['id']}.items", $moduleContent['items'] ?? $module['settings']['items'] ?? []);
                    ?>
                    <div class="form-group">
                        <label class="form-label">Thông tin sẽ hiển thị trong khối này</label>
                        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:10px;">
                            <?php foreach(\App\Support\EventTemplateSupport::infoFieldCatalog() as $key => $label): ?>
                                <label style="display:inline-flex;align-items:center;gap:6px;">
                                    <input type="checkbox" name="<?= e("module_content[{$module['id']}][items][]") ?>" value="<?= e($key) ?>"
                                        <?= in_array($key, $selectedItems, true) ? 'checked' : '' ?>>
                                    <?= e($label) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ghi chú thêm trong khối thông tin</label>
                        <textarea class="form-control" rows="3"
                            name="<?= e("module_content[{$module['id']}][custom_note]") ?>"
                            placeholder="Ví dụ: Sinh viên mang theo thẻ sinh viên khi check-in."><?= e(old("module_content.{$module['id']}.custom_note", $moduleContent['custom_note'] ?? '')) ?></textarea>
                    </div>
                <?php endif; ?>

                <?php if ($module['type'] === 'description'): ?>
                    <div class="form-group">
                        <label class="form-label">Tiêu đề khối</label>
                        <input class="form-control"
                            name="<?= e("module_content[{$module['id']}][heading]") ?>"
                            value="<?= e(old("module_content.{$module['id']}.heading", $moduleContent['heading'] ?? $moduleTitle)) ?>"
                            placeholder="Ví dụ: Nội dung chương trình">
                    </div>
                    <div class="form-group">
                        <label class="form-label" data-module-label="body_label"><?= e($module['settings']['body_label'] ?? 'Nội dung') ?></label>
                        <textarea class="form-control" rows="8"
                            name="<?= e("module_content[{$module['id']}][body]") ?>"
                            placeholder="Nhập nội dung cho module này..."><?= e(old("module_content.{$module['id']}.body", $moduleContent['body'] ?? '')) ?></textarea>
                    </div>
                <?php endif; ?>

                <?php if ($module['type'] === 'gallery'): ?>
                    <?php $existingImages = old("module_content.{$module['id']}.existing_images", $moduleContent['images'] ?? []); ?>
                    <?php if (!empty($module['settings']['hint'])): ?>
                        <div class="text-muted" data-module-label="hint" style="margin-bottom:var(--space-sm);"><?= e($module['settings']['hint']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($moduleContent['images'])): ?>
                        <div class="form-group">
                            <label class="form-label">Ảnh hiện có trong khối này</label>
                            <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(140px, 1fr));gap:12px;">
                                <?php foreach($moduleContent['images'] as $imagePath): ?>
                                    <label style="border:1px solid var(--border);border-radius:var(--border-radius);padding:10px;display:block;">
                                        <img src="<?= e(asset('storage/' . $imagePath)) ?>" alt="gallery" style="width:100%;height:110px;object-fit:cover;border-radius:var(--border-radius);margin-bottom:8px;">
                                        <span style="display:flex;align-items:center;gap:8px;font-size:.85rem;">
                                            <input type="checkbox" name="<?= e("module_content[{$module['id']}][existing_images][]") ?>" value="<?= e($imagePath) ?>" <?= in_array($imagePath, $existingImages, true) ? 'checked' : '' ?>>
                                            Giữ ảnh này
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label class="form-label">Tải thêm ảnh cho khối này</label>
                        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                            <input class="form-control" type="file" name="<?= e("module_gallery[{$module['id']}][]") ?>" accept="image/*" multiple style="flex:1;min-width:0;">
                            <button type="button" class="btn btn-outline btn-sm" style="white-space:nowrap;flex-shrink:0;"
                                onclick="openMediaPicker('gallery','<?= e($module['id']) ?>','multi')">
                                <i class="bi bi-images"></i> Chọn từ thư viện
                            </button>
                        </div>
                        <div id="gallery-media-preview-<?= e($module['id']) ?>" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:8px;"></div>
                    </div>
                <?php endif; ?>

                <?php if ($module['type'] === 'documents'): ?>
                    <?php $existingDocs = $moduleContent['items'] ?? []; ?>
                    <div class="form-group">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:8px;">
                            <label class="form-label" style="margin:0;">Đính kèm tài liệu</label>
                            <button type="button" class="btn btn-outline btn-sm"
                                onclick="openMediaPicker('documents','<?= e($module['id']) ?>','multi')">
                                <i class="bi bi-paperclip"></i> Chọn từ thư viện
                            </button>
                        </div>
                        <div id="docs-list-<?= e($module['id']) ?>" style="display:flex;flex-direction:column;gap:6px;">
                            <?php foreach($existingDocs as $doc): ?>
                                <div class="doc-item" data-doc-id="<?= e($doc['ma_phuong_tien']) ?>" style="display:flex;align-items:center;gap:8px;padding:8px 10px;border:1px solid var(--border);border-radius:var(--border-radius);background:var(--bg);">
                                    <i class="bi bi-file-earmark-text" style="color:var(--text-muted);flex-shrink:0;"></i>
                                    <span style="flex:1;font-size:.85rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e($doc['ten_tep']) ?></span>
                                    <input type="hidden" name="<?= e("module_content[{$module['id']}][media_ids][]") ?>" value="<?= e($doc['ma_phuong_tien']) ?>">
                                    <button type="button" onclick="this.closest('.doc-item').remove()" style="background:none;border:none;color:var(--text-muted);cursor:pointer;flex-shrink:0;font-size:1rem;" title="Xóa">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if(empty($existingDocs)): ?>
                        <div class="text-muted" style="font-size:.8rem;margin-top:4px;">Chưa có tài liệu nào được đính kèm.</div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    };
@endphp

<form method="POST" action="{{ $submitRoute }}" enctype="multipart/form-data" id="suKienForm">
    @csrf
    @if(!empty($httpMethod))
        @method($httpMethod)
    @endif

    <input type="hidden" name="module_schema_json" id="moduleSchemaJson" value='@json($effectiveModuleSchema)'>

    <div class="create-event">
        @if ($errors->any())
            <div class="alert alert-error mb-lg">
                <strong>Vui lòng kiểm tra lại dữ liệu bài đăng.</strong>
                <ul class="mb-0 mt-sm pl-md">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card mb-lg">
            <div class="card-body" style="display:flex;justify-content:space-between;gap:var(--space-lg);align-items:flex-start;flex-wrap:wrap;">
                <div>
                    <h2 style="margin-bottom:8px;">{{ $mode === 'create' ? 'Bước 2: nhập nội dung theo mẫu' : 'Chỉnh sửa bài đăng theo module' }}</h2>
                    <p class="text-muted" style="max-width:780px;">
                        @if($selectedTemplate)
                            Bạn đang dùng mẫu <strong>{{ $selectedTemplate->ten_mau }}</strong>. Mẫu chỉ là sườn ban đầu, bạn có thể chỉnh lại bố cục, thêm bớt module và đổi thứ tự ngay trong trang tạo bài đăng.
                        @else
                            Bài đăng này đang dùng cấu trúc module hiện có. Bạn có thể cập nhật lại nội dung từng khối bên dưới.
                        @endif
                    </p>
                    @if($selectedTemplate && $selectedTemplate->noi_dung)
                        <div class="text-sm text-muted" style="margin-top:var(--space-sm);line-height:1.6;">
                            Ghi chú mẫu: {{ $selectedTemplate->noi_dung }}
                        </div>
                    @endif
                </div>
                <div style="display:flex;gap:var(--space-sm);flex-wrap:wrap;">
                    @if($mode === 'create')
                        <a href="{{ route('admin.su-kien.select-template') }}" class="btn btn-outline">
                            <i class="bi bi-arrow-left"></i> Chọn lại mẫu
                        </a>
                    @endif
                    <a href="{{ route('admin.templates.index') }}" class="btn btn-outline">
                        <i class="bi bi-sliders"></i> Quản lý mẫu
                    </a>
                </div>
            </div>
        </div>

        <div class="card mb-lg">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-info-circle"></i> Thông tin chung của sự kiện</div>
            </div>
            <div class="card-body">
                <div class="input-grid">
                    <div class="form-group">
                        <label class="form-label" for="ten_su_kien">Tên sự kiện</label>
                        <input class="form-control" id="ten_su_kien" name="ten_su_kien" required
                            value="{{ $fieldValue('ten_su_kien', $eventModel->ten_su_kien ?? '') }}"
                            placeholder="Ví dụ: Workshop AI thực chiến">
                    </div>

                    @if($mode === 'edit')
                        <div class="form-group">
                            <label class="form-label" for="trang_thai">Trạng thái</label>
                            <select class="form-control" name="trang_thai" id="trang_thai">
                                @foreach(['sap_to_chuc' => 'Sắp tổ chức', 'dang_dien_ra' => 'Đang diễn ra', 'da_ket_thuc' => 'Đã kết thúc', 'huy' => 'Đã hủy'] as $value => $label)
                                    <option value="{{ $value }}" @selected($fieldValue('trang_thai', $eventModel->trang_thai ?? 'sap_to_chuc') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="form-label" for="ma_loai_su_kien">Loại sự kiện</label>
                        <select class="form-control" id="ma_loai_su_kien" name="ma_loai_su_kien" required>
                            <option value="">Chọn loại sự kiện</option>
                            @foreach ($loai as $item)
                                <option value="{{ $item->ma_loai_su_kien }}"
                                    @selected($fieldValue('ma_loai_su_kien', $eventModel->ma_loai_su_kien ?? $selectedTemplate?->ma_loai_su_kien) == $item->ma_loai_su_kien)>
                                    {{ $item->ten_loai }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="dia_diem">Địa điểm</label>
                        <input class="form-control" id="dia_diem" name="dia_diem" required
                            value="{{ $fieldValue('dia_diem', $eventModel->dia_diem ?? $selectedTemplate?->dia_diem) }}"
                            placeholder="Ví dụ: Hội trường A">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="thoi_gian_bat_dau">Thời gian bắt đầu</label>
                        <input class="form-control" type="datetime-local" id="thoi_gian_bat_dau" name="thoi_gian_bat_dau" required
                            value="{{ $fieldValue('thoi_gian_bat_dau', optional($eventModel?->thoi_gian_bat_dau)->format('Y-m-d\TH:i')) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="thoi_gian_ket_thuc">Thời gian kết thúc</label>
                        <input class="form-control" type="datetime-local" id="thoi_gian_ket_thuc" name="thoi_gian_ket_thuc" required
                            value="{{ $fieldValue('thoi_gian_ket_thuc', optional($eventModel?->thoi_gian_ket_thuc)->format('Y-m-d\TH:i')) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="so_luong_toi_da">Số lượng tối đa</label>
                        <input class="form-control" type="number" min="1" id="so_luong_toi_da" name="so_luong_toi_da" required
                            value="{{ $fieldValue('so_luong_toi_da', $eventModel->so_luong_toi_da ?? $selectedTemplate?->so_luong_toi_da ?? 1) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="diem_cong">Điểm cộng</label>
                        <input class="form-control" type="number" min="0" id="diem_cong" name="diem_cong" required
                            value="{{ $fieldValue('diem_cong', $eventModel->diem_cong ?? $selectedTemplate?->diem_cong ?? 0) }}">
                    </div>
                </div>
            </div>
        </div>

        @if($mode === 'create')
            <div class="card mb-lg">
                <div class="card-header">
                    <div class="card-title"><i class="bi bi-layout-text-window"></i> Tùy chỉnh bố cục bài đăng</div>
                </div>
                <div class="card-body">
                    <div class="text-muted" style="margin-bottom:var(--space-md);max-width:860px;">
                        Bạn có thể thêm, bớt, đổi thứ tự và đổi tên từng module trước khi nhập nội dung. Mẫu chỉ đóng vai trò khởi tạo bố cục ban đầu.
                    </div>
                    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:var(--space-lg);">
                        @foreach($moduleCatalog as $type => $meta)
                            <button type="button" class="btn btn-outline btn-sm" data-add-module="{{ $type }}">
                                <i class="bi {{ $meta['icon'] }}"></i> Thêm {{ $meta['label'] }}
                            </button>
                        @endforeach
                    </div>
                    <div id="layoutEditorList" style="display:grid;gap:12px;"></div>
                </div>
            </div>
        @endif

        <div id="moduleCardsContainer">
            @foreach($effectiveModuleSchema as $module)
                {!! $renderModuleCard($module) !!}
            @endforeach
        </div>

        <div class="submit-bar">
            <span class="text-muted text-sm d-none d-lg-block">
                {{ $mode === 'create' ? 'Bạn có thể chỉnh lại bố cục trước khi lưu, không bị khóa cứng theo mẫu.' : 'Mọi cập nhật sẽ được lưu lại theo cấu trúc module hiện tại.' }}
            </span>
            <div style="display:flex;gap:var(--space-sm);">
                <a href="{{ route('admin.su-kien.index') }}" class="btn btn-outline">Quay lại</a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="bi bi-check2-circle"></i>
                    {{ $mode === 'create' ? 'Tạo bài đăng' : 'Lưu thay đổi' }}
                </button>
            </div>
        </div>
    </div>
</form>

@if($mode === 'create')
<script type="text/template" id="module-card-template-banner">
<div class="card mb-lg module-card-editor" data-module-card="__ID__" data-module-id="__ID__" data-module-type="banner">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;gap:var(--space-sm);">
        <div class="card-title">
            <i class="bi bi-card-image"></i>
            <span data-module-card-title>__TITLE__</span>
        </div>
        <div class="text-muted text-sm">Ảnh bìa</div>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label class="form-label" data-module-label="caption_label">__CAPTION_LABEL__</label>
            <input class="form-control" name="module_content[__ID__][caption]" placeholder="Ví dụ: Banner hero dành cho landing page">
        </div>
        <div class="form-group">
            <label class="form-label">Ảnh cho module này</label>
            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                <input class="form-control" type="file" name="module_banner[__ID__]" accept="image/*" style="flex:1;min-width:0;">
                <button type="button" class="btn btn-outline btn-sm" style="white-space:nowrap;flex-shrink:0;"
                    onclick="openMediaPicker('banner','__ID__','single')">
                    <i class="bi bi-images"></i> Chọn từ thư viện
                </button>
            </div>
            <input type="hidden" name="module_banner_media_path[__ID__]" id="banner-media-path-__ID__">
            <div id="banner-media-preview-__ID__" style="margin-top:8px;display:none;">
                <div style="position:relative;display:inline-block;">
                    <img id="banner-media-img-__ID__" src="" alt="preview" style="max-width:220px;border:1px solid var(--border);border-radius:var(--border-radius);">
                    <button type="button" onclick="clearBannerMedia('__ID__')"
                        style="position:absolute;top:4px;right:4px;background:rgba(0,0,0,.55);color:#fff;border:none;border-radius:50%;width:22px;height:22px;cursor:pointer;font-size:0.8rem;line-height:1;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</script>

<script type="text/template" id="module-card-template-header">
<div class="card mb-lg module-card-editor" data-module-card="__ID__" data-module-id="__ID__" data-module-type="header">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;gap:var(--space-sm);">
        <div class="card-title">
            <i class="bi bi-type-h1"></i>
            <span data-module-card-title>__TITLE__</span>
        </div>
        <div class="text-muted text-sm">Tiêu đề</div>
    </div>
    <div class="card-body">
        <div class="input-grid">
            <div class="form-group">
                <label class="form-label">Tiêu đề hiển thị</label>
                <input class="form-control" name="module_content[__ID__][title]" placeholder="Ví dụ: Ngày hội công nghệ sinh viên 2026">
            </div>
            <div class="form-group">
                <label class="form-label" data-module-label="badge_label">__BADGE_LABEL__</label>
                <input class="form-control" name="module_content[__ID__][badge]" placeholder="Ví dụ: Sự kiện nổi bật">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label" data-module-label="subtitle_label">__SUBTITLE_LABEL__</label>
            <textarea class="form-control" rows="3" name="module_content[__ID__][subtitle]" placeholder="Ví dụ: Chương trình dành cho sinh viên năm nhất..."></textarea>
        </div>
    </div>
</div>
</script>

<script type="text/template" id="module-card-template-info">
<div class="card mb-lg module-card-editor" data-module-card="__ID__" data-module-id="__ID__" data-module-type="info">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;gap:var(--space-sm);">
        <div class="card-title">
            <i class="bi bi-info-circle"></i>
            <span data-module-card-title>__TITLE__</span>
        </div>
        <div class="text-muted text-sm">Thông tin</div>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label class="form-label">Thông tin sẽ hiển thị trong khối này</label>
            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:10px;">
                <label style="display:inline-flex;align-items:center;gap:6px;"><input type="checkbox" name="module_content[__ID__][items][]" value="time" checked> Thời gian</label>
                <label style="display:inline-flex;align-items:center;gap:6px;"><input type="checkbox" name="module_content[__ID__][items][]" value="location" checked> Địa điểm</label>
                <label style="display:inline-flex;align-items:center;gap:6px;"><input type="checkbox" name="module_content[__ID__][items][]" value="capacity" checked> Số lượng</label>
                <label style="display:inline-flex;align-items:center;gap:6px;"><input type="checkbox" name="module_content[__ID__][items][]" value="points" checked> Điểm cộng</label>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Ghi chú thêm trong khối thông tin</label>
            <textarea class="form-control" rows="3" name="module_content[__ID__][custom_note]" placeholder="Ví dụ: Sinh viên mang theo thẻ sinh viên khi check-in."></textarea>
        </div>
    </div>
</div>
</script>

<script type="text/template" id="module-card-template-description">
<div class="card mb-lg module-card-editor" data-module-card="__ID__" data-module-id="__ID__" data-module-type="description">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;gap:var(--space-sm);">
        <div class="card-title">
            <i class="bi bi-text-paragraph"></i>
            <span data-module-card-title>__TITLE__</span>
        </div>
        <div class="text-muted text-sm">Nội dung</div>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label class="form-label">Tiêu đề khối</label>
            <input class="form-control" name="module_content[__ID__][heading]" value="__TITLE__" placeholder="Ví dụ: Nội dung chương trình">
        </div>
        <div class="form-group">
            <label class="form-label" data-module-label="body_label">__BODY_LABEL__</label>
            <textarea class="form-control" rows="8" name="module_content[__ID__][body]" placeholder="Nhập nội dung cho module này..."></textarea>
        </div>
    </div>
</div>
</script>

<script type="text/template" id="module-card-template-gallery">
<div class="card mb-lg module-card-editor" data-module-card="__ID__" data-module-id="__ID__" data-module-type="gallery">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;gap:var(--space-sm);">
        <div class="card-title">
            <i class="bi bi-images"></i>
            <span data-module-card-title>__TITLE__</span>
        </div>
        <div class="text-muted text-sm">Gallery</div>
    </div>
    <div class="card-body">
        <div class="text-muted" data-module-label="hint" style="margin-bottom:var(--space-sm);">__HINT__</div>
        <div class="form-group">
            <label class="form-label">Tải thêm ảnh cho khối này</label>
            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                <input class="form-control" type="file" name="module_gallery[__ID__][]" accept="image/*" multiple style="flex:1;min-width:0;">
                <button type="button" class="btn btn-outline btn-sm" style="white-space:nowrap;flex-shrink:0;"
                    onclick="openMediaPicker('gallery','__ID__','multi')">
                    <i class="bi bi-images"></i> Chọn từ thư viện
                </button>
            </div>
            <div id="gallery-media-preview-__ID__" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:8px;"></div>
        </div>
    </div>
</div>
</script>

<script type="text/template" id="module-card-template-documents">
<div class="card mb-lg module-card-editor" data-module-card="__ID__" data-module-id="__ID__" data-module-type="documents">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;gap:var(--space-sm);">
        <div class="card-title">
            <i class="bi bi-paperclip"></i>
            <span data-module-card-title>__TITLE__</span>
        </div>
        <div class="text-muted text-sm">Tài liệu đính kèm</div>
    </div>
    <div class="card-body">
        <div class="form-group">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:8px;">
                <label class="form-label" style="margin:0;">Đính kèm tài liệu</label>
                <button type="button" class="btn btn-outline btn-sm"
                    onclick="openMediaPicker('documents','__ID__','multi')">
                    <i class="bi bi-paperclip"></i> Chọn từ thư viện
                </button>
            </div>
            <div id="docs-list-__ID__" style="display:flex;flex-direction:column;gap:6px;">
                <div class="text-muted" style="font-size:.8rem;">Chưa có tài liệu nào được đính kèm.</div>
            </div>
        </div>
    </div>
</div>
</script>
@endif

<script>
(() => {
    const form = document.getElementById('suKienForm');
    const submitBtn = document.getElementById('submitBtn');
    const startInput = document.getElementById('thoi_gian_bat_dau');
    const endInput = document.getElementById('thoi_gian_ket_thuc');
    const locationInput = document.getElementById('dia_diem');
    const schemaInput = document.getElementById('moduleSchemaJson');
    const moduleCardsContainer = document.getElementById('moduleCardsContainer');

    if (!form || !submitBtn || !startInput || !endInput || !locationInput || !schemaInput || !moduleCardsContainer) {
        return;
    }

    const moduleCatalog = @json($moduleCatalog);
    let moduleState = JSON.parse(schemaInput.value || '[]');

    function syncSchema() {
        schemaInput.value = JSON.stringify(moduleState);
    }

    @if($mode === 'create')
    const layoutEditorList = document.getElementById('layoutEditorList');

    function renderLayoutEditor() {
        if (!layoutEditorList) {
            return;
        }

        if (!moduleState.length) {
            layoutEditorList.innerHTML = '<div style="border:1px dashed var(--border);border-radius:var(--border-radius);padding:16px;color:var(--text-muted);">Bố cục đang trống. Hãy thêm module để tiếp tục.</div>';
            syncSchema();
            return;
        }

        layoutEditorList.innerHTML = moduleState.map((module, index) => {
            const meta = moduleCatalog[module.type];
            const title = escapeHtmlAttr(module.title || meta.label);
            const settings = module.settings || {};

            let settingsHtml = '';
            if (module.type === 'banner') {
                settingsHtml = `
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Nhãn chú thích</label>
                        <input class="form-control" data-schema-setting="caption_label" data-module-index="${index}" value="${escapeHtmlAttr(settings.caption_label || 'Chú thích ảnh')}">
                    </div>
                `;
            } else if (module.type === 'header') {
                settingsHtml = `
                    <div class="input-grid">
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">Nhãn phụ đề</label>
                            <input class="form-control" data-schema-setting="subtitle_label" data-module-index="${index}" value="${escapeHtmlAttr(settings.subtitle_label || 'Phụ đề')}">
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">Nhãn badge</label>
                            <input class="form-control" data-schema-setting="badge_label" data-module-index="${index}" value="${escapeHtmlAttr(settings.badge_label || 'Badge')}">
                        </div>
                    </div>
                `;
            } else if (module.type === 'description') {
                settingsHtml = `
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Nhãn vùng nội dung</label>
                        <input class="form-control" data-schema-setting="body_label" data-module-index="${index}" value="${escapeHtmlAttr(settings.body_label || 'Nội dung')}">
                    </div>
                `;
            } else if (module.type === 'gallery') {
                settingsHtml = `
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Gợi ý cho người nhập ảnh</label>
                        <input class="form-control" data-schema-setting="hint" data-module-index="${index}" value="${escapeHtmlAttr(settings.hint || 'Tải nhiều ảnh cho riêng khối này')}">
                    </div>
                `;
            } else if (module.type === 'documents') {
                settingsHtml = `
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Nhãn danh sách tài liệu</label>
                        <input class="form-control" data-schema-setting="label" data-module-index="${index}" value="${escapeHtmlAttr(settings.label || 'Tài liệu')}">
                    </div>
                `;
            }

            return `
                <div class="lei-item"
                    data-layout-index="${index}"
                    style="border:1px solid var(--border);border-radius:var(--border-radius);padding:14px;background:var(--bg);user-select:none;transition:box-shadow .15s;">
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:12px;">
                        <div style="display:flex;align-items:center;gap:10px;font-weight:600;">
                            <span class="lei-handle"
                                title="Kéo để sắp xếp"
                                style="cursor:grab;color:var(--text-muted);line-height:1;padding:2px 3px;border-radius:4px;touch-action:none;">
                                <svg width="12" height="18" viewBox="0 0 12 18" fill="currentColor">
                                    <circle cx="3" cy="3"  r="1.6"/>
                                    <circle cx="9" cy="3"  r="1.6"/>
                                    <circle cx="3" cy="9"  r="1.6"/>
                                    <circle cx="9" cy="9"  r="1.6"/>
                                    <circle cx="3" cy="15" r="1.6"/>
                                    <circle cx="9" cy="15" r="1.6"/>
                                </svg>
                            </span>
                            <i class="bi ${meta.icon}"></i>
                            <span>${meta.label}</span>
                            <span class="text-muted text-sm">#${index + 1}</span>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm" data-remove-module="${index}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    <div class="form-group" style="margin-bottom:12px;">
                        <label class="form-label">Tên hiển thị của module</label>
                        <input class="form-control" data-schema-title data-module-index="${index}" value="${title}">
                    </div>
                    ${settingsHtml}
                </div>
            `;
        }).join('');

        initSortable();
        syncSchema();
    }

    // ── Smooth pointer-based sortable ────────────────────────────────────────
    let sortDrag = null; // { el, clone, fromIndex, startY, currentY, itemH, offY }
    const GAP = 12;

    function initSortable() {
        layoutEditorList.querySelectorAll('.lei-handle').forEach(handle => {
            handle.addEventListener('pointerdown', onSortStart, { passive: false });
        });
    }

    function onSortStart(e) {
        if (e.button !== undefined && e.button !== 0) return;
        e.preventDefault();

        const item  = e.currentTarget.closest('.lei-item');
        const index = Number(item.dataset.layoutIndex);
        const rect  = item.getBoundingClientRect();
        const listRect = layoutEditorList.getBoundingClientRect();

        // Snapshot item heights + tops before anything changes
        const items = [...layoutEditorList.querySelectorAll('.lei-item')];
        const snapshots = items.map(el => {
            const r = el.getBoundingClientRect();
            return { el, top: r.top - listRect.top, height: r.height };
        });

        // Create floating clone
        const clone = item.cloneNode(true);
        Object.assign(clone.style, {
            position:      'fixed',
            left:          rect.left + 'px',
            top:           rect.top  + 'px',
            width:         rect.width + 'px',
            zIndex:        '9999',
            pointerEvents: 'none',
            boxShadow:     '0 8px 24px rgba(0,0,0,.18)',
            borderRadius:  'var(--border-radius)',
            opacity:       '1',
            transition:    'box-shadow .1s',
        });
        document.body.appendChild(clone);

        // Dim original
        item.style.opacity = '0.25';

        sortDrag = {
            item, clone, index,
            fromIndex: index,
            snapshots,
            listRect,
            startClientY: e.clientY,
            startTop:     rect.top,
            offY:         e.clientY - rect.top,
            currentIndex: index,
        };

        document.addEventListener('pointermove', onSortMove, { passive: false });
        document.addEventListener('pointerup',   onSortEnd);
        document.addEventListener('pointercancel', onSortEnd);
    }

    function onSortMove(e) {
        if (!sortDrag) return;
        e.preventDefault();

        const { clone, offY, snapshots, listRect } = sortDrag;

        // Move clone
        const cloneTop = e.clientY - offY;
        clone.style.top = cloneTop + 'px';

        // Figure out new insertion index based on pointer Y relative to list
        const relY = e.clientY - listRect.top;
        let newIndex = snapshots.length - 1;
        for (let i = 0; i < snapshots.length; i++) {
            const mid = snapshots[i].top + snapshots[i].height / 2;
            if (relY < mid) { newIndex = i; break; }
        }

        if (newIndex === sortDrag.currentIndex) return;
        sortDrag.currentIndex = newIndex;

        // Animate siblings with translateY
        const from = sortDrag.fromIndex;
        snapshots.forEach(({ el }, i) => {
            if (el === sortDrag.item) return;
            let shift = 0;
            const draggedH = snapshots[from].height + GAP;
            if (from < newIndex) {
                // dragging down: items between from+1..newIndex shift up
                if (i > from && i <= newIndex) shift = -draggedH;
            } else {
                // dragging up: items between newIndex..from-1 shift down
                if (i >= newIndex && i < from) shift = draggedH;
            }
            el.style.transition = 'transform .2s cubic-bezier(.25,.8,.25,1)';
            el.style.transform  = shift ? `translateY(${shift}px)` : '';
        });
    }

    function onSortEnd(e) {
        if (!sortDrag) return;
        document.removeEventListener('pointermove', onSortMove);
        document.removeEventListener('pointerup',   onSortEnd);
        document.removeEventListener('pointercancel', onSortEnd);

        const { item, clone, fromIndex, currentIndex } = sortDrag;
        sortDrag = null;

        // Animate clone to destination then commit
        const destSnap = (() => {
            // rebuild rects because siblings may have shifted
            const items = [...layoutEditorList.querySelectorAll('.lei-item')];
            const dest  = items[currentIndex === fromIndex ? fromIndex : currentIndex];
            return dest ? dest.getBoundingClientRect() : null;
        })();

        clone.style.transition = 'top .2s cubic-bezier(.25,.8,.25,1), left .2s';
        if (destSnap) clone.style.top = destSnap.top + 'px';

        setTimeout(() => {
            clone.remove();
            item.style.opacity  = '';
            item.style.transform = '';

            if (fromIndex !== currentIndex) {
                // Reorder state
                const [moved] = moduleState.splice(fromIndex, 1);
                moduleState.splice(currentIndex, 0, moved);

                // Reorder module cards
                const movedCard = moduleCardsContainer.querySelector(`[data-module-card="${moved.id}"]`);
                if (movedCard) {
                    const after = moduleState[currentIndex + 1];
                    const afterCard = after
                        ? moduleCardsContainer.querySelector(`[data-module-card="${after.id}"]`)
                        : null;
                    if (afterCard) moduleCardsContainer.insertBefore(movedCard, afterCard);
                    else           moduleCardsContainer.appendChild(movedCard);
                }
            }

            renderLayoutEditor();
        }, 210);
    }

    function updateCardLabels(module) {
        const card = moduleCardsContainer.querySelector(`[data-module-card="${module.id}"]`);
        if (!card) {
            return;
        }

        const titleNode = card.querySelector('[data-module-card-title]');
        if (titleNode) {
            titleNode.textContent = module.title || moduleCatalog[module.type].label;
        }

        if (module.type === 'banner') {
            const label = card.querySelector('[data-module-label="caption_label"]');
            if (label) label.textContent = module.settings?.caption_label || 'Chú thích ảnh';
        }

        if (module.type === 'header') {
            const subtitle = card.querySelector('[data-module-label="subtitle_label"]');
            const badge = card.querySelector('[data-module-label="badge_label"]');
            if (subtitle) subtitle.textContent = module.settings?.subtitle_label || 'Phụ đề';
            if (badge) badge.textContent = module.settings?.badge_label || 'Badge';
        }

        if (module.type === 'description') {
            const body = card.querySelector('[data-module-label="body_label"]');
            if (body) body.textContent = module.settings?.body_label || 'Nội dung';
        }

        if (module.type === 'gallery') {
            const hint = card.querySelector('[data-module-label="hint"]');
            if (hint) hint.textContent = module.settings?.hint || 'Tải nhiều ảnh cho riêng khối này';
        }

        if (module.type === 'documents') {
            const lbl = card.querySelector('[data-module-label="label"]');
            if (lbl) lbl.textContent = module.settings?.label || 'Tài liệu';
        }
    }

    function buildModuleCardHtml(module) {
        const template = document.getElementById(`module-card-template-${module.type}`);
        if (!template) {
            return '';
        }

        const settings = module.settings || {};

        return template.innerHTML
            .replaceAll('__ID__', module.id)
            .replaceAll('__TITLE__', escapeHtmlAttr(module.title || moduleCatalog[module.type].label))
            .replaceAll('__CAPTION_LABEL__', escapeHtmlAttr(settings.caption_label || 'Chú thích ảnh'))
            .replaceAll('__BADGE_LABEL__', escapeHtmlAttr(settings.badge_label || 'Badge'))
            .replaceAll('__SUBTITLE_LABEL__', escapeHtmlAttr(settings.subtitle_label || 'Phụ đề'))
            .replaceAll('__BODY_LABEL__', escapeHtmlAttr(settings.body_label || 'Nội dung'))
            .replaceAll('__HINT__', escapeHtmlAttr(settings.hint || 'Tải nhiều ảnh cho riêng khối này'))
            .replaceAll('__DOC_LABEL__', escapeHtmlAttr(settings.label || 'Tài liệu'));
    }

    function appendModuleCard(module) {
        moduleCardsContainer.insertAdjacentHTML('beforeend', buildModuleCardHtml(module));
    }

    function createDefaultModule(type) {
        const meta = moduleCatalog[type];
        const count = moduleState.filter(item => item.type === type).length + 1;
        const defaults = JSON.parse(JSON.stringify(meta.defaults || {}));
        return {
            id: `${type}-${Date.now()}-${Math.random().toString(16).slice(2, 6)}`,
            type,
            title: `${meta.label} ${count}`,
            settings: defaults.settings || {},
            content: {},
        };
    }

    document.addEventListener('click', (event) => {
        const addButton = event.target.closest('[data-add-module]');
        if (addButton) {
            const type = addButton.dataset.addModule;
            const module = createDefaultModule(type);
            moduleState.push(module);
            appendModuleCard(module);
            renderLayoutEditor();
            return;
        }

        const removeButton = event.target.closest('[data-remove-module]');
        if (removeButton) {
            const index = Number(removeButton.dataset.removeModule);
            const [removed] = moduleState.splice(index, 1);
            if (removed) {
                moduleCardsContainer.querySelector(`[data-module-card="${removed.id}"]`)?.remove();
            }
            renderLayoutEditor();
            return;
        }
    });

    document.addEventListener('input', (event) => {
        const index = Number(event.target.dataset.moduleIndex);
        if (Number.isNaN(index) || !moduleState[index]) {
            return;
        }

        if (event.target.hasAttribute('data-schema-title')) {
            moduleState[index].title = event.target.value;
            updateCardLabels(moduleState[index]);
            syncSchema();
            return;
        }

        const settingKey = event.target.dataset.schemaSetting;
        if (settingKey) {
            moduleState[index].settings = moduleState[index].settings || {};
            moduleState[index].settings[settingKey] = event.target.value;
            updateCardLabels(moduleState[index]);
            syncSchema();
        }
    });

    renderLayoutEditor();
    @endif

    form.addEventListener('submit', async (event) => {
        syncSchema();

        if (form.dataset.collisionChecked === '1') {
            return;
        }

        const start = startInput.value;
        const end = endInput.value;
        const location = locationInput.value.trim();

        if (!start || !end || !location) {
            return;
        }

        event.preventDefault();
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" style="margin-right:6px;"></span>Kiểm tra lịch...';

        try {
            const response = await fetch('{{ route('admin.su-kien.check-collision') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    thoi_gian_bat_dau: start,
                    thoi_gian_ket_thuc: end,
                    dia_diem: location,
                    bo_qua_id: @json($eventModel->ma_su_kien ?? null),
                })
            });
            const data = await response.json();

            if (data.has_collision) {
                const detail = (data.conflicts || [])
                    .map(item => `- ${item.ten_su_kien} (${new Date(item.thoi_gian_bat_dau).toLocaleString('vi-VN')} - ${new Date(item.thoi_gian_ket_thuc).toLocaleString('vi-VN')})`)
                    .join('\n');

                const confirmed = confirm(`Phát hiện trùng lịch tại địa điểm này:\n\n${detail}\n\nBạn vẫn muốn tiếp tục?`);

                if (!confirmed) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-check2-circle"></i> {{ $mode === "create" ? "Tạo bài đăng" : "Lưu thay đổi" }}';
                    return;
                }

                const forceInput = document.createElement('input');
                forceInput.type = 'hidden';
                forceInput.name = '{{ $mode === "create" ? "force_create" : "force_update" }}';
                forceInput.value = '1';
                form.appendChild(forceInput);
            }
        } catch (error) {
            console.error(error);
        }

        form.dataset.collisionChecked = '1';
        form.submit();
    });

    function escapeHtmlAttr(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('"', '&quot;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;');
    }
})();
</script>

{{-- ===== MEDIA PICKER MODAL ===== --}}
<div id="mediaPickerModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.5);align-items:center;justify-content:center;padding:16px;">
    <div style="background:var(--card);border-radius:12px;width:100%;max-width:860px;max-height:88vh;display:flex;flex-direction:column;box-shadow:0 20px 60px rgba(0,0,0,.3);">
        {{-- Header --}}
        <div style="padding:14px 18px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:12px;flex-shrink:0;">
            <div style="font-size:.95rem;font-weight:600;color:var(--text);">Chọn từ thư viện media</div>
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                <input type="text" id="mpSearch" placeholder="Tìm theo tên..." oninput="mpDoSearch()"
                    style="height:30px;padding:0 10px;font-size:.8rem;border:1px solid var(--border);border-radius:6px;background:var(--bg);color:var(--text);width:180px;">
                <select id="mpFilter" onchange="mpDoSearch()" style="height:30px;font-size:.8rem;border:1px solid var(--border);border-radius:6px;background:var(--bg);color:var(--text);padding:0 8px;">
                    <option value="">Tất cả</option>
                    <option value="hinh_anh">Hình ảnh</option>
                    <option value="tai_lieu">Tài liệu</option>
                    <option value="video">Video</option>
                    <option value="khac">Khác</option>
                </select>
                <button type="button" onclick="closeMediaPicker()" style="background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:1.4rem;line-height:1;padding:0 4px;">&#215;</button>
            </div>
        </div>
        {{-- Grid --}}
        <div id="mpGrid" style="flex:1;overflow-y:auto;padding:14px;display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:10px;min-height:200px;"></div>
        {{-- Footer --}}
        <div style="padding:12px 18px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:8px;flex-shrink:0;flex-wrap:wrap;">
            <div style="display:flex;align-items:center;gap:8px;">
                <button type="button" id="mpPrev" onclick="mpChangePage(-1)" style="padding:4px 10px;font-size:.8rem;border:1px solid var(--border);border-radius:6px;background:var(--bg);cursor:pointer;" disabled>&lsaquo; Trước</button>
                <span id="mpPageInfo" style="font-size:.8rem;color:var(--text-muted);">Trang 1</span>
                <button type="button" id="mpNext" onclick="mpChangePage(1)" style="padding:4px 10px;font-size:.8rem;border:1px solid var(--border);border-radius:6px;background:var(--bg);cursor:pointer;">Sau &rsaquo;</button>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                <span id="mpSelectedCount" style="font-size:.8rem;color:var(--text-muted);font-weight:600;"></span>
                <button type="button" onclick="closeMediaPicker()" style="padding:6px 14px;font-size:.8rem;border:1px solid var(--border);border-radius:7px;background:var(--bg);cursor:pointer;">Hủy</button>
                <button type="button" id="mpConfirmBtn" onclick="confirmPickerSelection()" style="padding:6px 16px;font-size:.8rem;border:none;border-radius:7px;background:#185FA5;color:#fff;font-weight:600;cursor:pointer;">Xác nhận</button>
            </div>
        </div>
    </div>
</div>

<script>
// ===== MEDIA PICKER STATE =====
let _mp = {
    type: '',       // 'banner' | 'gallery' | 'documents'
    moduleId: '',
    mode: 'single', // 'single' | 'multi'
    page: 1,
    lastPage: 1,
    selected: {},   // { ma_phuong_tien: itemObj }
    searchTimer: null,
};

const _mpApiUrl = '{{ route("admin.media.api.list") }}';

function openMediaPicker(type, moduleId, mode) {
    _mp.type     = type;
    _mp.moduleId = moduleId;
    _mp.mode     = mode;
    _mp.page     = 1;
    _mp.selected = {};

    // Auto-filter theo loại module
    const filter = document.getElementById('mpFilter');
    if (type === 'banner' || type === 'gallery') {
        filter.value = 'hinh_anh';
    } else if (type === 'documents') {
        filter.value = 'tai_lieu';
    } else {
        filter.value = '';
    }
    document.getElementById('mpSearch').value = '';

    const modal = document.getElementById('mediaPickerModal');
    modal.style.display = 'flex';
    mpLoadPage(1);
}

function closeMediaPicker() {
    document.getElementById('mediaPickerModal').style.display = 'none';
}

function mpDoSearch() {
    clearTimeout(_mp.searchTimer);
    _mp.searchTimer = setTimeout(() => { _mp.page = 1; mpLoadPage(1); }, 350);
}

function mpChangePage(delta) {
    const next = _mp.page + delta;
    if (next < 1 || next > _mp.lastPage) return;
    mpLoadPage(next);
}

function mpLoadPage(page) {
    const search  = document.getElementById('mpSearch').value.trim();
    const loaiTep = document.getElementById('mpFilter').value;
    const grid    = document.getElementById('mpGrid');
    grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:2rem;color:var(--text-muted);">Đang tải...</div>';

    const params = new URLSearchParams({ page });
    if (loaiTep) params.set('loai_tep', loaiTep);
    if (search)  params.set('tu_khoa', search);

    fetch(`${_mpApiUrl}?${params}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(json => {
            _mp.page     = json.current_page;
            _mp.lastPage = json.last_page;
            document.getElementById('mpPageInfo').textContent = `Trang ${_mp.page} / ${_mp.lastPage}`;
            document.getElementById('mpPrev').disabled = _mp.page <= 1;
            document.getElementById('mpNext').disabled = _mp.page >= _mp.lastPage;
            mpRenderGrid(json.data);
        })
        .catch(() => {
            grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:2rem;color:#c0392b;">Lỗi tải dữ liệu.</div>';
        });
}

function mpRenderGrid(items) {
    const grid = document.getElementById('mpGrid');
    if (!items.length) {
        grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:2rem;color:var(--text-muted);">Không có file nào.</div>';
        return;
    }
    grid.innerHTML = items.map(item => {
        const isImg      = item.loai_tep === 'hinh_anh';
        const isSelected = !!_mp.selected[item.ma_phuong_tien];
        const border     = isSelected ? '2px solid #185FA5' : '2px solid var(--border)';
        const checkIcon  = isSelected ? '<div style="position:absolute;inset:0;background:rgba(24,95,165,.18);border-radius:5px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-check-circle-fill" style="font-size:1.5rem;color:#185FA5;"></i></div>' : '';
        const thumb      = isImg
            ? `<img src="${item.url}" alt="" style="width:100%;height:90px;object-fit:cover;border-radius:5px;">`
            : `<div style="height:90px;display:flex;align-items:center;justify-content:center;background:var(--bg-alt);border-radius:5px;"><i class="bi bi-file-earmark-text" style="font-size:2rem;color:var(--text-muted);"></i></div>`;
        const safeItem   = JSON.stringify(item).replace(/'/g, "&#39;");
        return `
            <div onclick="mpToggleItem(${item.ma_phuong_tien},this)" data-item='${safeItem}'
                style="cursor:pointer;border-radius:7px;padding:6px;border:${border};transition:border-color .12s;position:relative;" title="${item.ten_tep}">
                <div style="position:relative;">${thumb}${checkIcon}</div>
                <div style="font-size:.68rem;margin-top:5px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:var(--text);">${item.ten_tep}</div>
            </div>`;
    }).join('');
    mpUpdateCount();
}

function mpToggleItem(id, el) {
    const item = JSON.parse(el.dataset.item.replace(/&#39;/g, "'"));
    if (_mp.mode === 'single') {
        _mp.selected = {};
        document.querySelectorAll('#mpGrid [data-item]').forEach(e => {
            e.style.border = '2px solid var(--border)';
            e.querySelector('div [style*="rgba"]')?.remove();
        });
    }
    if (_mp.selected[id]) {
        delete _mp.selected[id];
        el.style.border = '2px solid var(--border)';
        el.querySelector('div [style*="rgba"]')?.remove();
    } else {
        _mp.selected[id] = item;
        el.style.border = '2px solid #185FA5';
        const thumbWrap = el.querySelector('div[style*="position:relative"]');
        if (thumbWrap && !thumbWrap.querySelector('[style*="rgba"]')) {
            thumbWrap.insertAdjacentHTML('beforeend',
                '<div style="position:absolute;inset:0;background:rgba(24,95,165,.18);border-radius:5px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-check-circle-fill" style="font-size:1.5rem;color:#185FA5;"></i></div>');
        }
    }
    mpUpdateCount();
}

function mpUpdateCount() {
    const n = Object.keys(_mp.selected).length;
    document.getElementById('mpSelectedCount').textContent = n ? `Đã chọn: ${n}` : '';
}

function confirmPickerSelection() {
    const items = Object.values(_mp.selected);
    if (!items.length) { closeMediaPicker(); return; }

    if (_mp.type === 'banner') {
        const item = items[0];
        const inp  = document.getElementById(`banner-media-path-${_mp.moduleId}`);
        if (inp) inp.value = item.duong_dan_tep;
        const preview = document.getElementById(`banner-media-preview-${_mp.moduleId}`);
        const img     = document.getElementById(`banner-media-img-${_mp.moduleId}`);
        if (preview && img) { img.src = item.url; preview.style.display = 'block'; }
    }

    if (_mp.type === 'gallery') {
        items.forEach(item => addGalleryMedia(_mp.moduleId, item));
    }

    if (_mp.type === 'documents') {
        items.forEach(item => addDocumentItem(_mp.moduleId, item));
    }

    closeMediaPicker();
}

function clearBannerMedia(moduleId) {
    const inp = document.getElementById(`banner-media-path-${moduleId}`);
    if (inp) inp.value = '';
    const preview = document.getElementById(`banner-media-preview-${moduleId}`);
    if (preview) preview.style.display = 'none';
}

function addGalleryMedia(moduleId, item) {
    const container = document.getElementById(`gallery-media-preview-${moduleId}`);
    if (!container) return;
    if (container.querySelector(`[data-media-id="${item.ma_phuong_tien}"]`)) return;
    const div = document.createElement('div');
    div.dataset.mediaId = item.ma_phuong_tien;
    div.style.position = 'relative';
    div.innerHTML = `
        <img src="${item.url}" alt="${item.ten_tep}" title="${item.ten_tep}"
            style="width:80px;height:80px;object-fit:cover;border-radius:6px;border:1px solid var(--border);">
        <input type="hidden" name="module_gallery_media_ids[${moduleId}][]" value="${item.ma_phuong_tien}">
        <button type="button" onclick="this.parentElement.remove()" title="Bỏ chọn"
            style="position:absolute;top:2px;right:2px;background:rgba(0,0,0,.6);color:#fff;border:none;border-radius:50%;width:18px;height:18px;cursor:pointer;font-size:.65rem;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-x"></i>
        </button>`;
    container.appendChild(div);
}

function addDocumentItem(moduleId, item) {
    const container = document.getElementById(`docs-list-${moduleId}`);
    if (!container) return;
    if (container.querySelector(`[data-doc-id="${item.ma_phuong_tien}"]`)) return;
    // Xóa placeholder text
    container.querySelectorAll('.text-muted').forEach(el => el.remove());
    const div = document.createElement('div');
    div.className = 'doc-item';
    div.dataset.docId = item.ma_phuong_tien;
    div.style.cssText = 'display:flex;align-items:center;gap:8px;padding:8px 10px;border:1px solid var(--border);border-radius:var(--border-radius);background:var(--bg);';
    div.innerHTML = `
        <i class="bi bi-file-earmark-text" style="color:var(--text-muted);flex-shrink:0;"></i>
        <span style="flex:1;font-size:.85rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${item.ten_tep}">${item.ten_tep}</span>
        <input type="hidden" name="module_content[${moduleId}][media_ids][]" value="${item.ma_phuong_tien}">
        <button type="button" onclick="this.closest('.doc-item').remove()" title="Xóa"
            style="background:none;border:none;color:var(--text-muted);cursor:pointer;flex-shrink:0;font-size:1rem;">
            <i class="bi bi-x-circle"></i>
        </button>`;
    container.appendChild(div);
}

// Đóng picker khi click nền
document.getElementById('mediaPickerModal').addEventListener('click', function(e) {
    if (e.target === this) closeMediaPicker();
});
</script>