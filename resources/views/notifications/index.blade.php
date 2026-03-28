@extends('layouts.app')

@section('title', 'Thông báo')

@section('content')
<div style="max-width:700px;margin:0 auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:var(--space-lg);padding-bottom:var(--space-md);border-bottom:1px solid var(--border);">
        <h1 style="font-size:1.5rem;margin:0;">
            Thông báo
            @if($chuaDoc > 0)
            <span class="notif-badge" style="margin-left:6px;">{{ $chuaDoc }}</span>
            @endif
        </h1>
        @if($chuaDoc > 0)
        <form method="POST" action="{{ route('notifications.read-all') }}">
            @csrf
            <button type="submit" class="btn btn-secondary btn-sm">
                <i class="bi bi-check2-all"></i> Đọc tất cả
            </button>
        </form>
        @endif
    </div>

    <div class="card">
        @forelse($thongBao as $tb)
        @php
            $iconMap = [
                'he_thong'          => ['icon' => 'bi-gear',          'color' => 'var(--text-muted)'],
                'nhac_nho_su_kien'  => ['icon' => 'bi-calendar-event','color' => 'var(--accent)'],
                'cap_nhat_diem'     => ['icon' => 'bi-star',          'color' => 'var(--warning)'],
            ];
            $icon = $iconMap[$tb->loai_thong_bao] ?? ['icon' => 'bi-bell', 'color' => 'var(--text-muted)'];
        @endphp
        <div style="padding:var(--space-md) 20px;display:flex;gap:14px;{{ !$tb->da_doc ? 'background:var(--accent-bg);' : '' }}border-bottom:1px solid var(--border-light);">
            <div style="width:40px;height:40px;border:1px solid var(--border);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:var(--card);">
                <i class="bi {{ $icon['icon'] }}" style="color:{{ $icon['color'] }};font-size:16px;"></i>
            </div>
            <div style="flex:1;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">
                    <div style="font-weight:{{ !$tb->da_doc ? '700' : '500' }};font-size:0.875rem;color:var(--text);">
                        {{ $tb->tieu_de }}
                        @if(!$tb->da_doc)
                        <span class="badge-dot" style="background:var(--accent);display:inline-block;margin-left:4px;"></span>
                        @endif
                    </div>
                    <div class="text-xs text-muted" style="flex-shrink:0;">
                        {{ $tb->created_at ? \Carbon\Carbon::parse($tb->created_at)->diffForHumans() : '' }}
                    </div>
                </div>
                <div class="text-sm text-light" style="margin-top:4px;line-height:1.5;">{{ $tb->noi_dung }}</div>
                @if(!$tb->da_doc)
                <form method="POST" action="{{ route('notifications.read', $tb->ma_thong_bao) }}" style="margin-top:6px;display:inline;">
                    @csrf
                    <button type="submit" style="background:none;border:none;color:var(--accent);font-size:0.75rem;font-weight:600;cursor:pointer;padding:0;font-family:var(--font-sans);">
                        <i class="bi bi-check"></i> Đánh dấu đã đọc
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:var(--space-3xl);color:var(--text-muted);">
            <i class="bi bi-bell-slash" style="font-size:48px;display:block;margin-bottom:12px;opacity:0.3;"></i>
            <p>Bạn chưa có thông báo nào.</p>
        </div>
        @endforelse
    </div>

    @if($thongBao->hasPages())
    <div style="margin-top:var(--space-md);">{{ $thongBao->links() }}</div>
    @endif
</div>
@endsection
