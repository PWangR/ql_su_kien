@extends('admin.layout')

@section('title', 'Thư viện Media')
@section('page-title', 'Thư viện Media')

@section('styles')
<style>
    /* ===== MEDIA LIBRARY — REDESIGN ===== */

    .ml-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 1.25rem;
    }

    .ml-title-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ml-icon-wrap {
        width: 38px;
        height: 38px;
        background: #E6F1FB;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .ml-icon-wrap i {
        font-size: 17px;
        color: #185FA5;
    }

    .ml-page-title {
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--text);
        margin: 0;
        line-height: 1.3;
    }

    .ml-page-subtitle {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin: 0;
    }

    .btn-upload-new {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #185FA5;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s;
        white-space: nowrap;
        text-decoration: none;
    }

    .btn-upload-new:hover {
        background: #0C447C;
        color: #fff;
    }

    /* ===== FILTER PANEL ===== */
    .ml-filter-panel {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .ml-filter-row {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .ml-filter-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
        min-width: 68px;
    }

    .ml-search-form {
        display: flex;
        gap: 6px;
        flex: 1;
        max-width: 380px;
        margin: 0;
    }

    .ml-search-input {
        flex: 1;
        padding: 6px 10px;
        font-size: 0.8rem;
        border: 1px solid var(--border);
        border-radius: 7px;
        background: var(--bg);
        color: var(--text);
        height: 32px;
        min-height: 32px;
        outline: none;
        transition: border-color 0.15s;
    }

    .ml-search-input:focus {
        border-color: #378ADD;
    }

    .btn-ml-search {
        padding: 0 12px;
        font-size: 0.8rem;
        height: 32px;
        background: var(--bg-alt);
        border: 1px solid var(--border);
        border-radius: 7px;
        cursor: pointer;
        color: var(--text);
        white-space: nowrap;
        transition: background 0.12s;
    }

    .btn-ml-search:hover {
        background: var(--border);
    }

    .ml-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 11px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid var(--border);
        cursor: pointer;
        transition: all 0.12s;
        background: var(--bg);
        color: var(--text);
        text-decoration: none;
    }

    .ml-chip:hover {
        background: var(--bg-alt);
        color: var(--text);
    }

    .ml-chip.active {
        background: #185FA5;
        color: #fff;
        border-color: #185FA5;
    }

    .ml-tag-chip {
        display: inline-flex;
        align-items: center;
        padding: 4px 9px;
        border-radius: 5px;
        font-size: 0.72rem;
        font-weight: 700;
        cursor: pointer;
        transition: opacity 0.12s, transform 0.1s;
        text-decoration: none;
        border: 2px solid transparent;
    }

    .ml-tag-chip:hover {
        opacity: 0.82;
        transform: translateY(-1px);
    }

    .ml-tag-chip.active {
        box-shadow: 0 0 0 2px var(--card), 0 0 0 4px currentColor;
    }

    /* Active filter badges */
    .ml-active-filters {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
        padding-top: 10px;
        border-top: 1px dashed var(--border);
        margin-top: 2px;
    }

    .ml-active-label {
        font-size: 0.72rem;
        color: var(--text-muted);
        font-weight: 600;
    }

    .ml-filter-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #E6F1FB;
        color: #0C447C;
        padding: 4px 9px;
        border-radius: 5px;
        font-size: 0.72rem;
        font-weight: 600;
    }

    .ml-filter-badge a {
        color: #185FA5;
        text-decoration: none;
        margin-left: 2px;
        font-size: 1rem;
        line-height: 1;
    }

    .ml-clear-all {
        font-size: 0.72rem;
        color: #185FA5;
        text-decoration: none;
        margin-left: 4px;
        font-weight: 600;
    }

    .ml-clear-all:hover {
        text-decoration: underline;
    }

    /* ===== MEDIA GRID ===== */
    .ml-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(165px, 1fr));
        gap: 12px;
    }

    .ml-media-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: border-color 0.15s, transform 0.12s, box-shadow 0.12s;
    }

    .ml-media-card:hover {
        border-color: #378ADD;
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(24, 95, 165, 0.08);
    }

    .ml-media-thumb {
        height: 130px;
        background: var(--bg-alt);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }

    .ml-media-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.2s;
    }

    .ml-media-card:hover .ml-media-thumb img {
        transform: scale(1.03);
    }

    .ml-thumb-icon {
        font-size: 2.2rem;
        opacity: 0.28;
    }

    .ml-type-badge {
        position: absolute;
        top: 8px;
        left: 8px;
        background: rgba(0, 0, 0, 0.52);
        color: #fff;
        font-size: 0.62rem;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 4px;
        letter-spacing: 0.04em;
    }

    .ml-media-info {
        padding: 10px 10px 6px;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .ml-media-name {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .ml-media-meta {
        font-size: 0.7rem;
        color: var(--text-muted);
    }

    .ml-media-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 3px;
        margin-top: 4px;
    }

    .ml-media-tag {
        font-size: 0.65rem;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 3px;
        text-decoration: none;
        transition: opacity 0.12s;
    }

    .ml-media-tag:hover {
        opacity: 0.8;
    }

    .ml-media-actions {
        padding: 0 8px 8px;
        display: flex;
        justify-content: flex-end;
    }

    .btn-ml-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        background: none;
        border: 1px solid var(--border);
        border-radius: 6px;
        cursor: pointer;
        color: var(--text-muted);
        transition: all 0.12s;
        font-size: 0.8rem;
    }

    .btn-ml-delete:hover {
        background: #FCEBEB;
        border-color: #F09595;
        color: #A32D2D;
    }

    /* ===== EMPTY STATE ===== */
    .ml-empty {
        text-align: center;
        padding: 3.5rem 1rem;
        color: var(--text-muted);
    }

    .ml-empty i {
        font-size: 3rem;
        display: block;
        margin-bottom: 12px;
        opacity: 0.22;
    }

    .ml-empty p {
        font-size: 0.875rem;
    }

    /* ===== PAGINATION ===== */
    .ml-pagination {
        display: flex;
        justify-content: center;
        margin-top: 1.5rem;
    }

    /* ===== UPLOAD MODAL ===== */
    .ml-modal-backdrop {
        position: fixed;
        inset: 0;
        display: none;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.42);
        z-index: 999;
        padding: 16px;
    }

    .ml-modal-backdrop.show {
        display: flex;
    }

    .ml-modal-box {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 14px;
        width: 100%;
        max-width: 460px;
        max-height: 90vh;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    .ml-modal-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 18px;
        border-bottom: 1px solid var(--border);
        position: sticky;
        top: 0;
        background: var(--card);
        z-index: 1;
    }

    .ml-modal-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text);
    }

    .btn-ml-modal-close {
        background: none;
        border: none;
        cursor: pointer;
        color: var(--text-muted);
        font-size: 1.3rem;
        line-height: 1;
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.12s;
        padding: 0;
    }

    .btn-ml-modal-close:hover {
        background: var(--bg-alt);
    }

    .ml-modal-body {
        padding: 18px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .ml-modal-footer {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
        padding: 12px 18px;
        border-top: 1px solid var(--border);
        position: sticky;
        bottom: 0;
        background: var(--card);
    }

    /* Drop zone */
    .ml-drop-zone {
        border: 2px dashed var(--border);
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.12s, background 0.12s;
        background: var(--bg);
    }

    .ml-drop-zone:hover,
    .ml-drop-zone.drag-over {
        border-color: #378ADD;
        background: rgba(55, 138, 221, 0.04);
    }

    .ml-drop-zone i {
        font-size: 1.6rem;
        color: var(--text-muted);
        display: block;
        margin-bottom: 8px;
        opacity: 0.6;
    }

    .ml-drop-zone p {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin: 0 0 2px;
    }

    .ml-drop-zone span {
        font-size: 0.75rem;
        color: #185FA5;
        font-weight: 600;
    }

    /* Form elements inside modal */
    .ml-form-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .ml-form-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .ml-form-hint {
        font-size: 0.7rem;
        color: var(--text-muted);
    }

    .ml-tag-search-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 6px;
    }

    .ml-tag-search-input {
        width: 150px;
        height: 28px;
        min-height: 28px;
        font-size: 0.75rem;
        padding: 2px 8px;
        border: 1px solid var(--border);
        border-radius: 6px;
        background: var(--bg);
        color: var(--text);
        outline: none;
    }

    .ml-tag-search-input:focus {
        border-color: #378ADD;
    }

    .ml-tags-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        max-height: 110px;
        overflow-y: auto;
        padding: 8px;
        border: 1px solid var(--border);
        border-radius: 7px;
        background: var(--bg);
    }

    .ml-tag-check-item {
        display: flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
    }

    .ml-tag-check-item input[type="checkbox"] {
        width: 13px;
        height: 13px;
        margin: 0;
        cursor: pointer;
        accent-color: #185FA5;
    }

    .ml-new-tag-row {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .ml-new-tag-row .form-control {
        flex: 1;
    }

    .ml-color-input {
        width: 38px;
        height: 34px;
        padding: 2px;
        border-radius: 6px;
        border: 1px solid var(--border);
        cursor: pointer;
        background: none;
        flex-shrink: 0;
    }

    .ml-divider {
        border: none;
        border-top: 1px solid var(--border);
        margin: 0;
    }

    .ml-check-label {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        font-size: 0.8rem;
        color: var(--text);
    }

    .ml-check-label input[type="checkbox"] {
        width: 14px;
        height: 14px;
        margin: 0;
        cursor: pointer;
        accent-color: #185FA5;
    }

    /* File preview and progress */
    #filePreview {
        animation: slideDown 0.3s ease-out;
    }

    #uploadProgress {
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .btn-ml-cancel {
        padding: 7px 14px;
        font-size: 0.8rem;
        border-radius: 7px;
        border: 1px solid var(--border);
        background: var(--bg-alt);
        color: var(--text);
        cursor: pointer;
        transition: background 0.12s;
        font-weight: 500;
    }

    .btn-ml-cancel:hover {
        background: var(--border);
    }

    .btn-ml-submit {
        padding: 7px 16px;
        font-size: 0.8rem;
        border-radius: 7px;
        border: none;
        background: #185FA5;
        color: #fff;
        cursor: pointer;
        font-weight: 600;
        transition: background 0.12s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-ml-submit:hover {
        background: #0C447C;
    }
</style>
@endsection

@section('content')

{{-- ===== TOPBAR ===== --}}
<div class="ml-topbar">
    <div class="ml-title-group">
        <div class="ml-icon-wrap">
            <i class="bi bi-images"></i>
        </div>
        <div>
            <p class="ml-page-title">Thư viện Media</p>
            <p class="ml-page-subtitle">
                {{ $media->total() }} tệp trong thư viện
            </p>
        </div>
    </div>
    <button class="btn-upload-new" onclick="openUploadModal()">
        <i class="bi bi-upload"></i> Upload file
    </button>
</div>

{{-- ===== FILTER PANEL ===== --}}
<div class="ml-filter-panel">

    {{-- Tìm kiếm --}}
    <div class="ml-filter-row">
        <span class="ml-filter-label">Tìm kiếm</span>
        <form action="{{ route('admin.media.index') }}" method="GET" class="ml-search-form">
            @foreach(request()->except('tu_khoa', 'page') as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <input type="text" name="tu_khoa" class="ml-search-input"
                value="{{ request('tu_khoa') }}" placeholder="Nhập tên file cần tìm...">
            <button type="submit" class="btn-ml-search">Tìm</button>
        </form>
    </div>

    {{-- Lọc loại tệp --}}
    <div class="ml-filter-row">
        <span class="ml-filter-label">Loại tệp</span>
        <a href="{{ route('admin.media.index', request()->except('loai_tep', 'page')) }}"
            class="ml-chip {{ !request('loai_tep') ? 'active' : '' }}">
            Tất cả
        </a>
        @foreach(['hinh_anh' => 'Hình ảnh', 'video' => 'Video', 'tai_lieu' => 'Tài liệu', 'khac' => 'Khác'] as $val => $lbl)
        @php $activeLoai = request('loai_tep') === $val; @endphp
        <a href="{{ route('admin.media.index', array_merge(request()->except('loai_tep','page'), $activeLoai ? [] : ['loai_tep' => $val])) }}"
            class="ml-chip {{ $activeLoai ? 'active' : '' }}">
            {{ $lbl }}
        </a>
        @endforeach
    </div>

    {{-- Lọc theo thẻ --}}
    @if($tags->count())
    <div class="ml-filter-row">
        <span class="ml-filter-label">Thẻ tên</span>
        @foreach($tags as $tag)
        @php
        $activeTag = request('the_anh') == $tag->ma_the_anh;
        $textColor = $tag->getContrastColor($tag->mau_sac) === '#000000' ? '#000' : '#fff';
        @endphp
        <a href="{{ route('admin.media.index', array_merge(request()->except('the_anh','page'), $activeTag ? [] : ['the_anh' => $tag->ma_the_anh])) }}"
            class="ml-tag-chip {{ $activeTag ? 'active' : '' }}"
            title="{{ $tag->ten_the }}"
            style="background-color: {{ $tag->mau_sac }}; color: {{ $textColor }}; {{ $activeTag ? 'opacity:1;' : 'opacity:0.75;' }}">
            @if($activeTag)<i class="bi bi-check" style="font-size:0.65rem;margin-right:2px;"></i>@endif
            {{ $tag->ten_the }}
        </a>
        @endforeach
    </div>
    @endif

    {{-- Active filters --}}
    @if(request('the_anh') || request('loai_tep') || request('tu_khoa'))
    <div class="ml-active-filters">
        <span class="ml-active-label">Đang lọc:</span>

        @if(request('tu_khoa'))
        <div class="ml-filter-badge" style="background:#D3D1C7;color:#2C2C2A;">
            Từ khóa: "{{ request('tu_khoa') }}"
            <a href="{{ route('admin.media.index', array_merge(request()->except('tu_khoa','page'))) }}">&times;</a>
        </div>
        @endif

        @if(request('loai_tep'))
        <div class="ml-filter-badge">
            {{ match(request('loai_tep')) {
                'hinh_anh' => 'Hình ảnh',
                'video'    => 'Video',
                'tai_lieu' => 'Tài liệu',
                default    => 'Khác'
            } }}
            <a href="{{ route('admin.media.index', array_merge(request()->except('loai_tep','page'))) }}">&times;</a>
        </div>
        @endif

        @if(request('the_anh'))
        @php $selectedTag = $tags->where('ma_the_anh', request('the_anh'))->first(); @endphp
        @if($selectedTag)
        <div class="ml-filter-badge" style="background-color: {{ $selectedTag->mau_sac }}20; color: {{ $selectedTag->mau_sac }};">
            {{ $selectedTag->ten_the }}
            <a href="{{ route('admin.media.index', array_merge(request()->except('the_anh','page'))) }}"
                style="color: {{ $selectedTag->mau_sac }};">&times;</a>
        </div>
        @endif
        @endif

        <a href="{{ route('admin.media.index') }}" class="ml-clear-all">Xóa tất cả</a>
    </div>
    @endif

</div>

{{-- ===== MEDIA GRID ===== --}}
@if($media->count())

<div class="ml-grid">
    @foreach($media as $m)
    <div class="ml-media-card">

        {{-- Thumbnail --}}
        <div class="ml-media-thumb">
            @if($m->loai_tep === 'hinh_anh')
            <img src="{{ asset('storage/'.$m->duong_dan_tep) }}"
                alt="{{ $m->ten_tep }}" loading="lazy">
            <span class="ml-type-badge">IMG</span>
            @elseif($m->loai_tep === 'video')
            <i class="bi bi-play-circle ml-thumb-icon" style="color:#5F5E5A;"></i>
            <span class="ml-type-badge">VID</span>
            @elseif($m->loai_tep === 'tai_lieu')
            <i class="bi bi-file-earmark-pdf ml-thumb-icon" style="color:#A32D2D;"></i>
            <span class="ml-type-badge">PDF</span>
            @else
            <i class="bi bi-file-earmark ml-thumb-icon" style="color:#888780;"></i>
            <span class="ml-type-badge">FILE</span>
            @endif
        </div>

        {{-- Info --}}
        <div class="ml-media-info">
            <div class="ml-media-name" title="{{ $m->ten_tep }}">{{ $m->ten_tep }}</div>
            <div class="ml-media-meta">{{ $m->loaiTepLabel }}</div>

            @if($m->theAnh->count())
            <div class="ml-media-tags">
                @foreach($m->theAnh as $tag)
                @php $tagText = $tag->getContrastColor($tag->mau_sac) === '#000000' ? '#000' : '#fff'; @endphp
                <a href="{{ route('admin.media.index', ['the_anh' => $tag->ma_the_anh]) }}"
                    class="ml-media-tag"
                    style="background-color: {{ $tag->mau_sac }}; color: {{ $tagText }};">
                    {{ $tag->ten_the }}
                </a>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="ml-media-actions">
            <form method="POST" action="{{ route('admin.media.destroy', $m->ma_phuong_tien) }}"
                onsubmit="return confirm('Xóa file này?')" style="margin:0;">
                @csrf @method('DELETE')
                <button class="btn-ml-delete" title="Xóa file">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>

    </div>
    @endforeach
</div>

@if($media->hasPages())
<div class="ml-pagination">{{ $media->links() }}</div>
@endif

@else

<div class="ml-empty">
    <i class="bi bi-images"></i>
    <p>{{ request('the_anh') || request('loai_tep') || request('tu_khoa')
        ? 'Không tìm thấy file với bộ lọc này.'
        : 'Chưa có file nào trong thư viện.' }}</p>
</div>

@endif

{{-- ===== UPLOAD MODAL ===== --}}
<div id="uploadModal" class="ml-modal-backdrop" onclick="if(event.target===this)closeUploadModal()">
    <div class="ml-modal-box">

        <div class="ml-modal-head">
            <span class="ml-modal-title">Upload file mới</span>
            <button class="btn-ml-modal-close" onclick="closeUploadModal()">&times;</button>
        </div>

        <form method="POST" action="{{ route('admin.media.upload') }}"
            enctype="multipart/form-data" class="ml-modal-body" id="uploadForm">
            @csrf

            {{-- Drop zone --}}
            <div class="ml-drop-zone" id="dropZone">
                <i class="bi bi-cloud-upload"></i>
                <p id="dropZoneText">Kéo thả file vào đây</p>
                <span id="dropZoneHint">hoặc nhấn để chọn file</span>
                <input type="file" name="file" id="fileInput" style="display:none">
            </div>

            {{-- File preview --}}
            <div id="filePreview" style="display:none;">
                {{-- Thông tin file --}}
                <div style="padding: 10px 12px; background: #E6F1FB; border-radius: 8px; border: 1px solid #378ADD; display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                    <i class="bi bi-check-circle" style="color: #185FA5; font-size: 1.2rem; flex-shrink:0;"></i>
                    <div style="min-width:0;">
                        <div style="font-weight: 600; color: #185FA5; font-size: 0.85rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                            <span id="selectedFileName"></span>
                        </div>
                        <div style="font-size: 0.75rem; color: #0C447C;">
                            <span id="selectedFileSize"></span> &nbsp;·&nbsp; <span id="selectedFileType"></span>
                        </div>
                    </div>
                    <button type="button" onclick="clearSelectedFile()" title="Chọn file khác"
                        style="margin-left:auto; background:none; border:none; color:#185FA5; cursor:pointer; font-size:0.75rem; white-space:nowrap; flex-shrink:0; padding:2px 6px; border-radius:5px; transition:background 0.12s;"
                        onmouseover="this.style.background='#c8dff7'" onmouseout="this.style.background='none'">
                        <i class="bi bi-arrow-repeat"></i> Đổi file
                    </button>
                </div>
                {{-- Tên hiển thị tùy chỉnh --}}
                <div class="ml-form-group">
                    <label class="ml-form-label" for="tenHienThi">
                        Tên hiển thị <span style="color:var(--text-muted); font-weight:400; text-transform:none; letter-spacing:0;">(có thể đặt lại)</span>
                    </label>
                    <input type="text" name="ten_tep_tuy_chinh" id="tenHienThi"
                        class="ml-search-input" style="width:100%; height:34px;"
                        placeholder="Nhập tên muốn hiển thị..." maxlength="255">
                    <span class="ml-form-hint">Để trống sẽ dùng tên file gốc. Không cần nhập phần đuôi mở rộng (.jpg, .pdf...)</span>
                </div>
            </div>

            {{-- Upload progress --}}
            <div id="uploadProgress" style="display:none;">
                <div style="font-size: 0.72rem; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">
                    Đang tải lên... <span id="progressPercent">0</span>%
                </div>
                <div style="width: 100%; height: 6px; background: var(--bg-alt); border-radius: 3px; overflow: hidden;">
                    <div id="progressBar" style="height: 100%; width: 0%; background: #185FA5; transition: width 0.3s ease;"></div>
                </div>
            </div>

            {{-- Loại file --}}
            <div class="ml-form-group">
                <label class="ml-form-label">Loại file</label>
                <select name="loai_tep" class="form-control" style="font-size:0.8rem;">
                    <option value="">Tự động nhận diện</option>
                    <option value="hinh_anh">Hình ảnh</option>
                    <option value="video">Video</option>
                    <option value="tai_lieu">Tài liệu</option>
                    <option value="khac">Khác</option>
                </select>
                <span class="ml-form-hint">Nếu để trống, hệ thống sẽ tự phân loại theo định dạng tệp.</span>
            </div>

            {{-- Tags --}}
            <div class="ml-form-group">
                <div class="ml-tag-search-row">
                    <label class="ml-form-label" style="margin:0;">Thẻ tên (Tags)</label>
                    <input type="text" id="tagSearchInput" class="ml-tag-search-input" placeholder="Tìm nhanh thẻ...">
                </div>
                <div class="ml-tags-grid" id="tagsContainer">
                    @foreach($tags as $tag)
                    @php $tagText = $tag->getContrastColor($tag->mau_sac) === '#000000' ? '#000' : '#fff'; @endphp
                    <label class="ml-tag-check-item">
                        <input type="checkbox" name="the_anh[]" value="{{ $tag->ma_the_anh }}">
                        <span style="background-color: {{ $tag->mau_sac }}; color: {{ $tagText }};
                                         padding: 2px 7px; border-radius: 4px; font-size: 0.7rem; font-weight: 700;">
                            {{ $tag->ten_the }}
                        </span>
                    </label>
                    @endforeach
                </div>
                <span class="ml-form-hint">Chọn một hoặc nhiều thẻ để phân loại file.</span>
            </div>

            <hr class="ml-divider">

            {{-- Tạo thẻ mới --}}
            <div class="ml-form-group">
                <label class="ml-form-label">Tạo thẻ mới (tùy chọn)</label>
                <div class="ml-new-tag-row">
                    <input type="text" name="ten_the_moi" id="tenTheMoi"
                        class="form-control" placeholder="Tên thẻ mới..."
                        style="font-size:0.8rem;">
                    <input type="color" name="mau_sac_moi" id="mauSacMoi"
                        class="ml-color-input" value="#185FA5" title="Chọn màu thẻ">
                </div>
                <span class="ml-form-hint">Nhập tên thẻ và chọn màu để tạo thẻ mới khi upload.</span>
            </div>

        </form>

        <div class="ml-modal-footer">
            <button type="button" class="btn-ml-cancel" onclick="closeUploadModal()">Hủy</button>
            <button type="submit" form="uploadForm" class="btn-ml-submit">
                <i class="bi bi-upload"></i> Upload
            </button>
        </div>

    </div>
</div>

@endsection

<script>
    // ===== MODAL =====
    function openUploadModal() {
        document.getElementById('uploadModal').classList.add('show');
        resetUploadForm();
        loadTags();
        initializeTagSearch();
        initializeDropZoneListeners();
    }

    function closeUploadModal() {
        document.getElementById('uploadModal').classList.remove('show');
    }

    function resetUploadForm() {
        const fileInput = document.getElementById('fileInput');
        const filePreview = document.getElementById('filePreview');
        const uploadProgress = document.getElementById('uploadProgress');
        const submitBtn = document.querySelector('[form="uploadForm"]');
        const dropZone = document.getElementById('dropZone');
        const tenHienThi = document.getElementById('tenHienThi');

        // Reset toàn bộ form
        const form = document.getElementById('uploadForm');
        if (form) form.reset();

        // Clear file input
        if (fileInput) {
            const dataTransfer = new DataTransfer();
            fileInput.files = dataTransfer.files;
        }

        // Reset dropZone
        if (dropZone) {
            dropZone.style.opacity = '1';
            dropZone.style.pointerEvents = '';
            dropZone.classList.remove('drag-over');
            const text = document.getElementById('dropZoneText');
            const hint = document.getElementById('dropZoneHint');
            if (text) text.textContent = 'Kéo thả file vào đây';
            if (hint) hint.textContent = 'hoặc nhấn để chọn file';
        }

        // Reset tên tùy chỉnh
        if (tenHienThi) { tenHienThi.value = ''; tenHienThi.dataset.userEdited = ''; }

        // Reset submit button
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-upload"></i> Upload';
        }

        // Ẩn preview và progress
        if (filePreview) filePreview.style.display = 'none';
        if (uploadProgress) {
            uploadProgress.style.display = 'none';
            uploadProgress.innerHTML = '';
        }
    }

    // ===== LOAD TAGS từ server =====
    function loadTags() {
        fetch('{{ route("admin.media.tags.json") }}')
            .then(res => res.json())
            .then(tags => {
                const container = document.getElementById('tagsContainer');
                container.innerHTML = '';

                tags.forEach(tag => {
                    const textColor = getContrastColor(tag.mau_sac);
                    const label = document.createElement('label');
                    label.className = 'ml-tag-check-item';
                    label.innerHTML = `
                        <input type="checkbox" name="the_anh[]" value="${tag.ma_the_anh}">
                        <span style="background-color:${tag.mau_sac};color:${textColor};
                                     padding:2px 7px;border-radius:4px;font-size:0.7rem;font-weight:700;">
                            ${tag.ten_the}
                        </span>
                    `;
                    container.appendChild(label);
                });

                // Re-apply search filter if active
                document.getElementById('tagSearchInput').dispatchEvent(new Event('input'));
            })
            .catch(err => console.error('Error loading tags:', err));
    }

    // ===== TAG SEARCH FILTER =====
    function initializeTagSearch() {
        const tagSearchInput = document.getElementById('tagSearchInput');
        if (!tagSearchInput) return;

        tagSearchInput.addEventListener('input', function() {
            const term = this.value.toLowerCase().trim();
            document.querySelectorAll('#tagsContainer .ml-tag-check-item').forEach(item => {
                const name = item.querySelector('span').textContent.toLowerCase();
                item.style.display = name.includes(term) ? 'flex' : 'none';
            });
        });
    }

    // ===== DROP ZONE LISTENERS =====
    function initializeDropZoneListeners() {
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');

        if (!dropZone || !fileInput) return;

        // Chỉ gắn listener 1 lần duy nhất (tránh duplicate khi mở modal nhiều lần)
        if (dropZone.dataset.initialized === '1') return;
        dropZone.dataset.initialized = '1';

        // Click vào drop zone → mở file picker
        // Không dùng preventDefault/stopPropagation để browser cho phép mở file picker
        dropZone.addEventListener('click', () => {
            fileInput.click();
        });

        // Drag enter
        dropZone.addEventListener('dragenter', (e) => {
            e.preventDefault();
            dropZone.classList.add('drag-over');
        });

        // Drag over
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('drag-over');
        });

        // Drag leave
        dropZone.addEventListener('dragleave', (e) => {
            if (e.target === dropZone) {
                dropZone.classList.remove('drag-over');
            }
        });

        // Drop handler
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');

            if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                const dataTransfer = new DataTransfer();
                for (let i = 0; i < e.dataTransfer.files.length; i++) {
                    dataTransfer.items.add(e.dataTransfer.files[i]);
                }
                fileInput.files = dataTransfer.files;
                displayFileInfo(e.dataTransfer.files[0]);
            }
        });

        // File input change
        fileInput.addEventListener('change', (e) => {
            if (e.target.files && e.target.files.length > 0) {
                displayFileInfo(e.target.files[0]);
            }
        });
    }

    // Display file information
    function displayFileInfo(file) {
        document.getElementById('selectedFileName').textContent = file.name;
        document.getElementById('selectedFileSize').textContent = formatFileSize(file.size);
        document.getElementById('selectedFileType').textContent = file.type || 'Không xác định';

        // Tự điền tên file gốc (bỏ phần đuôi mở rộng) vào ô đặt tên
        const tenHienThi = document.getElementById('tenHienThi');
        if (tenHienThi && !tenHienThi.dataset.userEdited) {
            const dotIdx = file.name.lastIndexOf('.');
            tenHienThi.value = dotIdx > 0 ? file.name.substring(0, dotIdx) : file.name;
        }
        // Reset flag khi chọn file mới
        if (tenHienThi) tenHienThi.dataset.userEdited = '';

        const fp = document.getElementById('filePreview');
        const dz = document.getElementById('dropZone');
        if (fp) fp.style.display = 'block';
        if (dz) { dz.style.opacity = '0.6'; dz.style.pointerEvents = 'none'; }
        const dzText = document.getElementById('dropZoneText');
        if (dzText) dzText.textContent = 'Tệp đã được chọn ✓';
    }

    // Xóa file đã chọn, cho phép chọn lại
    function clearSelectedFile() {
        const fileInput = document.getElementById('fileInput');
        const filePreview = document.getElementById('filePreview');
        const dz = document.getElementById('dropZone');
        const tenHienThi = document.getElementById('tenHienThi');

        if (fileInput) fileInput.value = '';
        if (filePreview) filePreview.style.display = 'none';
        if (dz) { dz.style.opacity = '1'; dz.style.pointerEvents = ''; }
        if (tenHienThi) { tenHienThi.value = ''; tenHienThi.dataset.userEdited = ''; }

        const dzText = document.getElementById('dropZoneText');
        if (dzText) dzText.textContent = 'Kéo thả file vào đây';
        const dzHint = document.getElementById('dropZoneHint');
        if (dzHint) dzHint.textContent = 'hoặc nhấn để chọn file';
    }

    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i];
    }

    // ===== FORM SUBMISSION =====
    document.addEventListener('submit', function(e) {
        const uploadForm = document.getElementById('uploadForm');
        if (e.target !== uploadForm) return;

        e.preventDefault();

        // Lấy lại các element (có thể đã bị clone)
        const fileInputElem = uploadForm.querySelector('#fileInput') || document.getElementById('fileInput');
        const submitBtn = document.querySelector('[form="uploadForm"]');
        const tenTheMoi = document.getElementById('tenTheMoi');
        const filePreviewEl = document.getElementById('filePreview');
        const uploadProgress = document.getElementById('uploadProgress');

        if (!fileInputElem || !submitBtn || !uploadProgress) {
            alert('Lỗi: Không tìm thấy các element cần thiết.');
            return;
        }

        const theMoiValue = tenTheMoi ? tenTheMoi.value.trim() : '';

        // Kiểm tra file
        if (!fileInputElem.files || !fileInputElem.files.length) {
            alert('Vui lòng chọn file để upload.');
            return;
        }

        // Kiểm tra tên thẻ
        if (theMoiValue && theMoiValue.length > 100) {
            alert('Tên thẻ không được vượt quá 100 ký tự.');
            return;
        }

        // Vô hiệu hóa nút submit
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang upload...';

        // Ẩn file preview, hiển thị progress
        if (filePreviewEl) filePreviewEl.style.display = 'none';
        uploadProgress.style.display = 'block';
        uploadProgress.innerHTML = `
            <div style="font-size: 0.72rem; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">
                Đang tải lên... <span id="progressPercent">0</span>%
            </div>
            <div style="width: 100%; height: 6px; background: var(--bg-alt); border-radius: 3px; overflow: hidden;">
                <div id="progressBar" style="height: 100%; width: 0%; background: #185FA5; transition: width 0.3s ease;"></div>
            </div>
        `;

        // Gửi form bằng fetch
        const formData = new FormData(uploadForm);

        // Thêm file thủ công nếu cần (đảm bảo file được đính kèm)
        if (!formData.has('file') || !formData.get('file').size) {
            formData.set('file', fileInputElem.files[0]);
        }

        fetch(uploadForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                const progressBar = document.getElementById('progressBar');
                const progressPercent = document.getElementById('progressPercent');
                if (progressBar) progressBar.style.width = '100%';
                if (progressPercent) progressPercent.textContent = '100';

                if (!response.ok) {
                    return response.text().then(text => {
                        // Cố parse JSON lỗi validation từ Laravel
                        try {
                            const json = JSON.parse(text);
                            const msgs = json.errors
                                ? Object.values(json.errors).flat().join(' | ')
                                : (json.message || text);
                            throw new Error(msgs);
                        } catch(jsonErr) {
                            if (jsonErr instanceof SyntaxError) throw new Error(text.substring(0, 300));
                            throw jsonErr;
                        }
                    });
                }
                return response.text();
            })
            .then(html => {
                uploadProgress.innerHTML = `
                <div style="padding: 12px; background: #E6F8EF; border-radius: 8px; border: 1px solid #52C282; color: #28724E;">
                    <div style="display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 0.9rem;">
                        <i class="bi bi-check-circle" style="font-size: 1.2rem;"></i>
                        <span>Upload thành công! Đang tải lại...</span>
                    </div>
                </div>
            `;
                setTimeout(() => {
                    closeUploadModal();
                    location.reload();
                }, 1500);
            })
            .catch(error => {
                console.error('Upload error:', error);
                uploadProgress.innerHTML = `
                <div style="padding: 12px; background: #FCEBEB; border-radius: 8px; border: 1px solid #F09595; color: #A32D2D;">
                    <div style="display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 0.9rem;">
                        <i class="bi bi-exclamation-circle" style="font-size: 1.2rem;"></i>
                        <span>Lỗi: ${error.message}</span>
                    </div>
                </div>
            `;
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-upload"></i> Upload';
            });
    }, false);

    // ===== CONTRAST COLOR HELPER =====
    function getContrastColor(hex) {
        const h = hex.replace('#', '');
        const r = parseInt(h.substr(0, 2), 16);
        const g = parseInt(h.substr(2, 2), 16);
        const b = parseInt(h.substr(4, 2), 16);
        return (r * 299 + g * 587 + b * 114) / 1000 > 128 ? '#000000' : '#FFFFFF';
    }
</script>