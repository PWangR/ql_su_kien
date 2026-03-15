@extends('layouts.app')
@section('title', 'Xác nhận phiếu bầu')

@section('content')
<div style="max-width:600px;margin:0 auto;">
    <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;padding:28px;">
        <h1 style="font-size:22px;font-weight:700;color:#0f172a;margin-bottom:4px;">
            <i class="bi bi-shield-check" style="color:#2563eb;"></i> Xác nhận phiếu bầu
        </h1>
        <p style="color:#64748b;font-size:14px;margin-bottom:20px;">{{ $bauCu->tieu_de }}</p>

        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:16px;margin-bottom:20px;">
            <p style="font-size:13px;font-weight:600;color:#1d4ed8;margin-bottom:10px;">
                Bạn đã chọn {{ $ungCuViens->count() }} ứng cử viên:
            </p>
            @foreach($ungCuViens as $i => $ucv)
            <div style="display:flex;align-items:center;gap:10px;padding:8px 0;{{ !$loop->last ? 'border-bottom:1px solid #dbeafe;' : '' }}">
                <span style="width:28px;height:28px;background:#2563eb;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;flex-shrink:0;">
                    {{ $i + 1 }}
                </span>
                <div>
                    <strong style="font-size:14px;color:#1e293b;">{{ $ucv->ho_ten }}</strong>
                    <span style="font-size:12px;color:#64748b;margin-left:6px;">{{ $ucv->lop }} – {{ $ucv->ma_sinh_vien }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <div style="background:#fef3c7;border:1px solid #fde68a;border-radius:10px;padding:12px 16px;margin-bottom:20px;">
            <p style="font-size:13px;color:#92400e;">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <strong>Lưu ý:</strong> Sau khi gửi, bạn không thể thay đổi phiếu bầu.
            </p>
        </div>

        <div style="display:flex;gap:10px;">
            <form method="POST" action="{{ route('bo-phieu.submit', $bauCu->ma_bau_cu) }}" style="flex:1;">
                @csrf
                <button type="submit" style="width:100%;padding:14px;background:#2563eb;color:#fff;border:none;border-radius:10px;font-size:15px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;transition:background 0.2s;"
                        onclick="this.disabled=true;this.innerHTML='<i class=\'bi bi-hourglass-split\'></i> Đang gửi...';this.form.submit();">
                    <i class="bi bi-check-circle-fill"></i> Xác nhận gửi phiếu
                </button>
            </form>
            <a href="{{ route('bo-phieu.ballot', $bauCu->ma_bau_cu) }}"
               style="padding:14px 20px;background:#f1f5f9;color:#475569;border-radius:10px;text-decoration:none;font-weight:600;font-size:14px;display:flex;align-items:center;border:1px solid #e2e8f0;">
                Chọn lại
            </a>
        </div>
    </div>
</div>
@endsection
