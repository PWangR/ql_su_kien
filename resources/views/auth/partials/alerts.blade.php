@if (session('error'))
    <div class="alert alert-error">
        <i class="bi bi-x-circle-fill"></i>
        <div>{{ session('error') }}</div>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>{!! session('warning') !!}</div>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        <i class="bi bi-check-circle-fill"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif

@if (session('info'))
    <div class="alert alert-info">
        <i class="bi bi-info-circle-fill"></i>
        <div>{{ session('info') }}</div>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-error">
        <i class="bi bi-exclamation-octagon-fill"></i>
        <div>
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    </div>
@endif
