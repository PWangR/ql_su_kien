@extends('admin.layout')

@section('title', 'Máy quét QR (Quét thẻ User)')
@section('page-title', 'QR Scanner')

@section('styles')
<style>
.scanner-container {
    max-width: 600px;
    margin: 0 auto;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--border-radius-lg);
    padding: var(--space-xl);
    text-align: center;
}

#reader {
    width: 100%;
    margin-top: var(--space-md);
    border-radius: var(--border-radius-md);
    overflow: hidden;
    border: 1px solid var(--border);
}

.result-box {
    margin-top: var(--space-md);
    padding: var(--space-md);
    border-radius: var(--border-radius);
    display: none;
    font-size: 0.9375rem;
}

.result-success {
    background: #ecfdf5;
    border: 1px solid #10b981;
    color: #065f46;
}

.result-error {
    background: #fef2f2;
    border: 1px solid #ef4444;
    color: #991b1b;
}

.btn-scan {
    margin-top: var(--space-md);
}
</style>
@endsection

@section('content')
<div class="scanner-container">
    <h2 style="margin-bottom: var(--space-sm);">Máy Quét Thẻ Sinh Viên</h2>
    <p class="text-muted" style="margin-bottom: var(--space-md);">Sử dụng camera để quét mã QR trên màn hình điểm danh của người dùng.</p>
    
    <div id="reader"></div>
    
    <div id="result" class="result-box mt-3"></div>

    <button class="btn btn-outline btn-scan" id="resumeBtn" style="display:none;" onclick="startScanner()">
        <i class="bi bi-play-circle"></i> Tiếp tục quét mã khác
    </button>
</div>
@endsection

@section('scripts')
<!-- Thư viện HTML5 QRCode -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
let html5QrcodeScanner;
let isProcessing = false;

function initScanner() {
    html5QrcodeScanner = new Html5Qrcode("reader");
    startScanner();
}

function startScanner() {
    const config = { fps: 10, qrbox: { width: 250, height: 250 } };
    
    // Resume UI state
    document.getElementById('result').style.display = 'none';
    document.getElementById('resumeBtn').style.display = 'none';
    isProcessing = false;

    // Start using rear camera by default if available
    html5QrcodeScanner.start(
        { facingMode: "environment" },
        config,
        onScanSuccess,
        onScanFailure
    ).catch(err => {
        console.error("Error starting scanner:", err);
        showResult(false, "Lỗi truy cập máy ảnh: " + err);
    });
}

function onScanSuccess(decodedText, decodedResult) {
    if (isProcessing) return;
    
    // Tạm dừng scanner sau khi quét được 1 mã
    html5QrcodeScanner.stop().then(() => {
        isProcessing = true;
        processQRCode(decodedText);
    }).catch(err => {
        console.error("Lỗi khi dừng scanner:", err);
    });
}

function onScanFailure(error) {
    // console.warn(`Scan error: ${error}`);
}

function processQRCode(text) {
    try {
        const payload = JSON.parse(text);
        
        if (!payload.mssv || !payload.ma_su_kien) {
            throw new Error("Mã QR không đúng định dạng điểm danh của hệ thống.");
        }

        // Hiện overlay loading (tùy định nghĩa trong app.js, tạm skip nếu ko có store)
        // Send request 
        axios.post('{{ route('admin.diem-danh.process-scanner') }}', {
            mssv: payload.mssv,
            ma_su_kien: payload.ma_su_kien
        })
        .then(response => {
            showResult(true, `[SV: ${payload.mssv}] ${response.data.message}`);
        })
        .catch(error => {
            const msg = error.response ? error.response.data.message : error.message;
            showResult(false, `[SV: ${payload.mssv}] Lỗi: ${msg}`);
        });

    } catch (e) {
        showResult(false, "Lỗi giải mã QR: " + e.message);
    }
}

function showResult(isSuccess, message) {
    const resBox = document.getElementById('result');
    resBox.className = 'result-box ' + (isSuccess ? 'result-success' : 'result-error');
    resBox.innerHTML = `<i class="bi ${isSuccess ? 'bi-check-circle' : 'bi-x-circle'}"></i> ${message}`;
    resBox.style.display = 'block';
    
    // Hiện nút tiếp tục quét
    document.getElementById('resumeBtn').style.display = 'inline-block';
}

// Chạy luôn khi trang load xong
document.addEventListener("DOMContentLoaded", () => {
    initScanner();
});
</script>
@endsection
