@extends('admin.layout')

@section('title', 'Quét QR người dùng')
@section('page-title', 'Quét QR người dùng')

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

.scanner-toolbar {
    display: flex;
    gap: 12px;
    align-items: end;
    flex-wrap: wrap;
    margin-bottom: var(--space-md);
    text-align: left;
}

.scanner-toolbar .form-group {
    flex: 1;
    min-width: 220px;
    margin-bottom: 0;
}

.scanner-status {
    margin-bottom: var(--space-md);
    padding: var(--space-md);
    border-radius: var(--border-radius);
    text-align: left;
    font-size: 0.9375rem;
    border: 1px solid var(--border);
    background: var(--bg-alt);
    color: var(--text-light);
}

.scanner-status.is-warning {
    background: #fff7ed;
    border-color: #fb923c;
    color: #9a3412;
}

.scanner-status.is-success {
    background: #ecfdf5;
    border-color: #10b981;
    color: #065f46;
}
</style>
@endsection

@section('content')
<div class="scanner-container">
    <h2 style="margin-bottom: var(--space-sm);">Quét QR cá nhân của sinh viên</h2>
    <p class="text-muted" style="margin-bottom: var(--space-md);">Trang này dành cho quản trị viên hoặc ban tổ chức để quét mã QR cá nhân mà người dùng hiển thị trên trang sự kiện của họ. Nếu trình duyệt không hỗ trợ camera trực tiếp, bạn vẫn có thể tải ảnh QR lên để nhận diện.</p>

    <div class="scanner-toolbar">
        <div class="form-group">
            <label class="form-label" for="cameraSelect">Nguồn camera</label>
            <select id="cameraSelect" class="form-control">
                <option value="">Tự động chọn camera tốt nhất</option>
            </select>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary" type="button" id="startScannerBtn" onclick="startScanner(true)">
                <i class="bi bi-camera-video"></i> Bật camera
            </button>
            <label class="btn btn-outline" for="qrFileInput" style="cursor:pointer;">
                <i class="bi bi-image"></i> Quét từ ảnh
            </label>
        </div>
        <input type="file" id="qrFileInput" accept="image/*" style="display:none;">
    </div>

    <div id="scannerStatus" class="scanner-status">
        Kiểm tra môi trường camera trước khi bật máy quét...
    </div>
    
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
let isScannerRunning = false;

function initScanner() {
    html5QrcodeScanner = new Html5Qrcode("reader");
    showEnvironmentStatus();
    loadCameraOptions();
}

async function loadCameraOptions() {
    const cameraSelect = document.getElementById('cameraSelect');

    if (!window.isSecureContext || !navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        return;
    }

    try {
        const devices = await Html5Qrcode.getCameras();
        devices.forEach((device, index) => {
            const option = document.createElement('option');
            option.value = device.id;
            option.textContent = device.label || `Camera ${index + 1}`;
            cameraSelect.appendChild(option);
        });
    } catch (error) {
        console.warn('Không lấy được danh sách camera:', error);
    }
}

function setScannerStatus(message, type = '') {
    const statusBox = document.getElementById('scannerStatus');
    statusBox.className = 'scanner-status' + (type ? ` is-${type}` : '');
    statusBox.innerHTML = message;
}

function showEnvironmentStatus() {
    const currentOrigin = `${window.location.protocol}//${window.location.host}`;

    if (!window.isSecureContext) {
        setScannerStatus(
            `Trình duyệt đang mở trang ở môi trường không an toàn: <strong>${currentOrigin}</strong>. Camera web chỉ hoạt động ổn định trên <strong>HTTPS</strong> hoặc <strong>localhost</strong>. Bạn có thể chuyển sang HTTPS hoặc dùng tạm nút <strong>"Quét từ ảnh"</strong>.`,
            'warning'
        );
        return;
    }

    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        setScannerStatus(
            'Trình duyệt này không hỗ trợ <code>mediaDevices.getUserMedia()</code>. Hãy dùng Chrome/Edge bản mới hoặc quét bằng ảnh QR.',
            'warning'
        );
        return;
    }

    setScannerStatus(
        'Môi trường trình duyệt hỗ trợ camera. Bấm <strong>"Bật camera"</strong> để xin quyền truy cập và bắt đầu quét.',
        'success'
    );
}

async function ensureCameraAccess() {
    if (!window.isSecureContext) {
        throw new Error('Trang hiện tại không chạy trong secure context. Hãy dùng HTTPS hoặc localhost để bật camera.');
    }

    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        throw new Error('Trình duyệt không hỗ trợ truy cập camera qua mediaDevices.');
    }

    const stream = await navigator.mediaDevices.getUserMedia({
        video: {
            facingMode: 'environment'
        },
        audio: false
    });

    stream.getTracks().forEach(track => track.stop());
}

async function startScanner(showErrorOnFail = true) {
    const config = { fps: 10, qrbox: { width: 250, height: 250 } };
    const selectedCameraId = document.getElementById('cameraSelect').value;
    
    // Resume UI state
    document.getElementById('result').style.display = 'none';
    document.getElementById('resumeBtn').style.display = 'none';
    isProcessing = false;

    if (isScannerRunning) {
        await html5QrcodeScanner.stop().catch(() => {});
        isScannerRunning = false;
    }

    try {
        await ensureCameraAccess();

        const cameraConfig = selectedCameraId ? selectedCameraId : { facingMode: "environment" };
        await html5QrcodeScanner.start(cameraConfig, config, onScanSuccess, onScanFailure);
        isScannerRunning = true;
        setScannerStatus('Camera đã sẵn sàng. Đưa QR cá nhân của sinh viên vào khung quét.', 'success');
    } catch (primaryError) {
        console.error("Error starting scanner:", primaryError);

        try {
            const devices = await Html5Qrcode.getCameras();
            if (devices.length > 0) {
                await html5QrcodeScanner.start(devices[0].id, config, onScanSuccess, onScanFailure);
                document.getElementById('cameraSelect').value = devices[0].id;
                isScannerRunning = true;
                setScannerStatus('Đã chuyển sang camera khả dụng đầu tiên của thiết bị.', 'success');
                return;
            }
        } catch (fallbackError) {
            console.error("Fallback camera error:", fallbackError);
        }

        if (showErrorOnFail) {
            const normalizedMessage = normalizeCameraError(primaryError);
            setScannerStatus(normalizedMessage, 'warning');
            showResult(false, normalizedMessage);
        }
    }
}

function normalizeCameraError(error) {
    const rawMessage = error?.message || String(error || '');

    if (!window.isSecureContext) {
        return 'Trình duyệt chặn camera vì trang chưa chạy bằng HTTPS hoặc localhost. Hãy bật HTTPS cho tên miền hiện tại hoặc dùng "Quét từ ảnh".';
    }

    if (rawMessage.includes('NotAllowedError') || rawMessage.includes('Permission denied')) {
        return 'Bạn đã từ chối quyền camera. Hãy cấp lại quyền camera cho trình duyệt rồi thử lại.';
    }

    if (rawMessage.includes('NotFoundError') || rawMessage.includes('Requested device not found')) {
        return 'Không tìm thấy camera trên thiết bị này. Hãy kiểm tra webcam hoặc dùng "Quét từ ảnh".';
    }

    if (rawMessage.includes('NotReadableError') || rawMessage.includes('Could not start video source')) {
        return 'Camera đang được ứng dụng khác sử dụng hoặc trình duyệt không đọc được luồng video. Hãy đóng ứng dụng đang chiếm camera rồi thử lại.';
    }

    if (rawMessage.includes('streaming not supported')) {
        return 'Trình duyệt không hỗ trợ camera streaming ở ngữ cảnh hiện tại. Nguyên nhân thường là thiếu HTTPS hoặc chính sách bảo mật của trình duyệt.';
    }

    return 'Không thể khởi động camera trên trình duyệt này. Hãy đổi trình duyệt, chọn camera khác hoặc dùng "Quét từ ảnh".';
}

function onScanSuccess(decodedText, decodedResult) {
    if (isProcessing) return;
    
    // Tạm dừng scanner sau khi quét được 1 mã
    html5QrcodeScanner.stop().then(() => {
        isScannerRunning = false;
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
        const mssv = payload.ma_sinh_vien || payload.mssv;

        if (payload.action === 'diem_danh') {
            throw new Error("Đây là QR sự kiện dành cho ứng dụng di động, không phải QR cá nhân để máy quét web admin xử lý.");
        }
        
        if (payload.action !== 'student_checkin' || !mssv || !payload.ma_su_kien) {
            throw new Error("Mã QR không đúng định dạng điểm danh sinh viên của hệ thống.");
        }

        axios.post('{{ route('admin.diem-danh.process-scanner') }}', {
            mssv: mssv,
            ma_su_kien: payload.ma_su_kien
        })
        .then(response => {
            showResult(true, `[SV: ${mssv}] ${response.data.message}`);
        })
        .catch(error => {
            const msg = error.response ? error.response.data.message : error.message;
            showResult(false, `[SV: ${mssv}] Lỗi: ${msg}`);
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

    document.getElementById('qrFileInput').addEventListener('change', async (event) => {
        const [file] = event.target.files || [];
        if (!file) {
            return;
        }

        try {
            if (isScannerRunning) {
                await html5QrcodeScanner.stop().catch(() => {});
                isScannerRunning = false;
            }

            const decodedText = await html5QrcodeScanner.scanFile(file, true);
            isProcessing = true;
            processQRCode(decodedText);
        } catch (error) {
            showResult(false, 'Không thể đọc mã QR từ ảnh đã chọn. ' + error.message);
        } finally {
            event.target.value = '';
        }
    });
});
</script>
@endsection
