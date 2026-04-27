@extends('layouts.app')

@section('title', 'Phiên làm việc hết hạn — 419')

@section('styles')
<style>
    .error-container {
        text-align: center;
        padding: var(--space-3xl) var(--space-lg);
        max-width: 600px;
        margin: 0 auto;
    }

    .error-code {
        font-size: 8rem;
        font-family: var(--font-serif);
        font-weight: 800;
        line-height: 1;
        color: var(--accent);
        margin-bottom: var(--space-md);
        opacity: 0.15;
    }

    .error-title {
        font-size: 2rem;
        margin-bottom: var(--space-md);
    }

    .error-message {
        color: var(--text-muted);
        font-size: 1.125rem;
        margin-bottom: var(--space-xl);
        line-height: 1.6;
    }

    .error-illustration {
        font-size: 4rem;
        color: var(--accent);
        margin-bottom: var(--space-lg);
    }
</style>
@endsection

@section('content')
<div class="error-container">
    <div class="error-code">419</div>
    <div class="error-illustration">
        <i class="bi bi-clock-history"></i>
    </div>
    <h1 class="error-title">Phiên làm việc đã hết hạn</h1>
    <p class="error-message">
        Do bạn đã ở trang này quá lâu mà không có hoạt động, kết nối bảo mật đã bị ngắt. 
        Vui lòng làm mới trang và thử lại.
    </p>
    <div class="btn-group justify-center">
        <button onclick="window.location.reload()" class="btn btn-primary">
            <i class="bi bi-arrow-clockwise"></i> Làm mới trang
        </button>
        <a href="{{ route('home') }}" class="btn btn-outline">
            <i class="bi bi-house"></i> Về trang chủ
        </a>
    </div>
</div>
@endsection
