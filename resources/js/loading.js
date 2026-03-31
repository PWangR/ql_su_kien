/**
 * ============================================================
 * LOADING STORE — Global State Manager
 * Tương đương Context API / Global Store cho Vanilla JS
 * ============================================================
 *
 * Tính năng:
 *  - Đếm số request đang chạy (hỗ trợ concurrent requests)
 *  - Debounce 300ms: không hiện overlay nếu request < 300ms
 *  - Aria-busy cho accessibility
 *  - CSS transition mượt mà 60fps
 */

const LoadingStore = (() => {
    // ── Internal State ──────────────────────────────────────
    let activeRequests = 0;   // Số request đang pending
    let debounceTimer = null; // Timer debounce
    let _visible = false; // Trạng thái thật sự của overlay

    // ── DOM Reference ───────────────────────────────────────
    const getOverlay = () => document.getElementById('global-loading-overlay');

    /**
     * Hiển thị overlay (gọi sau debounce)
     */
    function _show() {
        const overlay = getOverlay();
        if (!overlay || _visible) return;
        _visible = true;
        overlay.classList.add('is-active');
        overlay.setAttribute('aria-busy', 'true');
        // Focus trap & scroll lock
        document.body.style.overflow = 'hidden';
    }

    /**
     * Ẩn overlay
     */
    function _hide() {
        const overlay = getOverlay();
        if (!overlay || !_visible) return;
        _visible = false;
        overlay.classList.remove('is-active');
        overlay.setAttribute('aria-busy', 'false');
        document.body.style.overflow = '';
    }

    // ── Public API ──────────────────────────────────────────

    /**
     * showLoading() — Gọi trước khi bắt đầu một async task.
     * Áp dụng debounce 300ms: nếu hideLoading() được gọi trong vòng
     * 300ms, overlay sẽ KHÔNG bao giờ xuất hiện (tránh nháy màn hình).
     */
    function showLoading() {
        activeRequests++;

        if (activeRequests === 1 && !debounceTimer) {
            // Cho phép hiển thị gần như ngay lập tức (giảm từ 300ms xuống 50ms)
            debounceTimer = setTimeout(() => {
                debounceTimer = null;
                if (activeRequests > 0) {
                    _show();
                }
            }, 50);
        }
    }

    /**
     * hideLoading() — Gọi sau khi async task kết thúc (thành công hoặc lỗi).
     * Chỉ ẩn overlay khi TẤT CẢ request đã xong (concurrent safe).
     */
    function hideLoading() {
        if (activeRequests > 0) activeRequests--;

        if (activeRequests === 0) {
            // Hủy timer nếu request kết thúc trước 300ms → không hiện gì cả
            if (debounceTimer) {
                clearTimeout(debounceTimer);
                debounceTimer = null;
            }
            _hide();
        }
    }

    /**
     * isLoading() — Trả về trạng thái hiện tại.
     */
    function isLoading() {
        return activeRequests > 0;
    }

    /**
     * forceHide() — Reset toàn bộ state (dùng khi cần emergency reset).
     */
    function forceHide() {
        activeRequests = 0;
        if (debounceTimer) {
            clearTimeout(debounceTimer);
            debounceTimer = null;
        }
        _hide();
    }

    return { showLoading, hideLoading, isLoading, forceHide };
})();

// ============================================================
// useLoading — Custom Hook
// Trả về API để dùng ở bất kỳ đâu trong ứng dụng:
//   const { showLoading, hideLoading, isLoading } = window.useLoading();
// ============================================================
function useLoading() {
    return {
        showLoading: LoadingStore.showLoading,
        hideLoading: LoadingStore.hideLoading,
        isLoading: LoadingStore.isLoading,
        forceHide: LoadingStore.forceHide,
    };
}

// Expose ra global scope để dùng trong Blade templates & scripts inline
window.LoadingStore = LoadingStore;
window.useLoading = useLoading;

// [TÍNH NĂNG MỚI] Tự động show Loading khi submit các form truyền thống
document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('submit', (e) => {
        // Skip loading nếu form có data-skip-loading="true"
        if (e.target.hasAttribute('data-skip-loading') && e.target.getAttribute('data-skip-loading') === 'true') {
            return;
        }
        // Nếu form không có target="_blank" (mở tab mới)
        if (!e.target.hasAttribute('target') || e.target.getAttribute('target') !== '_blank') {
            LoadingStore.showLoading();
        }
    });
});

export { LoadingStore, useLoading };
