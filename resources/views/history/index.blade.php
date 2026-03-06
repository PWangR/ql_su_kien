@extends('layouts.app')

@section('title', 'Lịch sử tham gia')

@section('content')
<h2 style="font-family:'Montserrat',sans-serif;font-size:22px;font-weight:800;margin-bottom:8px;">
    <i class="bi bi-clock-history" style="color:#2563eb;"></i> Lịch sử tham gia
</h2>
<p style="color:#64748b;margin-bottom:24px;">Tổng điểm tích lũy: <strong style="color:#f59e0b;font-size:18px;">{{ $tongDiem }} điểm</strong></p>

<div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:12px 16px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:#64748b;text-align:left;">Sự kiện</th>
                    <th style="padding:12px 16px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:#64748b;text-align:left;">Ngày đăng ký</th>
                    <th style="padding:12px 16px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:#64748b;text-align:left;">Điểm</th>
                    <th style="padding:12px 16px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:#64748b;text-align:left;">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lichSu as $dk)
                @php
                    $sMap = ['da_dang_ky'=>['bg'=>'#dbeafe','t'=>'#1d4ed8','l'=>'Đã đăng ký'],'da_tham_gia'=>['bg'=>'#dcfce7','t'=>'#15803d','l'=>'Đã tham gia'],'vang_mat'=>['bg'=>'#fef3c7','t'=>'#92400e','l'=>'Vắng mặt'],'huy'=>['bg'=>'#f1f5f9','t'=>'#475569','l'=>'Đã hủy']];
                    $s = $sMap[$dk->trang_thai_tham_gia] ?? $sMap['da_dang_ky'];
                @endphp
                <tr style="border-bottom:1px solid #f1f5f9;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                    <td style="padding:14px 16px;">
                        @if($dk->suKien)
                        <a href="{{ route('events.show', $dk->suKien->ma_su_kien) }}" style="font-weight:700;color:#1e293b;text-decoration:none;">
                            {{ Str::limit($dk->suKien->ten_su_kien, 50) }}
                        </a>
                        <div style="font-size:12px;color:#64748b;margin-top:2px;">
                            @if($dk->suKien->dia_diem)<i class="bi bi-geo-alt"></i> {{ $dk->suKien->dia_diem }}@endif
                        </div>
                        @else
                        <span style="color:#94a3b8;">—</span>
                        @endif
                    </td>
                    <td style="padding:14px 16px;font-size:13px;color:#64748b;">
                        {{ $dk->created_at ? $dk->created_at->format('d/m/Y H:i') : '—' }}
                    </td>
                    <td style="padding:14px 16px;">
                        @if($dk->trang_thai_tham_gia === 'da_tham_gia' && $dk->suKien?->diem_cong > 0)
                        <span style="color:#d97706;font-weight:700;font-size:15px;">+{{ $dk->suKien->diem_cong }}</span>
                        @else
                        <span style="color:#94a3b8;">—</span>
                        @endif
                    </td>
                    <td style="padding:14px 16px;">
                        <span style="background:{{ $s['bg'] }};color:{{ $s['t'] }};padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">
                            {{ $s['l'] }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center;padding:60px;color:#64748b;">
                        <i class="bi bi-calendar-x" style="font-size:48px;display:block;margin-bottom:12px;opacity:.3;"></i>
                        <p>Bạn chưa tham gia sự kiện nào.</p>
                        <a href="{{ route('events.index') }}" style="display:inline-block;margin-top:12px;background:#2563eb;color:#fff;padding:8px 20px;border-radius:8px;font-weight:600;text-decoration:none;">Xem sự kiện ngay</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($lichSu->hasPages())
    <div style="padding:16px 20px;border-top:1px solid #f1f5f9;">{{ $lichSu->links() }}</div>
    @endif
</div>
@endsection
