@extends('layouts.app')

@section('title', 'Lịch sử tham gia')

@section('content')
<div style="margin-bottom:var(--space-lg);padding-bottom:var(--space-md);border-bottom:1px solid var(--border);">
    <h1 style="font-size:1.75rem;margin-bottom:4px;">Lịch sử tham gia</h1>
    <p class="text-muted">
        Tổng điểm tích lũy:
        <strong style="color:var(--accent);font-family:var(--font-sans);font-size:1.25rem;">
            <span class="mono">{{ $tongDiem }}</span> điểm
        </strong>
    </p>
</div>

<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Sự kiện</th>
                    <th>Ngày đăng ký</th>
                    <th>Điểm</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lichSu as $dk)
                @php
                    $sMap = [
                        'da_dang_ky' => ['class' => 'badge-primary', 'label' => 'Đã đăng ký'],
                        'da_tham_gia' => ['class' => 'badge-success', 'label' => 'Đã tham gia'],
                        'vang_mat' => ['class' => 'badge-warning', 'label' => 'Vắng mặt'],
                        'huy' => ['class' => 'badge-secondary', 'label' => 'Đã hủy'],
                    ];
                    $s = $sMap[$dk->trang_thai_tham_gia] ?? $sMap['da_dang_ky'];
                @endphp
                <tr>
                    <td>
                        @if($dk->suKien)
                        <a href="{{ route('events.show', $dk->suKien->ma_su_kien) }}" style="font-weight:600;color:var(--text);text-decoration:none;">
                            {{ Str::limit($dk->suKien->ten_su_kien, 50) }}
                        </a>
                        <div class="text-sm text-muted" style="margin-top:2px;">
                            @if($dk->suKien->dia_diem)<i class="bi bi-geo-alt"></i> {{ $dk->suKien->dia_diem }}@endif
                        </div>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-sm text-muted">
                        {{ $dk->created_at ? $dk->created_at->format('d/m/Y H:i') : '-' }}
                    </td>
                    <td>
                        @if($dk->trang_thai_tham_gia === 'da_tham_gia' && $dk->suKien?->diem_cong > 0)
                        <strong style="color:var(--accent);font-family:var(--font-sans);">
                            <span class="mono">+{{ $dk->suKien->diem_cong }}</span>
                        </strong>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $s['class'] }}">{{ $s['label'] }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center;padding:var(--space-3xl);color:var(--text-muted);">
                        <i class="bi bi-calendar-x" style="font-size:48px;display:block;margin-bottom:12px;opacity:0.3;"></i>
                        <p>Bạn chưa tham gia sự kiện nào.</p>
                        <a href="{{ route('events.index') }}" class="btn btn-primary" style="margin-top:12px;">Xem sự kiện ngay</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($lichSu->hasPages())
    <div style="padding:var(--space-md) 20px;border-top:1px solid var(--border-light);">{{ $lichSu->links() }}</div>
    @endif
</div>
@endsection
