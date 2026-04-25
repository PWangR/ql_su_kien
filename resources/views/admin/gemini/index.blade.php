@extends('admin.layout')

@section('title', 'Cấu hình Gemini AI')
@section('page-title', 'Cấu hình Gemini AI')

@section('content')
<div class="card mb-lg">
    <div class="card-header">
        <div class="card-title">
            <i class="bi bi-robot"></i> Luồng hoạt động chatbot AI
        </div>
    </div>
    <div class="card-body">
        <div class="grid grid-2 gap-lg">
            <div>
                <h3 style="font-size:1rem;margin-bottom:10px;">Luồng xử lý</h3>
                <ol style="padding-left:18px;line-height:1.8;color:var(--text-light);">
                    <li>Người dùng mở widget chatbot trên giao diện web.</li>
                    <li>Frontend gửi câu hỏi tới route <span class="mono">/chatbot/ask</span>.</li>
                    <li>Backend phân loại intent và ưu tiên trả lời bằng dữ liệu nội bộ qua truy vấn SQL.</li>
                    <li>Chỉ khi không khớp câu hỏi mẫu hoặc cần diễn đạt mở rộng, service mới gọi Gemini API.</li>
                    <li>Prompt fallback được rút gọn theo đúng ngữ cảnh liên quan để giảm token.</li>
                </ol>
            </div>
            <div>
                <h3 style="font-size:1rem;margin-bottom:10px;">Phạm vi hỗ trợ</h3>
                <ul style="padding-left:18px;line-height:1.8;color:var(--text-light);">
                    <li>Sự kiện sắp diễn ra, đang diễn ra, địa điểm, thời gian.</li>
                    <li>Cách đăng ký, hủy đăng ký, điều kiện tham gia.</li>
                    <li>Điểm cộng và trạng thái tham gia gần đây của người dùng.</li>
                    <li>Nếu hệ thống không có dữ liệu, chatbot sẽ từ chối suy diễn.</li>
                    <li>Khi AI tắt, chatbot vẫn trả lời được nhóm câu hỏi chuẩn bằng dữ liệu sẵn có.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card mb-lg">
    <div class="card-header">
        <div class="card-title">
            <i class="bi bi-sliders"></i> Cấu hình Gemini API
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.gemini.update') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ $gemini->is_active ? 'checked' : '' }}
                        style="width:18px;height:18px;margin-top:2px;">
                    <span style="text-transform:none;font-size:0.9375rem;color:var(--text);">
                        Bật Gemini fallback cho người dùng
                        <small style="display:block;color:var(--text-muted);text-transform:none;letter-spacing:0;">
                            Chatbot luôn ưu tiên dữ liệu nội bộ. Khi bật mục này, hệ thống mới dùng Gemini cho câu hỏi mở hoặc ngoài bộ intent SQL.
                        </small>
                    </span>
                </label>
            </div>

            <hr class="section-rule">

            <div class="input-grid">
                <div class="form-group">
                    <label class="form-label" for="model">Model Gemini <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        id="model"
                        name="model"
                        class="form-control @error('model') is-invalid @enderror"
                        value="{{ old('model', $gemini->model) }}"
                        placeholder="gemini-2.5-flash"
                        required
                    >
                    @error('model')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-hint">Ví dụ theo docs hiện tại: <span class="mono">gemini-2.5-flash</span>.</div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="api_key">Gemini API Key</label>
                    <input
                        type="password"
                        id="api_key"
                        name="api_key"
                        class="form-control @error('api_key') is-invalid @enderror"
                        placeholder="{{ $gemini->hasApiKey() ? '•••••••••••••••• (đã lưu)' : 'Nhập Gemini API key' }}"
                    >
                    @error('api_key')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-hint">Để trống nếu không muốn thay API key hiện tại.</div>
                </div>
            </div>

            <div class="input-grid">
                <div class="form-group">
                    <label class="form-label" for="temperature">Temperature</label>
                    <input
                        type="number"
                        id="temperature"
                        name="temperature"
                        class="form-control @error('temperature') is-invalid @enderror"
                        value="{{ old('temperature', $gemini->temperature) }}"
                        min="0"
                        max="2"
                        step="0.1"
                        required
                    >
                    @error('temperature')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="max_output_tokens">Max Output Tokens</label>
                    <input
                        type="number"
                        id="max_output_tokens"
                        name="max_output_tokens"
                        class="form-control @error('max_output_tokens') is-invalid @enderror"
                        value="{{ old('max_output_tokens', $gemini->max_output_tokens) }}"
                        min="128"
                        max="4096"
                        step="1"
                        required
                    >
                    @error('max_output_tokens')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="system_prompt">System Prompt</label>
                <textarea
                    id="system_prompt"
                    name="system_prompt"
                    rows="12"
                    class="form-control @error('system_prompt') is-invalid @enderror"
                    placeholder="Hướng dẫn cho chatbot..."
                >{{ old('system_prompt', $gemini->system_prompt) }}</textarea>
                @error('system_prompt')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-hint">Prompt này chỉ dùng cho lớp fallback Gemini sau khi hệ thống không tự trả lời được bằng dữ liệu nội bộ.</div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Lưu cấu hình
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="bi bi-lightning-charge"></i> Kiểm tra kết nối Gemini
        </div>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label class="form-label" for="geminiTestPrompt">Câu hỏi test</label>
            <textarea id="geminiTestPrompt" class="form-control" rows="3" placeholder="Ví dụ: Có sự kiện nào sắp diễn ra trong hệ thống không?">Có sự kiện nào sắp diễn ra trong hệ thống không?</textarea>
        </div>

        <div class="btn-group" style="margin-bottom:var(--space-md);">
            <button type="button" class="btn btn-outline" id="btnTestGemini">
                <i class="bi bi-play-circle"></i> Test Gemini
            </button>
        </div>

        <div id="geminiTestResult" class="hidden"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const btnTestGemini = document.getElementById('btnTestGemini');
const geminiPrompt = document.getElementById('geminiTestPrompt');
const geminiResult = document.getElementById('geminiTestResult');

if (btnTestGemini) {
    btnTestGemini.addEventListener('click', async () => {
        const prompt = geminiPrompt.value.trim();

        if (!prompt) {
            geminiResult.className = 'alert alert-warning';
            geminiResult.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Vui lòng nhập câu hỏi test.';
            return;
        }

        btnTestGemini.disabled = true;
        btnTestGemini.innerHTML = '<i class="bi bi-arrow-repeat"></i> Đang kiểm tra...';
        geminiResult.className = 'alert alert-info';
        geminiResult.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang gửi yêu cầu tới Gemini...';

        try {
            const response = await axios.post('{{ route('admin.gemini.test') }}', { prompt });
            geminiResult.className = 'alert alert-success';
            geminiResult.innerHTML = `<i class="bi bi-check-circle"></i> <div>${escapeHtml(response.data.reply).replace(/\n/g, '<br>')}</div>`;
        } catch (error) {
            const message = error?.response?.data?.message || 'Không thể kết nối Gemini API.';
            geminiResult.className = 'alert alert-error';
            geminiResult.innerHTML = `<i class="bi bi-x-circle"></i> <div>${escapeHtml(message)}</div>`;
        } finally {
            btnTestGemini.disabled = false;
            btnTestGemini.innerHTML = '<i class="bi bi-play-circle"></i> Test Gemini';
        }
    });
}

function escapeHtml(text) {
    return text
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}
</script>
@endsection
