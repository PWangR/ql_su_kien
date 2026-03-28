@extends('layouts.app')
@section('title', 'Phiếu bầu')

@section('styles')
<style>
.ballot-item { display:flex; align-items:flex-start; gap:14px; padding:14px 16px; border:2px solid var(--border); border-radius:var(--border-radius); cursor:pointer; transition:all 0.2s; margin-bottom:8px; }
.ballot-item:hover { border-color:var(--accent); background:var(--accent-bg); }
.ballot-item.selected { border-color:var(--accent); background:var(--accent-bg); }
.ballot-checkbox { width:18px; height:18px; accent-color:var(--accent); margin-top:2px; flex-shrink:0; cursor:pointer; }
</style>
@endsection

@section('content')
<div style="max-width:700px;margin:0 auto;">
<div class="card">
    <div class="card-body" style="padding:var(--space-xl);">
        <h1 style="font-size:1.35rem;margin-bottom:4px;text-transform:uppercase;letter-spacing:0.04em;">Phiếu bầu</h1>
        <p class="text-muted" style="margin-bottom:8px;">{{ $bauCu->tieu_de }}</p>
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:var(--space-lg);">
            <span class="text-sm" style="color:var(--accent);font-weight:600;">
                Chọn tối thiểu {{ $bauCu->so_chon_toi_thieu }}, tối đa {{ $bauCu->so_chon_toi_da }} ứng cử viên.
            </span>
            <span class="badge badge-primary" id="counter">Đã chọn: 0/{{ $bauCu->so_chon_toi_da }}</span>
        </div>

        <form id="ballotForm" action="{{ route('bo-phieu.review', $bauCu->ma_bau_cu) }}" method="POST">
            @csrf
            <div style="margin-bottom:var(--space-lg);">
                @foreach($ungCuViens as $i => $ucv)
                <label class="ballot-item" id="item-{{ $ucv->ma_ung_cu_vien }}">
                    <input type="checkbox" name="ung_cu_vien[]" value="{{ $ucv->ma_ung_cu_vien }}"
                           class="ballot-checkbox candidate-cb"
                           data-max="{{ $bauCu->so_chon_toi_da }}">
                    <div style="flex:1;">
                        <div style="display:flex;align-items:center;gap:6px;">
                            <span class="text-sm font-bold" style="color:var(--accent);">{{ $i + 1 }}.</span>
                            <h4 style="font-size:0.9375rem;font-weight:600;">{{ $ucv->ho_ten }}</h4>
                        </div>
                        <p class="text-sm text-muted">
                            Lớp: {{ $ucv->lop }} | MSSV: {{ $ucv->ma_sinh_vien }}
                            @if($ucv->diem_trung_binh) | ĐTB: {{ number_format($ucv->diem_trung_binh, 2) }} @endif
                            @if($ucv->diem_ren_luyen) | ĐRL: {{ number_format($ucv->diem_ren_luyen, 1) }} @endif
                        </p>
                        @if($ucv->gioi_thieu)
                        <p class="text-sm text-light" style="margin-top:4px;">{{ $ucv->gioi_thieu }}</p>
                        @endif
                    </div>
                </label>
                @endforeach
            </div>

            <button type="submit" id="reviewBtn" disabled class="btn btn-primary w-full" style="padding:14px;opacity:0.5;">
                <i class="bi bi-eye"></i> Xem lại trước khi gửi
            </button>
        </form>
    </div>
</div>
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

    checkboxes.forEach(cb => {
        if (!cb.checked && checked >= max) {
            cb.disabled = true;
            cb.parentElement.style.opacity = '0.5';
        } else {
            cb.disabled = false;
            cb.parentElement.style.opacity = '1';
        }
        cb.parentElement.classList.toggle('selected', cb.checked);
    });

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
