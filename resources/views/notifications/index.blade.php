@extends('layouts.app')

@section('title', 'Thông báo')

@section('content')
<div style="max-width:700px;margin:0 auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <h2 style="font-family:'Montserrat',sans-serif;font-size:22px;font-weight:800;">
            <i class="bi bi-bell-fill" style="color:#2563eb;"></i> Thông báo
            @if($chuaDoc > 0)
            <span style="background:#ef4444;color:#fff;font-size:12px;font-weight:700;border-radius:20px;padding:2px 8px;margin-left:6px;">{{ $chuaDoc }}</span>
            @endif
        </h2>
        @if($chuaDoc > 0)
        <form method="POST" action="{{ route('notifications.read-all') }}">
            @csrf
            <button type="submit" style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:8px;padding:7px 16px;font-size:13px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;display:flex;align-items:center;gap:6px;">
                <i class="bi bi-check2-all"></i> Đọc tất cả
            </button>
        </form>
        @endif
    </div>

    <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;">
        @forelse($thongBao as $tb)
        @php
            $iconMap = ['he_thong'=>['ic'=>'bi-gear-fill','bg'=>'#f1f5f9','ic_c'=>'#64748b'],'nhac_nho_su_kien'=>['ic'=>'bi-calendar-event-fill','bg'=>'#dbeafe','ic_c'=>'#2563eb'],'cap_nhat_diem'=>['ic'=>'bi-star-fill','bg'=>'#fef3c7','ic_c'=>'#d97706']];
            $icon = $iconMap[$tb->loai_thong_bao] ?? ['ic'=>'bi-bell-fill','bg'=>'#f1f5f9','ic_c'=>'#64748b'];
        @endphp
        <div style="padding:16px 20px;display:flex;gap:14px;{{ !$tb->da_doc ? 'background:#f0f6ff;' : '' }}border-bottom:1px solid #f1f5f9;">
            <div style="width:44px;height:44px;border-radius:50%;background:{{ $icon['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi {{ $icon['ic'] }}" style="color:{{ $icon['ic_c'] }};font-size:18px;"></i>
            </div>
            <div style="flex:1;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">
                    <div style="font-weight:{{ !$tb->da_doc ? '700' : '500' }};font-size:14px;color:#1e293b;">
                        {{ $tb->tieu_de }}
                        @if(!$tb->da_doc)
                        <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#2563eb;margin-left:4px;"></span>
                        @endif
                    </div>
                    <div style="font-size:11px;color:#94a3b8;flex-shrink:0;">
                        {{ $tb->created_at ? \Carbon\Carbon::parse($tb->created_at)->diffForHumans() : '' }}
                    </div>
                </div>
                <div style="font-size:13px;color:#64748b;margin-top:4px;line-height:1.5;">{{ $tb->noi_dung }}</div>
                @if(!$tb->da_doc)
                <form method="POST" action="{{ route('notifications.read', $tb->ma_thong_bao) }}" style="margin-top:8px;display:inline;">
                    @csrf
                    <button type="submit" style="background:none;border:none;color:#2563eb;font-size:12px;font-weight:600;cursor:pointer;padding:0;font-family:'Inter',sans-serif;">
                        <i class="bi bi-check"></i> Đánh dấu đã đọc
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:60px;color:#64748b;">
            <i class="bi bi-bell-slash" style="font-size:48px;display:block;margin-bottom:12px;opacity:.3;"></i>
            <p>Bạn chưa có thông báo nào.</p>
        </div>
        @endforelse
    </div>

    @if($thongBao->hasPages())
    <div style="margin-top:16px;">{{ $thongBao->links() }}</div>
    @endif
</div>
@endsection
