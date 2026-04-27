@extends('layouts.app')

@section('title', 'Danh sách sự kiện — Quản Lý Sự Kiện NTU')

@section('styles')
    <style>
        .page-header {
            margin-bottom: var(--space-lg);
            padding-bottom: var(--space-md);
            border-bottom: 1px solid var(--border);
        }

        .page-header h1 {
            font-size: 1.75rem;
            margin-bottom: 4px;
        }

        .page-header p {
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: var(--space-lg);
        }

        .search-bar {
            display: flex;
            gap: var(--space-sm);
            margin-bottom: var(--space-lg);
        }

        .search-bar input {
            flex: 1;
        }

        /* Event card image badge positioning */
        .event-card-img {
            position: relative;
        }

        .event-card-img .badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
        }

        /* ============ MOBILE RESPONSIVE ============ */
        @media (max-width: 768px) {

            /* Page header */
            .page-header {
                margin-bottom: var(--space-md);
                padding-bottom: var(--space-sm);
            }

            .page-header h1 {
                font-size: 1.35rem;
                margin-bottom: 2px;
            }

            .page-header p {
                font-size: 0.8125rem;
            }

            /* Search bar */
            .search-bar {
                flex-direction: column;
                gap: var(--space-xs);
                margin-bottom: var(--space-md);
            }

            .search-bar input {
                width: 100%;
            }

            .search-bar button {
                width: 100%;
            }

            /* Events grid */
            .events-grid {
                grid-template-columns: 1fr;
                gap: var(--space-md);
            }

            /* Filter tabs */
            .filter-tabs {
                padding-bottom: 8px !important;
                margin-bottom: var(--space-md) !important;
                -webkit-overflow-scrolling: touch;
            }

            .filter-tab {
                padding: 8px 12px !important;
                font-size: 0.75rem !important;
            }
        }

        @media (max-width: 480px) {

            /* Extra small screens */
            .page-header h1 {
                font-size: 1.15rem;
            }

            .page-header p {
                font-size: 0.75rem;
            }

            .events-grid {
                gap: var(--space-sm);
            }

            /* Reduce padding on small screens */
            .event-card-body {
                padding: 12px !important;
            }

            .event-card-title {
                font-size: 0.95rem !important;
                margin-bottom: 6px !important;
            }

            .event-card-meta {
                gap: 6px !important;
            }

            .event-meta-item {
                font-size: 0.75rem !important;
            }

            .event-card-footer {
                padding: 10px 12px !important;
                gap: 8px !important;
            }

            .btn-sm {
                font-size: 0.75rem !important;
                padding: 6px 10px !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <h1>Sự kiện</h1>
        <p>Khám phá và đăng ký tham gia các sự kiện của Khoa CNTT</p>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('events.index') }}" class="search-bar">
        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sự kiện..."
            value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-search"></i> Tìm
        </button>
        @foreach(collect(request()->query())->except(['search', 'page']) as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
    </form>

    {{-- Status Filter --}}
    <div style="margin-bottom: var(--space-lg);">
        <form method="GET" style="display: flex; flex-wrap: wrap; gap: var(--space-sm); align-items: center;">
            <label
                style="font-size: 0.875rem; font-weight: 600; color: var(--text-muted); width: 100%; margin-bottom: 4px;">Trạng
                thái:</label>
            @foreach(collect(request()->query())->except(['trang_thai', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <select name="trang_thai"
                style="padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--border-radius); background: var(--card); color: var(--text); font-size: 0.875rem; cursor: pointer; flex: 1; min-width: 180px;"
                onchange="this.form.submit();">
                <option value="">Tất cả</option>
                <option value="sap_to_chuc" {{ request('trang_thai') === 'sap_to_chuc' ? 'selected' : '' }}>Sắp tổ chức
                </option>
                <option value="dang_dien_ra" {{ request('trang_thai') === 'dang_dien_ra' ? 'selected' : '' }}>Đang diễn ra
                </option>
                <option value="da_ket_thuc" {{ request('trang_thai') === 'da_ket_thuc' ? 'selected' : '' }}>Đã kết thúc
                </option>
            </select>
        </form>
    </div>

    {{-- Filter tabs --}}
    @if(isset($loaiSuKien) && $loaiSuKien->count())
        <div class="filter-tabs"
            style="border-bottom:1px solid var(--border);margin-bottom:var(--space-lg);display:flex;overflow-x:auto;">
            @php
                $baseQuery = collect(request()->query())->except(['loai', 'page'])->toArray();
            @endphp
            <a href="{{ route('events.index', $baseQuery) }}" class="filter-tab {{ !request('loai') ? 'active' : '' }}"
                style="padding:10px 18px;font-size:0.8125rem;font-weight:600;color:{{ !request('loai') ? 'var(--accent)' : 'var(--text-light)' }};text-decoration:none;border-bottom:2px solid {{ !request('loai') ? 'var(--accent)' : 'transparent' }};white-space:nowrap;">
                Tất cả
            </a>
            @foreach($loaiSuKien as $l)
                <a href="{{ route('events.index', array_merge($baseQuery, ['loai' => $l->ma_loai_su_kien])) }}"
                    class="filter-tab {{ request('loai') == $l->ma_loai_su_kien ? 'active' : '' }}"
                    style="padding:10px 18px;font-size:0.8125rem;font-weight:600;color:{{ request('loai') == $l->ma_loai_su_kien ? 'var(--accent)' : 'var(--text-light)' }};text-decoration:none;border-bottom:2px solid {{ request('loai') == $l->ma_loai_su_kien ? 'var(--accent)' : 'transparent' }};white-space:nowrap;">
                    {{ $l->ten_loai }}
                </a>
            @endforeach
        </div>
    @endif

    {{-- Events Grid --}}
    @if(isset($suKien) && $suKien->count())
        <div class="events-grid">
            @foreach($suKien as $sk)
                <div class="event-card">
                    <div class="event-card-img">
                        @if($sk->anh_su_kien)
                            <img src="{{ asset('storage/' . $sk->anh_su_kien) }}" alt="{{ $sk->ten_su_kien }}">
                        @else
                            <i class="bi bi-calendar-event placeholder-icon"></i>
                        @endif
                        {{-- Status Badge --}}
                        @php
                            $status = $sk->trang_thai_thuc_te;
                            $statusConfig = [
                                'sap_to_chuc' => ['label' => 'Sắp tổ chức', 'badge' => 'badge-primary'],
                                'dang_dien_ra' => ['label' => 'Đang diễn ra', 'badge' => 'badge-success'],
                                'da_ket_thuc' => ['label' => 'Đã kết thúc', 'badge' => 'badge-danger'],
                            ];
                            $config = $statusConfig[$status] ?? ['label' => 'Không xác định', 'badge' => 'badge-secondary'];
                        @endphp
                        <span class="badge {{ $config['badge'] }}">{{ $config['label'] }}</span>
                    </div>
                    <div class="event-card-body">
                        <h3 class="event-card-title">{{ Str::limit($sk->ten_su_kien, 55) }}</h3>
                        <div class="event-card-meta">
                            @if($sk->thoi_gian_bat_dau)
                                <div class="event-meta-item"><i class="bi bi-clock"></i>
                                    {{ $sk->thoi_gian_bat_dau->format('H:i, d/m/Y') }}</div>
                            @endif
                            @if($sk->dia_diem)
                                <div class="event-meta-item"><i class="bi bi-geo-alt"></i> {{ Str::limit($sk->dia_diem, 35) }}</div>
                            @endif
                            @if($sk->diem_cong > 0)
                                <div class="event-meta-item"><i class="bi bi-star"></i> +{{ $sk->diem_cong }} điểm</div>
                            @endif
                        </div>
                    </div>
                    <div class="event-card-footer">
                        <span class="text-sm text-muted">
                            <i class="bi bi-people"></i> {{ $sk->so_luong_hien_tai }}/{{ $sk->so_luong_toi_da ?: '∞' }}
                        </span>
                        <a href="{{ route('events.show', $sk->ma_su_kien) }}" class="btn btn-outline btn-sm">
                            Chi tiết <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if(method_exists($suKien, 'links'))
            <div style="margin-top:var(--space-xl);">
                {{ $suKien->links() }}
            </div>
        @endif
    @else
        <div style="text-align:center;padding:var(--space-3xl);color:var(--text-muted);">
            <i class="bi bi-calendar-x" style="font-size:48px;display:block;margin-bottom:12px;opacity:0.3;"></i>
            <p>Không tìm thấy sự kiện nào.</p>
        </div>
    @endif
@endsection