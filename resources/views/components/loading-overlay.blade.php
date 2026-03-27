{{--
    ============================================================
    GLOBAL LOADING OVERLAY COMPONENT
    ============================================================
    Sử dụng:
      - Tự động: Axios Interceptors (loading-axios.js) kích hoạt
      - Thủ công: window.LoadingStore.showLoading() / hideLoading()
      - Hook:     const { showLoading, hideLoading } = window.useLoading()

    API bổ sung:
      - Bỏ qua loading cho 1 request: axios.get(url, { skipLoading: true })
      - Force reset: window.LoadingStore.forceHide()
    ============================================================
--}}
<div
    id="global-loading-overlay"
    role="status"
    aria-busy="false"
    aria-label="Đang tải, vui lòng chờ..."
    aria-live="polite"
>
    <div class="loading-spinner-wrap">
        {{-- Ring Spinner --}}
        <div class="loading-ring" aria-hidden="true"></div>

        {{-- Animated dots --}}
        <div class="loading-dots" aria-hidden="true">
            <span class="loading-dot"></span>
            <span class="loading-dot"></span>
            <span class="loading-dot"></span>
        </div>

        {{-- Label --}}
        <p class="loading-text">Đang xử lý...</p>
    </div>
</div>

{{-- Progress bar chạy dọc top của trang --}}
<div class="loading-progress-bar" id="loading-progress-bar" aria-hidden="true"></div>
