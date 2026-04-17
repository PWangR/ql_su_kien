@extends('layouts.app')

@section('title', 'Quét QR điểm danh')

@section('styles')
<style>
.scanner-page {
    max-width: 860px;
    margin: 0 auto;
}

.scanner-shell {
    display: grid;
    grid-template-columns: 1.2fr 0.8fr;
    gap: var(--space-lg);
}

.scanner-card,
.scanner-guide {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--border-radius-lg);
    padding: var(--space-xl);
}

.scanner-headline {
    margin-bottom: var(--space-md);
}

.scanner-headline h1 {
    margin: 0 0 8px;
    font-size: 1.75rem;
}

.scanner-headline p {
    margin: 0;
    color: var(--text-muted);
}

.scanner-toolbar {
    display: flex;
    gap: 12px;
    align-items: end;
    flex-wrap: wrap;
    margin-bottom: var(--space-md);
}

.scanner-toolbar .form-group {
    flex: 1;
    min-width: 220px;
    margin-bottom: 0;
}

#reader {
    width: 100%;
    min-height: 320px;
    margin-top: var(--space-md);
    border-radius: var(--border-radius-md);
    overflow: hidden;
    border: 1px solid var(--border);
    background: var(--bg-alt);
}

.scanner-status,
.scan-result {
    margin-top: var(--space-md);
    padding: var(--space-md);
    border-radius: var(--border-radius);
    border: 1px solid var(--border);
    background: var(--bg-alt);
    color: var(--text-light);
}

.scanner-status.is-warning,
.scan-result.is-error {
    background: #fff7ed;
    border-color: #fb923c;
    color: #9a3412;
}

.scanner-status.is-success,
.scan-result.is-success {
    background: #ecfdf5;
    border-color: #10b981;
    color: #065f46;
}

.guide-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.guide-item {
    display: flex;
    gap: 12px;
    align-items: flex-start;
}

.guide-item i {
    color: var(--accent);
    font-size: 1.125rem;
    margin-top: 2px;
}

@media (max-width: 900px) {
    .scanner-shell {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

@section('content')
<div class="scanner-page">
    <div class="scanner-headline">
        <h1>Quét QR điểm danh</h1>
        <p>Dùng camera để quét mã QR sự kiện do ban tổ chức hiển thị trên màn hình. Sau khi quét thành công, hệ thống sẽ tự cập nhật điểm danh cho tài khoản của bạn.</p>
    </div>

    <div class="scanner-shell">
        <section class="scanner-card">
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

            <div id="scanResult" class="scan-result" style="display:none;"></div>

            <button class="btn btn-outline" id="resumeBtn" style="display:none;margin-top:var(--space-md);" onclick="startScanner()">
                <i class="bi bi-arrow-repeat"></i> Quét mã khác
            </button>
        </section>

        <aside class="scanner-guide">
            <h3 style="margin-top:0;">Cách sử dụng</h3>
            <div class="guide-list">
                <div class="guide-item">
                    <i class="bi bi-1-circle"></i>
                    <div>Mở màn hình QR điểm danh do quản trị viên hoặc ban tổ chức hiển thị.</div>
                </div>
                <div class="guide-item">
                    <i class="bi bi-2-circle"></i>
                    <div>Chọn <strong>Bật camera</strong> và đưa mã QR vào khung quét.</div>
                </div>
                <div class="guide-item">
                    <i class="bi bi-3-circle"></i>
                    <div>Nếu trình duyệt chặn camera, bạn có thể dùng <strong>Quét từ ảnh</strong> hoặc mở hệ thống bằng HTTPS.</div>
                </div>
                <div class="guide-item">
                    <i class="bi bi-4-circle"></i>
                    <div>Mã QR sự kiện chỉ có hiệu lực trong thời gian ngắn. Nếu báo hết hạn, hãy quét lại mã mới trên màn hình.</div>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection

@section('scripts')
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

function setScannerStatus(message, type = '') {
    const statusBox = document.getElementById('scannerStatus');
    statusBox.className = 'scanner-status' + (type ? ` is-${type}` : '');
    statusBox.innerHTML = message;
}

function showScanResult(isSuccess, message) {
    const resultBox = document.getElementById('scanResult');
    resultBox.className = 'scan-result ' + (isSuccess ? 'is-success' : 'is-error');
    resultBox.innerHTML = `<i class="bi ${isSuccess ? 'bi-check-circle' : 'bi-x-circle'}"></i> ${message}`;
    resultBox.style.display = 'block';
    document.getElementById('resumeBtn').style.display = 'inline-flex';
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
        'Môi trường trình duyệt hỗ trợ camera. Bấm <strong>"Bật camera"</strong> để bắt đầu quét QR sự kiện.',
        'success'
    );
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

    return 'Không thể khởi động camera trên trình duyệt này. Hãy đổi trình duyệt hoặc dùng "Quét từ ảnh".';
}

async function startScanner(showErrorOnFail = true) {
    const config = { fps: 10, qrbox: { width: 250, height: 250 } };
    const selectedCameraId = document.getElementById('cameraSelect').value;

    document.getElementById('scanResult').style.display = 'none';
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
        setScannerStatus('Camera đã sẵn sàng. Đưa QR sự kiện vào khung quét.', 'success');
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
            showScanResult(false, normalizedMessage);
        }
    }
}

function onScanSuccess(decodedText) {
    if (isProcessing) return;

    html5QrcodeScanner.stop().then(() => {
        isScannerRunning = false;
        isProcessing = true;
        processQRCode(decodedText);
    }).catch(err => {
        console.error("Lỗi khi dừng scanner:", err);
    });
}

function onScanFailure(error) {
    // Ignore noisy frame-level scan failures.
}

function processQRCode(text) {
    try {
        const payload = JSON.parse(text);

        if (payload.action !== 'diem_danh' || !payload.ma_su_kien || !payload.t) {
            throw new Error('Mã QR này không phải QR điểm danh sự kiện hợp lệ.');
        }

        axios.post('{{ route('events.process-scanner') }}', {
            action: payload.action,
            ma_su_kien: payload.ma_su_kien,
            diff: Math.abs(Date.now() - payload.t),
        }).then((response) => {
            showScanResult(true, response.data.message);
        }).catch((error) => {
            const message = error.response?.data?.message || error.message;
            showScanResult(false, message);
        });
    } catch (error) {
        showScanResult(false, 'Không thể giải mã QR: ' + error.message);
    }
}

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
            showScanResult(false, 'Không thể đọc mã QR từ ảnh đã chọn. ' + error.message);
        } finally {
            event.target.value = '';
        }
    });
});
</script>
@endsection
