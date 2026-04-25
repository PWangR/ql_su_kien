@extends('admin.layout')

@php
    use App\Support\EventTemplateSupport;

    $templatePayload = $templates->getCollection()->map(function ($template) {
        return [
            'id' => $template->ma_mau,
            'ten_mau' => $template->ten_mau,
            'noi_dung' => $template->noi_dung,
            'ma_loai_su_kien' => $template->ma_loai_su_kien,
            'dia_diem' => $template->dia_diem,
            'so_luong_toi_da' => $template->so_luong_toi_da,
            'diem_cong' => $template->diem_cong,
            'bo_cuc' => EventTemplateSupport::normalizeTemplateModules($template->bo_cuc),
        ];
    })->values();
@endphp

@section('title', 'Mẫu bài đăng')
@section('page-title', 'Mẫu bài đăng')

@section('styles')
<style>
    .template-page {
        display: grid;
        grid-template-columns: minmax(0, 1.15fr) minmax(380px, 0.85fr);
        gap: var(--space-lg);
        align-items: start;
    }

    .template-list {
        display: grid;
        gap: var(--space-md);
    }

    .template-card {
        border: 1px solid var(--border);
        border-radius: var(--border-radius-md);
        background: var(--card);
        padding: var(--space-lg);
    }

    .template-meta {
        display: flex;
        gap: var(--space-sm);
        flex-wrap: wrap;
        margin-top: var(--space-sm);
        font-size: .8rem;
        color: var(--text-muted);
    }

    .template-module-tags {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: var(--space-md);
    }

    .template-module-tags span,
    .module-picker button,
    .module-chip {
        border: 1px solid var(--border);
        border-radius: 999px;
        padding: 6px 12px;
        background: var(--bg-alt);
        font-size: .8rem;
    }

    .builder-card {
        position: sticky;
        top: 20px;
    }

    .module-picker {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: var(--space-lg);
    }

    .module-picker button {
        cursor: pointer;
    }

    .module-builder-list {
        display: grid;
        gap: var(--space-md);
    }

    .module-item {
        border: 1px solid var(--border);
        border-radius: var(--border-radius-md);
        padding: var(--space-md);
        background: var(--bg);
    }

    .module-item-head {
        display: flex;
        justify-content: space-between;
        gap: var(--space-sm);
        align-items: center;
        margin-bottom: var(--space-sm);
    }

    .module-item-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
    }

    .module-item-actions {
        display: flex;
        gap: 8px;
    }

    .module-item-actions button {
        border: 1px solid var(--border);
        background: var(--card);
        border-radius: 8px;
        padding: 6px 10px;
        cursor: pointer;
    }

    .empty-builder {
        border: 1px dashed var(--border);
        border-radius: var(--border-radius-md);
        padding: var(--space-lg);
        color: var(--text-muted);
        text-align: center;
    }

    .builder-note {
        margin-bottom: var(--space-lg);
        padding: var(--space-md);
        border: 1px solid var(--border);
        background: var(--bg-alt);
        border-radius: var(--border-radius-md);
        color: var(--text-light);
        font-size: .9rem;
    }

    .item-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: var(--space-md);
    }

    .check-grid {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 8px;
    }

    .check-grid label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: .85rem;
    }

    @media (max-width: 1200px) {
        .template-page {
            grid-template-columns: 1fr;
        }

        .builder-card {
            position: static;
        }
    }
</style>
@endsection

@section('content')
<div class="template-page">
    <div class="template-list">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-collection"></i> Danh sách mẫu</div>
            </div>
            <div class="card-body">
                <div class="builder-note">
                    Trang này dùng để định nghĩa cấu trúc bài đăng. Người dùng sẽ chọn một mẫu trước khi điền thông tin sự kiện, nên việc thêm 2 banner, 2 phần nội dung hoặc nhiều gallery khác nhau đều được quyết định tại đây.
                </div>

                @forelse($templates as $template)
                    @php $modules = EventTemplateSupport::normalizeTemplateModules($template->bo_cuc); @endphp
                    <div class="template-card">
                        <div style="display:flex;justify-content:space-between;gap:var(--space-md);align-items:flex-start;">
                            <div style="flex:1;">
                                <div style="font-size:1.05rem;font-weight:700;">{{ $template->ten_mau }}</div>
                                <div class="template-meta">
                                    <span><i class="bi bi-grid"></i> {{ count($modules) }} module</span>
                                    <span><i class="bi bi-tag"></i> {{ $template->loaiSuKien->ten_loai ?? 'Mọi loại sự kiện' }}</span>
                                    @if($template->dia_diem)<span><i class="bi bi-geo-alt"></i> {{ $template->dia_diem }}</span>@endif
                                    @if($template->diem_cong)<span><i class="bi bi-star"></i> +{{ $template->diem_cong }} điểm</span>@endif
                                </div>
                                @if($template->noi_dung)
                                    <div style="margin-top:var(--space-sm);color:var(--text-light);line-height:1.6;">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($template->noi_dung), 180) }}
                                    </div>
                                @endif
                                <div class="template-module-tags">
                                    @foreach($modules as $module)
                                        <span>{{ $moduleCatalog[$module['type']]['label'] ?? $module['type'] }}: {{ $module['title'] }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-secondary btn-sm" type="button" data-edit-template="{{ $template->ma_mau }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.templates.destroy', $template->ma_mau) }}" onsubmit="return confirm('Xóa mẫu này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-builder">Chưa có mẫu nào. Hãy tạo mẫu đầu tiên ở khung bên phải.</div>
                @endforelse
            </div>
            @if($templates->hasPages())
                <div class="card-footer">{{ $templates->links() }}</div>
            @endif
        </div>
    </div>

    <div class="card builder-card">
        <div class="card-header">
            <div class="card-title" id="builderTitle"><i class="bi bi-plus-circle"></i> Tạo mẫu mới</div>
        </div>
        <div class="card-body">
            <form method="POST" id="templateForm" action="{{ route('admin.templates.store') }}" enctype="multipart/form-data">
                @csrf
                <div id="methodField"></div>
                <input type="hidden" name="bo_cuc_json" id="boCucJson">

                <div class="form-group">
                    <label class="form-label">Tên mẫu</label>
                    <input class="form-control" name="ten_mau" id="inputTenMau" required maxlength="150" placeholder="Ví dụ: Mẫu landing day hội thảo">
                </div>

                <div class="item-grid">
                    <div class="form-group">
                        <label class="form-label">Loại sự kiện mặc định</label>
                        <select class="form-control" name="ma_loai_su_kien" id="inputLoai">
                            <option value="">Mọi loại sự kiện</option>
                            @foreach($loaiSuKien as $loai)
                                <option value="{{ $loai->ma_loai_su_kien }}">{{ $loai->ten_loai }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Địa điểm gợi ý</label>
                        <input class="form-control" name="dia_diem" id="inputDiaDiem" maxlength="200" placeholder="Ví dụ: Hội trường A">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Số lượng mặc định</label>
                        <input class="form-control" type="number" min="0" name="so_luong_toi_da" id="inputSoLuong" placeholder="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Điểm cộng mặc định</label>
                        <input class="form-control" type="number" min="0" name="diem_cong" id="inputDiemCong" placeholder="0">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Ghi chú / hướng dẫn cho người soạn</label>
                    <textarea class="form-control" name="noi_dung" id="inputNoiDung" rows="4" placeholder="Ví dụ: Banner 1 dùng cho hero chính, banner 2 là ảnh chân trang..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Ảnh preview của mẫu</label>
                    <input class="form-control" type="file" name="anh_su_kien" accept="image/*">
                </div>

                <hr class="section-rule">

                <div class="form-group">
                    <label class="form-label">Cấu trúc module</label>
                    <div class="module-picker">
                        @foreach($moduleCatalog as $type => $meta)
                            <button type="button" data-add-module="{{ $type }}">
                                <i class="bi {{ $meta['icon'] }}"></i> {{ $meta['label'] }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div id="moduleBuilderList" class="module-builder-list"></div>

                <div class="btn-group" style="margin-top:var(--space-lg);">
                    <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;">
                        <i class="bi bi-save"></i> Lưu mẫu
                    </button>
                    <button type="button" class="btn btn-outline" id="resetTemplateForm">Làm lại</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const moduleCatalog = @json($moduleCatalog);
const infoCatalog = @json(EventTemplateSupport::infoFieldCatalog());
const templatePayload = @json($templatePayload);
const defaultModules = @json(EventTemplateSupport::defaultTemplateModules());

const form = document.getElementById('templateForm');
const builderTitle = document.getElementById('builderTitle');
const methodField = document.getElementById('methodField');
const boCucJson = document.getElementById('boCucJson');
const moduleBuilderList = document.getElementById('moduleBuilderList');

let moduleState = structuredClone(defaultModules);

function ensureModuleDefaults(module) {
    const meta = moduleCatalog[module.type];
    return {
        id: module.id || `${module.type}-${Date.now()}-${Math.random().toString(16).slice(2, 6)}`,
        type: module.type,
        title: module.title || meta.label,
        settings: module.settings || {},
        content: module.content || {},
    };
}

function renderModuleSettings(module, index) {
    const prefix = `module-${index}`;

    if (module.type === 'banner') {
        return `
            <div class="form-group">
                <label class="form-label">Tên khối hiển thị</label>
                <input class="form-control" data-field="title" data-index="${index}" value="${escapeHtmlAttr(module.title || '')}" placeholder="Ví dụ: Ảnh bìa chính">
            </div>
            <div class="form-group">
                <label class="form-label">Nhãn chú thích khi nhập nội dung</label>
                <input class="form-control" data-setting="caption_label" data-index="${index}" value="${escapeHtmlAttr(module.settings?.caption_label || 'Chú thích ảnh')}" placeholder="Ví dụ: Chú thích banner">
            </div>
        `;
    }

    if (module.type === 'header') {
        return `
            <div class="form-group">
                <label class="form-label">Tên khối hiển thị</label>
                <input class="form-control" data-field="title" data-index="${index}" value="${escapeHtmlAttr(module.title || '')}" placeholder="Ví dụ: Tiêu đề đầu trang">
            </div>
            <div class="item-grid">
                <div class="form-group">
                    <label class="form-label">Nhãn phụ đề</label>
                    <input class="form-control" data-setting="subtitle_label" data-index="${index}" value="${escapeHtmlAttr(module.settings?.subtitle_label || 'Phụ đề')}" placeholder="Phụ đề">
                </div>
                <div class="form-group">
                    <label class="form-label">Nhãn badge</label>
                    <input class="form-control" data-setting="badge_label" data-index="${index}" value="${escapeHtmlAttr(module.settings?.badge_label || 'Badge')}" placeholder="Badge">
                </div>
            </div>
        `;
    }

    if (module.type === 'info') {
        const selectedItems = Array.isArray(module.settings?.items) ? module.settings.items : [];
        const itemOptions = Object.entries(infoCatalog).map(([key, label]) => `
            <label>
                <input type="checkbox" data-setting-array="items" data-index="${index}" value="${key}" ${selectedItems.includes(key) ? 'checked' : ''}>
                ${label}
            </label>
        `).join('');

        return `
            <div class="form-group">
                <label class="form-label">Tên khối hiển thị</label>
                <input class="form-control" data-field="title" data-index="${index}" value="${escapeHtmlAttr(module.title || '')}" placeholder="Ví dụ: Thông tin chương trình">
            </div>
            <div class="form-group">
                <label class="form-label">Mục thông tin được phép hiển thị</label>
                <div class="check-grid">${itemOptions}</div>
            </div>
        `;
    }

    if (module.type === 'description') {
        return `
            <div class="form-group">
                <label class="form-label">Tên khối hiển thị</label>
                <input class="form-control" data-field="title" data-index="${index}" value="${escapeHtmlAttr(module.title || '')}" placeholder="Ví dụ: Nội dung chính">
            </div>
            <div class="form-group">
                <label class="form-label">Nhãn vùng soạn thảo</label>
                <input class="form-control" data-setting="body_label" data-index="${index}" value="${escapeHtmlAttr(module.settings?.body_label || 'Nội dung')}" placeholder="Ví dụ: Nội dung mô tả">
            </div>
        `;
    }

    return `
        <div class="form-group">
            <label class="form-label">Tên khối hiển thị</label>
            <input class="form-control" data-field="title" data-index="${index}" value="${escapeHtmlAttr(module.title || '')}" placeholder="Ví dụ: Album hậu trường">
        </div>
        <div class="form-group">
            <label class="form-label">Gợi ý cho người nhập ảnh</label>
            <input class="form-control" data-setting="hint" data-index="${index}" value="${escapeHtmlAttr(module.settings?.hint || 'Tải nhiều ảnh cho riêng khối này')}" placeholder="Ví dụ: Chọn ảnh hậu trường hoặc ảnh recap">
        </div>
    `;
}

function renderModules() {
    if (!moduleState.length) {
        moduleBuilderList.innerHTML = '<div class="empty-builder">Mẫu đang không có module nào. Hãy thêm ít nhất một module trước khi lưu.</div>';
        syncJson();
        return;
    }

    moduleBuilderList.innerHTML = moduleState.map((rawModule, index) => {
        const module = ensureModuleDefaults(rawModule);
        const meta = moduleCatalog[module.type];

        return `
            <div class="module-item" data-module-index="${index}">
                <div class="module-item-head">
                    <div class="module-item-title">
                        <i class="bi ${meta.icon}"></i>
                        <span>${meta.label}</span>
                        <span class="module-chip">${module.id}</span>
                    </div>
                    <div class="module-item-actions">
                        <button type="button" data-move-up="${index}" title="Đưa lên trên"><i class="bi bi-arrow-up"></i></button>
                        <button type="button" data-move-down="${index}" title="Đưa xuống dưới"><i class="bi bi-arrow-down"></i></button>
                        <button type="button" data-remove-module="${index}" title="Xóa module"><i class="bi bi-trash"></i></button>
                    </div>
                </div>
                <div style="font-size:.84rem;color:var(--text-muted);margin-bottom:var(--space-sm);">${meta.description}</div>
                ${renderModuleSettings(module, index)}
            </div>
        `;
    }).join('');

    syncJson();
}

function syncJson() {
    boCucJson.value = JSON.stringify(moduleState);
}

function addModule(type) {
    const countOfType = moduleState.filter(module => module.type === type).length + 1;
    moduleState.push(ensureModuleDefaults({
        id: `${type}-${countOfType}`,
        type,
        title: `${moduleCatalog[type].label} ${countOfType}`,
        settings: structuredClone(moduleCatalog[type].defaults?.settings || {}),
        content: {},
    }));
    renderModules();
}

function resetForm() {
    builderTitle.innerHTML = '<i class="bi bi-plus-circle"></i> Tạo mẫu mới';
    form.action = '{{ route("admin.templates.store") }}';
    methodField.innerHTML = '';
    form.reset();
    moduleState = structuredClone(defaultModules);
    renderModules();
}

function editTemplate(templateId) {
    const template = templatePayload.find(item => Number(item.id) === Number(templateId));
    if (!template) return;

    builderTitle.innerHTML = '<i class="bi bi-pencil"></i> Chỉnh sửa mẫu';
    form.action = `/admin/templates/${template.id}`;
    methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';

    document.getElementById('inputTenMau').value = template.ten_mau || '';
    document.getElementById('inputNoiDung').value = template.noi_dung || '';
    document.getElementById('inputLoai').value = template.ma_loai_su_kien || '';
    document.getElementById('inputDiaDiem').value = template.dia_diem || '';
    document.getElementById('inputSoLuong').value = template.so_luong_toi_da || 0;
    document.getElementById('inputDiemCong').value = template.diem_cong || 0;

    moduleState = structuredClone(template.bo_cuc || defaultModules);
    renderModules();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

document.addEventListener('click', (event) => {
    const addBtn = event.target.closest('[data-add-module]');
    if (addBtn) {
        addModule(addBtn.dataset.addModule);
        return;
    }

    const removeBtn = event.target.closest('[data-remove-module]');
    if (removeBtn) {
        moduleState.splice(Number(removeBtn.dataset.removeModule), 1);
        renderModules();
        return;
    }

    const upBtn = event.target.closest('[data-move-up]');
    if (upBtn) {
        const index = Number(upBtn.dataset.moveUp);
        if (index > 0) {
            [moduleState[index - 1], moduleState[index]] = [moduleState[index], moduleState[index - 1]];
            renderModules();
        }
        return;
    }

    const downBtn = event.target.closest('[data-move-down]');
    if (downBtn) {
        const index = Number(downBtn.dataset.moveDown);
        if (index < moduleState.length - 1) {
            [moduleState[index + 1], moduleState[index]] = [moduleState[index], moduleState[index + 1]];
            renderModules();
        }
        return;
    }

    const editBtn = event.target.closest('[data-edit-template]');
    if (editBtn) {
        editTemplate(editBtn.dataset.editTemplate);
    }
});

document.addEventListener('input', (event) => {
    const field = event.target.dataset.field;
    const setting = event.target.dataset.setting;
    const index = Number(event.target.dataset.index);

    if (Number.isNaN(index) || !moduleState[index]) {
        return;
    }

    if (field) {
        moduleState[index][field] = event.target.value;
        syncJson();
    }

    if (setting) {
        moduleState[index].settings = moduleState[index].settings || {};
        moduleState[index].settings[setting] = event.target.value;
        syncJson();
    }
});

document.addEventListener('change', (event) => {
    const arraySetting = event.target.dataset.settingArray;
    const index = Number(event.target.dataset.index);

    if (!arraySetting || Number.isNaN(index) || !moduleState[index]) {
        return;
    }

    const checked = [...document.querySelectorAll(`[data-setting-array="${arraySetting}"][data-index="${index}"]:checked`)]
        .map(input => input.value);

    moduleState[index].settings = moduleState[index].settings || {};
    moduleState[index].settings[arraySetting] = checked;
    syncJson();
});

document.getElementById('resetTemplateForm').addEventListener('click', resetForm);
form.addEventListener('submit', syncJson);

function escapeHtmlAttr(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('"', '&quot;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;');
}

renderModules();
</script>
@endsection
