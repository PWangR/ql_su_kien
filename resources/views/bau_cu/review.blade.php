@extends('layouts.app')
@section('title', 'Xác nhận phiếu bầu')

@section('content')
<div style="max-width:600px;margin:0 auto;">
    <div class="card">
        <div class="card-body" style="padding:var(--space-xl);">
            <h1 style="font-size:1.35rem;margin-bottom:4px;">
                <i class="bi bi-shield-check" style="color:var(--accent);"></i> Xác nhận phiếu bầu
            </h1>
            <p class="text-muted" style="margin-bottom:var(--space-lg);">{{ $bauCu->tieu_de }}</p>

            <div style="background:var(--accent-bg);border:1px solid var(--accent);border-radius:var(--border-radius);padding:var(--space-md);margin-bottom:var(--space-lg);">
                <p class="text-sm font-semibold" style="color:var(--accent);margin-bottom:10px;">
                    Bạn đã chọn {{ $ungCuViens->count() }} ứng cử viên:
                </p>
                @foreach($ungCuViens as $i => $ucv)
                <div style="display:flex;align-items:center;gap:10px;padding:8px 0;{{ !$loop->last ? 'border-bottom:1px solid var(--border-light);' : '' }}">
                    <span style="width:26px;height:26px;background:var(--accent);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.6875rem;flex-shrink:0;">
                        {{ $i + 1 }}
                    </span>
                    <div>
                        <strong style="font-size:0.875rem;">{{ $ucv->ho_ten }}</strong>
                        <span class="text-xs text-muted" style="margin-left:6px;">{{ $ucv->lop }} – {{ $ucv->ma_sinh_vien }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i>
                <strong>Lưu ý:</strong> Sau khi gửi, bạn không thể thay đổi phiếu bầu.
            </div>

            <div style="display:flex;gap:10px;">
                <form method="POST" action="{{ route('bo-phieu.submit', $bauCu->ma_bau_cu) }}" style="flex:1;">
                    @csrf
                    <button type="submit" class="btn btn-primary w-full" style="padding:14px;"
                            onclick="this.disabled=true;this.innerHTML='<i class=\'bi bi-hourglass-split\'></i> Đang gửi...';this.form.submit();">
                        <i class="bi bi-check-circle"></i> Xác nhận gửi phiếu
                    </button>
                </form>
                <a href="{{ route('bo-phieu.ballot', $bauCu->ma_bau_cu) }}" class="btn btn-secondary" style="padding:14px;">Chọn lại</a>
            </div>
        </div>
    </div>
</div>
@endsection
