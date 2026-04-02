@extends('admin.layout')

@section('title', 'Sinh QR Điểm Danh')
@section('page-title', 'Mã QR Sự Kiện')

@section('styles')
<style>
.qr-container {
    max-width: 600px;
    margin: 0 auto;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--border-radius-lg);
    padding: var(--space-xl);
    text-align: center;
}

.qr-box {
    width: 300px;
    height: 300px;
    margin: var(--space-lg) auto;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--border-radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.qr-box img {
    width: 100% !important;
    height: 100% !important;
    object-fit: contain;
}

.timer {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--accent);
    margin-top: var(--space-md);
    font-family: monospace;
}
</style>
@endsection

@section('content')
<div class="qr-container">
    <h2 style="margin-bottom: var(--space-md);">Chọn sự kiện để điểm danh</h2>
    
    <div class="form-group" style="text-align: left;">
        <label>Sự kiện đang diễn ra</label>
        <select id="eventSelect" class="form-control" onchange="startQR()">
            <option value="">-- Chọn sự kiện --</option>
            @forelse($danhSachSuKien as $sk)
                <option value="{{ $sk->ma_su_kien }}">{{ $sk->ten_su_kien }} (ID: {{ $sk->ma_su_kien }})</option>
            @empty
                <!-- No active events -->
            @endforelse
        </select>
    </div>

    <div id="qr-section" style="display: none;">
        <p class="text-muted mt-4">Yêu cầu người dùng sử dụng ứng dụng di động để quét mã QR này.</p>
        <div class="qr-box" id="qr-code">
            <img id="qr-image" alt="Mã QR" style="display:none; border: 1px solid #ccc; padding: 10px;">
            <div id="qr-placeholder" class="text-muted">Đang chờ tạo mã QR...</div>
        </div>
        <div class="timer">Tự động làm mới trong: <span id="countdown">5</span>s</div>
    </div>
    
    @if(count($danhSachSuKien) === 0)
    <div class="alert alert-warning mt-4">
        <i class="bi bi-exclamation-triangle"></i> Không có sự kiện nào đang diễn ra ở thời điểm hiện tại.
    </div>
    @endif
</div>
@endsection

@section('scripts')
{{-- Không cần thư viện JS nữa vì dùng Server-side QR --}}

<script>
let refreshInterval;
let countdownInterval;
let timeLeft = 5; // Làm mới mỗi 5 giây để timestamp luôn sát nhất

function startQR() {
    const select = document.getElementById('eventSelect');
    const qrSection = document.getElementById('qr-section');
    
    if (!select.value) {
        qrSection.style.display = 'none';
        clearInterval(refreshInterval);
        clearInterval(countdownInterval);
        return;
    }
    
    qrSection.style.display = 'block';
    
    // Clear old intervals
    if (refreshInterval) clearInterval(refreshInterval);
    if (countdownInterval) clearInterval(countdownInterval);
    
    generateQR(select.value);
    
    // Tạo khoảng thời gian làm mới
    refreshInterval = setInterval(() => {
        generateQR(select.value);
    }, 5000); // Tạo mới mỗi 5 giây
}

function generateQR(eventId) {
    console.log("Đang tạo QR cho sự kiện:", eventId);
    
    const qrData = JSON.stringify({
        action: 'diem_danh',
        ma_su_kien: parseInt(eventId),
        t: Date.now() 
    });
    
    console.log("Payload QR (gửi về server):", qrData);
    
    // Sử dụng API của Laravel để sinh ảnh QR, không dùng thư viện JS
    const img = document.getElementById('qr-image');
    if (!img) return;
    
    const qrUrl = `/api/generate-qr?base64=1&data=${encodeURIComponent(btoa(qrData))}`;
    
    const placeholder = document.getElementById('qr-placeholder');
    if (placeholder) placeholder.style.display = 'none';
    
    img.onload = function() {
        img.style.display = 'block';
        console.log("Mã QR đã được cập nhật từ Server!");
    };
    img.src = qrUrl;
    
    // Reset countdown
    timeLeft = 5;
    document.getElementById('countdown').innerText = timeLeft;
    
    if(countdownInterval) clearInterval(countdownInterval);
    countdownInterval = setInterval(() => {
        timeLeft--;
        if(timeLeft < 0) timeLeft = 5;
        document.getElementById('countdown').innerText = timeLeft;
    }, 1000);
}
</script>
@endsection
