@extends('admin.layout')

@section('title', 'Thư viện Media')
@section('page-title', 'Thư viện Media')

@section('styles')
<style>
    .modal-layer {
        position: fixed;
        inset: 0;
        display: none;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.4);
        z-index: 999;
        padding: 18px;
    }

    .modal-layer.show {
        display: flex;
    }

    .modal-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--border-radius-md);
        width: 100%;
        max-width: 480px;
        overflow: hidden;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-card header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        background: var(--card);
    }

    .modal-card .body {
        padding: 20px;
    }

    .media-pagination {
        margin-top: var(--space-xl);
        display: flex;
        justify-content: center;
    }

    .tag-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-right: 4px;
        margin-bottom: 4px;
    }

    .tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-top: 8px;
        margin-bottom: 12px;
    }

    .tag-select-group {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
    }

    .tag-checkbox {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.875rem;
        padding: 6px 10px;
        border: 1px solid var(--border);
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .tag-checkbox input[type="checkbox"] {
        margin: 0;
    }

    .tag-checkbox:hover {
        background-color: var(--bg-alt);
    }

    .tag-checkbox input[type="checkbox"]:checked+label {
        font-weight: 600;
    }

    .new-tag-section {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid var(--border);
    }

    .filter-section {
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--border);
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .filter-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background-color: var(--accent);
        color: white;
        border-radius: 4px;
        font-size: 0.875rem;
    }

    .filter-badge a {
        cursor: pointer;
        color: white;
        margin-left: 4px;
    }
</style>
@endsection

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-images" style="color:var(--accent)"></i> Thư viện đa phương tiện</div>
        <button class="btn btn-primary" onclick="document.getElementById('uploadModal').classList.add('show')">
            <i class="bi bi-upload"></i> Upload file
        </button>
    </div>

    <div class="card-body">
        <p class="text-sm text-muted" style="margin-bottom:16px;">Thư viện này hiển thị các tệp gốc để tái sử dụng. Ảnh được gắn vào sự kiện sẽ không tạo thêm bản sao trong kho media.</p>

        <!-- Filter Section -->
        <div class="filter-section">
            <label class="text-sm" style="font-weight: 600; margin: 0;">Lọc:</label>

            @if(request('the_anh') || request('loai_tep'))
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                @if(request('the_anh'))
                @php
                $selectedTag = $tags->where('ma_the_anh', request('the_anh'))->first();
                @endphp
                @if($selectedTag)
                <div class="filter-badge" style="background-color: {{ $selectedTag->mau_sac }};">
                    {{ $selectedTag->ten_the }}
                    <a href="{{ route('admin.media.index', array_merge(request()->query(), ['the_anh' => null])) }}">&times;</a>
                </div>
                @endif
                @endif
                @if(request('loai_tep'))
                <div class="filter-badge">
                    {{ match(request('loai_tep')) { 'hinh_anh' => 'Hình ảnh', 'video' => 'Video', 'tai_lieu' => 'Tài liệu', default => 'Khác' } }}
                    <a href="{{ route('admin.media.index', array_merge(request()->query(), ['loai_tep' => null])) }}">&times;</a>
                </div>
                @endif
            </div>
            <a href="{{ route('admin.media.index') }}" class="text-sm" style="color: var(--accent); cursor: pointer;">Xóa tất cả</a>
            @else
            <span class="text-sm text-muted">Chưa có bộ lọc</span>
            @endif
        </div>

        @if($media->count())
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;">
            @foreach($media as $m)
            <div style="border:1px solid var(--border);background:var(--card);transition:border-color 0.2s;display:flex;flex-direction:column;">
                @if($m->loai_tep === 'hinh_anh')
                <div style="height:140px;border-bottom:1px solid var(--border);">
                    <img src="{{ asset('storage/'.$m->duong_dan_tep) }}" alt="{{ $m->ten_tep }}" style="width:100%;height:100%;object-fit:cover;">
                </div>
                @else
                <div style="height:140px;background:var(--bg-alt);border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-{{ $m->loai_tep==='video' ? 'play-circle' : ($m->loai_tep==='tai_lieu' ? 'file-earmark-pdf' : 'file-earmark') }}"
                        style="font-size:36px;color:var(--border);"></i>
                </div>
                @endif
                <div style="padding:12px;flex:1;display:flex;flex-direction:column;">
                    <div style="font-size:0.875rem;font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $m->ten_tep }}">{{ $m->ten_tep }}</div>
                    <div class="text-xs text-muted" style="margin-top:2px;">{{ $m->loaiTepLabel }}</div>

                    @if($m->theAnh->count())
                    <div class="tags-container">
                        @foreach($m->theAnh as $tag)
                        <a href="{{ route('admin.media.index', ['the_anh' => $tag->ma_the_anh]) }}" class="tag-badge" style="background-color: {{ $tag->mau_sac }}; color: {{ $tag->getContrastColor($tag->mau_sac) === '#000000' ? '#000' : '#fff' }}; text-decoration: none;">
                            {{ $tag->ten_the }}
                        </a>
                        @endforeach
                    </div>
                    @endif

                    <div style="margin-top:auto;display:flex;justify-content:flex-end;gap:6px;">
                        <form method="POST" action="{{ route('admin.media.destroy', $m->ma_phuong_tien) }}" onsubmit="return confirm('Xóa file này?')" style="margin:0;">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($media->hasPages())
        <div class="media-pagination">{{ $media->links() }}</div>
        @endif
        @else
        <div style="text-align:center;padding:var(--space-3xl);color:var(--text-muted);">
            <i class="bi bi-images" style="font-size:48px;display:block;margin-bottom:12px;opacity:0.3;"></i>
            <p>{{ request('the_anh') || request('loai_tep') ? 'Không tìm thấy file với bộ lọc này' : 'Chưa có file nào trong thư viện' }}</p>
        </div>
        @endif
    </div>
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="modal-layer">
    <div class="modal-card">
        <header>
            <h3 style="font-size:1rem;font-weight:600;">Upload file</h3>
            <button onclick="document.getElementById('uploadModal').classList.remove('show')" style="background:none;border:none;font-size:18px;cursor:pointer;color:var(--text-light);">&times;</button>
        </header>
        <form method="POST" action="{{ route('admin.media.upload') }}" enctype="multipart/form-data" class="body" id="uploadForm">
            @csrf
            <div class="form-group">
                <label class="form-label">Chọn file *</label>
                <input type="file" name="file" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Loại file</label>
                <select name="loai_tep" class="form-control">
                    <option value="">Tự động nhận diện</option>
                    <option value="hinh_anh">Hình ảnh</option>
                    <option value="video">Video</option>
                    <option value="tai_lieu">Tài liệu</option>
                    <option value="khac">Khác</option>
                </select>
                <div class="text-xs text-muted" style="margin-top:6px;">Nếu để trống, hệ thống sẽ tự phân loại theo định dạng tệp.</div>
            </div>

            <!-- Tags Section -->
            <div class="form-group">
                <label class="form-label">Thẻ tên (Tags)</label>
                <div class="tag-select-group" id="tagsContainer">
                    @foreach($tags as $tag)
                    <label class="tag-checkbox">
                        <input type="checkbox" name="the_anh[]" value="{{ $tag->ma_the_anh }}">
                        <span style="background-color: {{ $tag->mau_sac }}; color: white; padding: 2px 6px; border-radius: 3px; font-size: 0.75rem;">{{ $tag->ten_the }}</span>
                    </label>
                    @endforeach
                </div>
                <div class="text-xs text-muted" style="margin-top:6px;">Chọn một hoặc nhiều thẻ để phân loại file</div>
            </div>

            <!-- Create New Tag Section -->
            <div class="new-tag-section">
                <label class="form-label">Tạo thẻ mới</label>
                <div class="form-group" style="margin-bottom: 0;">
                    <input type="text" name="ten_the_moi" id="tenTheMoi" class="form-control" placeholder="Tên thẻ mới (tùy chọn)" style="margin-bottom: 8px;">
                    <input type="color" name="mau_sac_moi" id="mauSacMoi" class="form-control" value="#007bff" style="height: 40px; cursor: pointer;">
                    <div class="text-xs text-muted" style="margin-top:6px;">Nhập tên thẻ và chọn màu để tạo thẻ mới khi upload</div>
                </div>
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:0.875rem;">
                    <input type="checkbox" name="la_cong_khai" value="1"> Công khai
                </label>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:var(--space-md);">
                <button type="button" onclick="document.getElementById('uploadModal').classList.remove('show')" class="btn btn-secondary">Hủy</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Upload</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Reload tags từ server khi modal mở
        const uploadModal = document.getElementById('uploadModal');
        uploadModal.addEventListener('click', function(e) {
            if (e.target === uploadModal) return;
            setTimeout(() => {
                if (uploadModal.classList.contains('show')) {
                    loadTags();
                }
            }, 100);
        });

        // Hoặc reload tags khi nhấn nút upload
        document.querySelector('[onclick*="uploadModal"]').addEventListener('click', function() {
            loadTags();
        });
    });

    function loadTags() {
        fetch('{{ route("admin.media.tags.json") }}')
            .then(response => response.json())
            .then(tags => {
                const container = document.getElementById('tagsContainer');
                container.innerHTML = '';

                tags.forEach(tag => {
                    const label = document.createElement('label');
                    label.className = 'tag-checkbox';

                    const textColor = getContrastColor(tag.mau_sac);
                    label.innerHTML = `
                    <input type="checkbox" name="the_anh[]" value="${tag.ma_the_anh}">
                    <span style="background-color: ${tag.mau_sac}; color: ${textColor}; padding: 2px 6px; border-radius: 3px; font-size: 0.75rem;">${tag.ten_the}</span>
                `;
                    container.appendChild(label);
                });
            })
            .catch(error => console.error('Error loading tags:', error));
    }

    function getContrastColor(hexColor) {
        const hex = hexColor.replace('#', '');
        const r = parseInt(hex.substr(0, 2), 16);
        const g = parseInt(hex.substr(2, 2), 16);
        const b = parseInt(hex.substr(4, 2), 16);
        const luminance = (r * 299 + g * 587 + b * 114) / 1000;
        return luminance > 128 ? '#000000' : '#FFFFFF';
    }

    // Validate new tag name trước khi submit
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        const tenTheMoi = document.getElementById('tenTheMoi').value.trim();
        const theAnhInputs = document.querySelectorAll('input[name="the_anh[]"]:checked');

        if (!tenTheMoi && theAnhInputs.length === 0) {
            // Không có tag được chọn và không tạo tag mới - điều này được phép
        }

        if (tenTheMoi && tenTheMoi.length > 100) {
            e.preventDefault();
            alert('Tên thẻ không được vượt quá 100 ký tự');
        }
    });
</script>
@endpush
@endsection