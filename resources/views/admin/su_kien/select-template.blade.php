@extends('admin.layout')

@section('title', 'Chọn mẫu bài đăng')
@section('page-title', 'Chọn mẫu bài đăng')

@section('styles')
<style>
    .template-chooser {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: var(--space-lg);
    }

    .chooser-card {
        border: 1px solid var(--border);
        border-radius: var(--border-radius-md);
        background: var(--card);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .chooser-cover {
        height: 160px;
        background: linear-gradient(135deg, var(--bg-alt), var(--bg));
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid var(--border);
    }

    .chooser-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .chooser-body {
        padding: var(--space-lg);
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
        height: 100%;
    }

    .chooser-tags {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .chooser-tags span {
        border: 1px solid var(--border);
        border-radius: 999px;
        padding: 5px 10px;
        background: var(--bg-alt);
        font-size: .78rem;
    }
</style>
@endsection

@section('content')
<div class="card mb-lg">
    <div class="card-body">
        <div style="display:flex;justify-content:space-between;gap:var(--space-lg);align-items:flex-start;flex-wrap:wrap;">
            <div>
                <h2 style="margin-bottom:8px;">Bước 1: chọn mẫu trước khi tạo bài đăng</h2>
                <p class="text-muted" style="max-width:760px;">
                    Mỗi mẫu quyết định bài đăng sẽ có những module nào. Bạn có thể chọn mẫu có 2 banner, nhiều khối nội dung hoặc nhiều gallery khác nhau, sau đó mới sang bước nhập dữ liệu chi tiết cho bài đăng.
                </p>
            </div>
            <a href="{{ route('admin.templates.index') }}" class="btn btn-outline">
                <i class="bi bi-sliders"></i> Quản lý mẫu
            </a>
        </div>
    </div>
</div>

<div class="template-chooser">
    @foreach($templates as $template)
        <div class="chooser-card">
            <div class="chooser-cover">
                @if($template->anh_su_kien)
                    <img src="{{ asset('storage/' . $template->anh_su_kien) }}" alt="{{ $template->ten_mau }}">
                @else
                    <i class="bi bi-layout-text-window" style="font-size:42px;color:var(--text-muted);"></i>
                @endif
            </div>
            <div class="chooser-body">
                <div style="font-size:1.05rem;font-weight:700;">{{ $template->ten_mau }}</div>
                <div class="text-muted">
                    {{ \Illuminate\Support\Str::limit(strip_tags($template->noi_dung), 160) ?: 'Mẫu không có ghi chú thêm.' }}
                </div>
                <div class="chooser-tags">
                    <span>{{ count($template->normalized_modules) }} module</span>
                    <span>{{ $template->loaiSuKien->ten_loai ?? 'Mọi loại sự kiện' }}</span>
                    @if($template->diem_cong)<span>+{{ $template->diem_cong }} điểm</span>@endif
                </div>
                <div class="chooser-tags">
                    @foreach($template->normalized_modules as $module)
                        <span>{{ $module['title'] }}</span>
                    @endforeach
                </div>
                <div style="margin-top:auto;">
                    <a href="{{ route('admin.su-kien.create', ['template_id' => $template->ma_mau]) }}" class="btn btn-primary w-full">
                        <i class="bi bi-arrow-right-circle"></i> Dùng mẫu này
                    </a>
                </div>
            </div>
        </div>
    @endforeach

    <div class="chooser-card">
        <div class="chooser-cover">
            <i class="bi bi-stars" style="font-size:42px;color:var(--accent);"></i>
        </div>
        <div class="chooser-body">
            <div style="font-size:1.05rem;font-weight:700;">Bài đăng tùy chỉnh</div>
            <div class="text-muted">
                Tạo bài đăng với bộ 5 module chuẩn rồi chỉnh nội dung thủ công. Phù hợp khi bạn chưa muốn dùng mẫu có sẵn.
            </div>
            <div class="chooser-tags">
                <span>5 module chuẩn</span>
                <span>Không gắn mẫu</span>
            </div>
            <div style="margin-top:auto;">
                <a href="{{ route('admin.su-kien.create', ['custom' => 1]) }}" class="btn btn-outline w-full">
                    <i class="bi bi-pencil-square"></i> Tạo từ bố cục mặc định
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
