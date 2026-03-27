/**
 * ============================================================
 * AXIOS INTERCEPTORS — Auto Loading Integration
 * ============================================================
 *
 * Tự động hiển thị/ẩn Loading Overlay cho MỌI request Axios.
 *
 * Cách bỏ qua loading cho một request cụ thể:
 *   axios.get('/api/silent', { skipLoading: true })
 */

function setupAxiosLoadingInterceptors(axiosInstance) {
    if (!axiosInstance) {
        console.warn('[LoadingInterceptor] axiosInstance is undefined.');
        return;
    }

    const store = () => window.LoadingStore;

    // ── Request Interceptor ────────────────────────────────
    axiosInstance.interceptors.request.use(
        (config) => {
            if (!config.skipLoading) store()?.showLoading();
            return config;
        },
        (error) => {
            if (!error.config?.skipLoading) store()?.hideLoading();
            return Promise.reject(error);
        }
    );

    // ── Response Interceptor ───────────────────────────────
    axiosInstance.interceptors.response.use(
        (response) => {
            if (!response.config?.skipLoading) store()?.hideLoading();
            return response;
        },
        (error) => {
            if (!error.config?.skipLoading) store()?.hideLoading();
            return Promise.reject(error);
        }
    );

    console.info('[LoadingInterceptor] ✓ Interceptors attached to axios.');
}

// ESM modules chạy sau DOMContentLoaded — dùng setTimeout để đảm bảo
// bootstrap.js đã đặt window.axios trước khi chúng ta attach interceptors
setTimeout(() => {
    if (window.axios) {
        setupAxiosLoadingInterceptors(window.axios);
    } else {
        console.warn('[LoadingInterceptor] window.axios not available.');
    }
}, 0);

export { setupAxiosLoadingInterceptors };
