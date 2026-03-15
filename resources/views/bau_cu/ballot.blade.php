@extends('layouts.app')
@section('title', 'Phiếu bầu')

@section('styles')
<style>
    .ballot-card { background:#fff; border-radius:14px; border:1px solid #e2e8f0; padding:28px; max-width:700px; margin:0 auto; }
    .ballot-item { display:flex; align-items:flex-start; gap:14px; padding:14px 16px; border:2px solid #e2e8f0; border-radius:12px; cursor:pointer; transition:all 0.2s; margin-bottom:10px; }
    .ballot-item:hover { border-color:#bfdbfe; background:#eff6ff; }
    .ballot-item.selected { border-color:#2563eb; background:#eff6ff; box-shadow:0 0 0 3px rgba(37,99,235,0.15); }
    .ballot-checkbox { width:22px; height:22px; accent-color:#2563eb; margin-top:2px; flex-shrink:0; cursor:pointer; }
    .ballot-info h4 { font-size:15px; font-weight:600; color:#1e293b; margin-bottom:3px; }
    .ballot-info p { font-size:13px; color:#64748b; }
    .ballot-counter { display:inline-flex; align-items:center; gap:6px; padding:6px 14px; background:#eff6ff; color:#2563eb; border-radius:8px; font-size:13px; font-weight:600; }
</style>
@endsection

@section('content')
<div class="ballot-card">
    <h1 style="font-size:22px;font-weight:700;color:#0f172a;margin-bottom:4px;">PHIẾU BẦU</h1>
    <p style="color:#64748b;font-size:14px;margin-bottom:8px;">{{ $bauCu->tieu_de }}</p>
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
        <span style="color:#2563eb;font-size:13px;font-weight:600;">
            Chọn tối thiểu {{ $bauCu->so_chon_toi_thieu }}, tối đa {{ $bauCu->so_chon_toi_da }} ứng cử viên.
        </span>
        <span class="ballot-counter" id="counter">Đã chọn: 0/{{ $bauCu->so_chon_toi_da }}</span>
    </div>

    <form id="ballotForm" action="{{ route('bo-phieu.review', $bauCu->ma_bau_cu) }}" method="POST">
        @csrf
        <div style="margin-bottom:20px;">
            @foreach($ungCuViens as $i => $ucv)
            <label class="ballot-item" id="item-{{ $ucv->ma_ung_cu_vien }}">
                <input type="checkbox" name="ung_cu_vien[]" value="{{ $ucv->ma_ung_cu_vien }}"
                       class="ballot-checkbox candidate-cb"
                       data-max="{{ $bauCu->so_chon_toi_da }}">
                <div class="ballot-info" style="flex:1;">
                    <div style="display:flex;align-items:center;gap:6px;">
                        <span style="font-size:13px;font-weight:700;color:#2563eb;">{{ $i + 1 }}.</span>
                        <h4>{{ $ucv->ho_ten }}</h4>
                    </div>
                    <p>
                        Lớp: {{ $ucv->lop }} | MSSV: {{ $ucv->ma_sinh_vien }}
                        @if($ucv->diem_trung_binh) | ĐTB: {{ number_format($ucv->diem_trung_binh, 2) }} @endif
                        @if($ucv->diem_ren_luyen) | ĐRL: {{ number_format($ucv->diem_ren_luyen, 1) }} @endif
                    </p>
                    @if($ucv->gioi_thieu)
                    <p style="margin-top:4px;">{{ $ucv->gioi_thieu }}</p>
                    @endif
                </div>
            </label>
            @endforeach
        </div>

        <button type="submit" id="reviewBtn" disabled
                style="width:100%;padding:14px;background:#2563eb;color:#fff;border:none;border-radius:10px;font-size:15px;font-weight:600;cursor:pointer;transition:all 0.2s;opacity:0.5;font-family:'Inter',sans-serif;">
            <i class="bi bi-eye"></i> Xem lại trước khi gửi
        </button>
    </form>
</div>
@endsection

@section('scripts')
<script>
const min = {{ $bauCu->so_chon_toi_thieu }};
const max = {{ $bauCu->so_chon_toi_da }};
const checkboxes = document.querySelectorAll('.candidate-cb');
const counter = document.getElementById('counter');
const btn = document.getElementById('reviewBtn');

function updateState() {
    const checked = document.querySelectorAll('.candidate-cb:checked').length;
    counter.textContent = `Đã chọn: ${checked}/${max}`;

    // Disable unchecked if max reached
    checkboxes.forEach(cb => {
        if (!cb.checked && checked >= max) {
            cb.disabled = true;
            cb.parentElement.style.opacity = '0.5';
        } else {
            cb.disabled = false;
            cb.parentElement.style.opacity = '1';
        }
        // Toggle selected class
        if (cb.checked) {
            cb.parentElement.classList.add('selected');
        } else {
            cb.parentElement.classList.remove('selected');
        }
    });

    // Enable/disable submit
    if (checked >= min && checked <= max) {
        btn.disabled = false;
        btn.style.opacity = '1';
    } else {
        btn.disabled = true;
        btn.style.opacity = '0.5';
    }
}

checkboxes.forEach(cb => cb.addEventListener('change', updateState));
</script>
@endsection
