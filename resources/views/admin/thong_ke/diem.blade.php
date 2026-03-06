@extends('admin.layout')

@section('title', 'Thống kê điểm')
@section('page-title', 'Thống kê điểm sinh viên')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-star-fill" style="color:#f59e0b"></i> Bảng xếp hạng điểm tích lũy</div>
        <a href="{{ route('admin.thong-ke.index') }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Quay lại</a>
    </div>
    <div class="table-resp">
        <table>
            <thead>
                <tr><th>#</th><th>Họ tên</th><th>MSSV</th><th>Tổng điểm</th></tr>
            </thead>
            <tbody>
                @forelse($tongDiem as $i => $row)
                <tr>
                    <td>
                        @if($i < 3)
                        <span style="font-size:18px;">{{ ['🥇','🥈','🥉'][$i] }}</span>
                        @else
                        <span style="color:var(--text-light);">{{ $i+1 }}</span>
                        @endif
                    </td>
                    <td><strong>{{ $row->ho_ten }}</strong></td>
                    <td style="color:var(--text-light);">{{ $row->ma_sinh_vien }}</td>
                    <td><span class="badge" style="background:#fef3c7;color:#92400e;font-size:14px;padding:4px 12px;">{{ $row->tong_diem }} điểm</span></td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;padding:40px;color:var(--text-light);">Chưa có dữ liệu điểm</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tongDiem->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);">{{ $tongDiem->links() }}</div>
    @endif
</div>
@endsection