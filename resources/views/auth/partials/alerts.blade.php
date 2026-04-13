@if (session('error'))
    <div class="auth-alert is-error" role="alert">
        <i class="bi bi-x-circle-fill"></i>
        <div>{!! session('error') !!}</div>
    </div>
@endif

@if (session('warning'))
    <div class="auth-alert is-warning" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>{!! session('warning') !!}</div>
    </div>
@endif

@if (session('success'))
    <div class="auth-alert is-success" role="alert">
        <i class="bi bi-check-circle-fill"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif

@if (session('info'))
    <div class="auth-alert is-info" role="alert">
        <i class="bi bi-info-circle-fill"></i>
        <div>{{ session('info') }}</div>
    </div>
@endif

@if ($errors->any())
    <div class="auth-alert is-error" role="alert">
        <i class="bi bi-exclamation-octagon-fill"></i>
        <div>
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    </div>
@endif
