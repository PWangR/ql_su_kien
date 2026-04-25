@extends('admin.layout')

@section('title', 'Chi tiết sự kiện')
@section('page-title', 'Chi tiết sự kiện')

@php
    $modules = \App\Support\EventTemplateSupport::normalizeTemplateModules($suKien->bo_cuc);
@endphp

@section('styles')
<style>
    .event-admin-grid {
        display: grid;
        grid-template-columns: 1.6fr .9fr;
        gap: var(--space-lg);
    }

    .module-card {
        border: 1px solid var(--border);
        border-radius: var(--border-radius-md);
        background: var(--card);
        padding: var(--space-lg);
        margin-bottom: var(--space-md);
    }

    .module-card img {
        width: 100%;
        max-height: 360px;
        object-fit: cover;
        border: 1px solid var(--border);
        border-radius: var(--border-radius);
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 12px;
    }

    .gallery-grid img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: var(--border-radius);
        border: 1px solid var(--border);
    }

    @media (max-width: 991.98px) {
        .event-admin-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div style="display:flex;justify-content:space-between;gap:var(--space-md);margin-bottom:var(--space-lg);flex-wrap:wrap;">
    <div>
        <h2 style="margin-bottom:8px;">{{ $suKien->ten_su_kien }}</h2>
        <div class="text-muted">Bố cục bài đăng hiện tại được render theo module đã lưu trong sự kiện.</div>
    </div>
    <div style="display:flex;gap:var(--space-sm);">
        <a href="{{ route('admin.su-kien.edit', $suKien->ma_su_kien) }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Chỉnh sửa
        </a>
        <a href="{{ route('admin.su-kien.index') }}" class="btn btn-outline">Quay lại</a>
    </div>
</div>

<div class="event-admin-grid">
    <div>
        @foreach($modules as $module)
            @php
                $type = $module['type'] ?? null;
                $content = $module['content'] ?? [];
                $title = $module['title'] ?? '';
            @endphp

            @if($type === 'banner')
                @php $bannerPath = $content['image_path'] ?? $suKien->anh_su_kien; @endphp
                @if($bannerPath)
                    <div class="module-card">
                        <div style="font-weight:700;margin-bottom:10px;">{{ $title ?: 'Banner' }}</div>
                        <img src="{{ asset('storage/' . $bannerPath) }}" alt="{{ $suKien->ten_su_kien }}">
                        @if(!empty($content['caption']))
                            <div class="text-muted" style="margin-top:10px;">{{ $content['caption'] }}</div>
                        @endif
                    </div>
                @endif
            @endif

            @if($type === 'header')
                <div class="module-card">
                    <div style="font-weight:700;margin-bottom:10px;">{{ $title ?: 'Header' }}</div>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:10px;">
                        <span class="badge bg-primary">{{ $suKien->loaiSuKien->ten_loai ?? 'Sự kiện' }}</span>
                        <span class="badge bg-{{ $suKien->trang_thai_color }}">{{ $suKien->trang_thai_label }}</span>
                        @if(!empty($content['badge']))
                            <span class="badge bg-secondary">{{ $content['badge'] }}</span>
                        @endif
                    </div>
                    <h3 style="margin-bottom:8px;">{{ $content['title'] ?? $suKien->ten_su_kien }}</h3>
                    @if(!empty($content['subtitle']))
                        <div class="text-muted">{!! nl2br(e($content['subtitle'])) !!}</div>
                    @endif
                </div>
            @endif

            @if($type === 'info')
                @php $items = $content['items'] ?? ['time', 'location', 'capacity', 'points']; @endphp
                <div class="module-card">
                    <div style="font-weight:700;margin-bottom:10px;">{{ $title ?: 'Thông tin' }}</div>
                    <div style="display:grid;gap:10px;">
                        @if(in_array('time', $items, true))
                            <div><strong>Thời gian:</strong> {{ $suKien->thoi_gian_bat_dau?->format('H:i d/m/Y') }} - {{ $suKien->thoi_gian_ket_thuc?->format('H:i d/m/Y') }}</div>
                        @endif
                        @if(in_array('location', $items, true))
                            <div><strong>Địa điểm:</strong> {{ $suKien->dia_diem ?: 'Chưa cập nhật' }}</div>
                        @endif
                        @if(in_array('capacity', $items, true))
                            <div><strong>Số lượng:</strong> {{ $suKien->so_luong_hien_tai }}/{{ $suKien->so_luong_toi_da ?: 'Không giới hạn' }}</div>
                        @endif
                        @if(in_array('points', $items, true))
                            <div><strong>Điểm cộng:</strong> +{{ $suKien->diem_cong }}</div>
                        @endif
                    </div>
                    @if(!empty($content['custom_note']))
                        <div class="text-muted" style="margin-top:10px;">{!! nl2br(e($content['custom_note'])) !!}</div>
                    @endif
                </div>
            @endif

            @if($type === 'description')
                @php $body = $content['body'] ?? null; @endphp
                @if($body || $suKien->mo_ta_chi_tiet)
                    <div class="module-card">
                        <div style="font-weight:700;margin-bottom:10px;">{{ $content['heading'] ?? $title ?: 'Nội dung' }}</div>
                        <div style="line-height:1.8;">
                            @if($body)
                                {!! nl2br(e($body)) !!}
                            @else
                                {!! $suKien->mo_ta_chi_tiet !!}
                            @endif
                        </div>
                    </div>
                @endif
            @endif

            @if($type === 'gallery')
                @php
                    $galleryImages = $content['images'] ?? [];
                    if (empty($galleryImages)) {
                        $galleryImages = $suKien->media->where('loai_tep', 'hinh_anh')->pluck('duong_dan_tep')->values()->all();
                    }
                @endphp
                @if(!empty($galleryImages))
                    <div class="module-card">
                        <div style="font-weight:700;margin-bottom:10px;">{{ $title ?: 'Gallery' }}</div>
                        <div class="gallery-grid">
                            @foreach($galleryImages as $imagePath)
                                <a href="{{ asset('storage/' . $imagePath) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $imagePath) }}" alt="Ảnh sự kiện">
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        @endforeach
    </div>

    <div>
        <div class="module-card">
            <div style="font-weight:700;margin-bottom:12px;">Thông tin hệ thống</div>
            <div style="display:grid;gap:10px;">
                <div><strong>ID:</strong> #{{ $suKien->ma_su_kien }}</div>
                <div><strong>Loại:</strong> {{ $suKien->loaiSuKien->ten_loai ?? 'Chưa phân loại' }}</div>
                <div><strong>Trạng thái:</strong> {{ $suKien->trang_thai_label }}</div>
                <div><strong>Bắt đầu:</strong> {{ $suKien->thoi_gian_bat_dau?->format('H:i d/m/Y') }}</div>
                <div><strong>Kết thúc:</strong> {{ $suKien->thoi_gian_ket_thuc?->format('H:i d/m/Y') }}</div>
                <div><strong>Địa điểm:</strong> {{ $suKien->dia_diem ?: 'Chưa cập nhật' }}</div>
                <div><strong>Số lượng hiện tại:</strong> {{ $suKien->so_luong_hien_tai }}/{{ $suKien->so_luong_toi_da ?: 'Không giới hạn' }}</div>
                <div><strong>Điểm cộng:</strong> +{{ $suKien->diem_cong }}</div>
            </div>
        </div>

        <div class="module-card">
            <div style="font-weight:700;margin-bottom:12px;">Người tham gia</div>
            <div class="text-muted" style="margin-bottom:10px;">Tổng đăng ký: {{ $suKien->dangKy->count() }}</div>
            @forelse($suKien->dangKy as $dangKy)
                <div style="padding:10px 0;border-bottom:1px solid var(--border-light);">
                    <div style="font-weight:600;">{{ $dangKy->nguoiDung->ho_ten ?? $dangKy->ma_sinh_vien }}</div>
                    <div class="text-muted text-sm">{{ $dangKy->ma_sinh_vien }} - {{ $dangKy->trang_thai_label }}</div>
                </div>
            @empty
                <div class="text-muted">Chưa có người tham gia.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
