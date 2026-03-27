import './bootstrap';

// ── Global Loading Overlay ─────────────────────────────────
// 1. LoadingStore + useLoading hook (window.LoadingStore, window.useLoading)
import './loading.js';

// 2. Axios Interceptors — tự động show/hide loading khi gọi HTTP
import './loading-axios.js';
