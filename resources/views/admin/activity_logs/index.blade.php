@extends('admin.layout')

@section('title', 'Log hoạt động')
@section('page-title', 'Log hoạt động')

@section('content')
{{-- Bộ lọc --}}
<div class="card mb-lg">
    <div class="card-header">
        <div class="card-title">
            <i class="bi bi-funnel"></i> Bộ lọc
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.activity-logs.index') }}">
            <div style="display: flex; gap: var(--space-md); flex-wrap: wrap; align-items: flex-end;">
                {{-- Tìm kiếm --}}
                <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                    <label class="form-label" for="search">Tìm kiếm</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}"
                           placeholder="Tên, MSSV, mô tả...">
                </div>

                {{-- Lọc action --}}
                <div class="form-group" style="min-width: 160px; margin-bottom: 0;">
                    <label class="form-label" for="action">Hành động</label>
                    <select class="form-control" id="action" name="action">
                        <option value="">— Tất cả —</option>
                        @foreach($actions as $key => $label)
                            <option value="{{ $key }}" {{ request('action') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Từ ngày --}}
                <div class="form-group" style="min-width: 160px; margin-bottom: 0;">
                    <label class="form-label" for="from">Từ ngày</label>
                    <input type="date" class="form-control" id="from" name="from"
                           value="{{ request('from') }}">
                </div>

                {{-- Đến ngày --}}
                <div class="form-group" style="min-width: 160px; margin-bottom: 0;">
                    <label class="form-label" for="to">Đến ngày</label>
                    <input type="date" class="form-control" id="to" name="to"
                           value="{{ request('to') }}">
                </div>

                {{-- Nút --}}
                <div class="btn-group" style="margin-bottom: 0;">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-search"></i> Lọc
                    </button>
                    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-counterclockwise"></i> Xóa lọc
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Kết quả --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="bi bi-clock-history"></i> Nhật ký hoạt động
        </div>
        <span class="badge badge-secondary">{{ $logs->total() }} bản ghi</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 160px;">Thời gian</th>
                        <th style="width: 160px;">Người dùng</th>
                        <th style="width: 110px;">Hành động</th>
                        <th>Mô tả</th>
                        <th style="width: 120px;">IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>
                            <span class="mono text-sm" style="color: var(--text-light);">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </span>
                        </td>
                        <td>
                            @if($log->user_id)
                                <div style="font-weight: 600;">{{ $log->user_name }}</div>
                                <div class="text-xs text-muted">{{ $log->user_id }}</div>
                            @else
                                <span class="text-muted">Hệ thống</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $log->action_badge_class }}">
                                {{ $log->action_label }}
                            </span>
                        </td>
                        <td>
                            <div style="max-width: 400px;">
                                {{ $log->description }}
                            </div>
                            @if($log->model_type)
                                <div class="text-xs text-muted" style="margin-top: 2px;">
                                    {{ class_basename($log->model_type) }}
                                    @if($log->model_id) #{{ $log->model_id }} @endif
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="mono text-sm" style="color: var(--text-muted);">
                                {{ $log->ip_address }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center" style="padding: var(--space-xl); color: var(--text-muted);">
                            <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: var(--space-sm);"></i>
                            Chưa có log nào được ghi nhận.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($logs->hasPages())
    <div class="card-footer" style="justify-content: center;">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
