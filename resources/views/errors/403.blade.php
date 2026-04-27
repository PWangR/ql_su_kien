@extends('layouts.app')

@section('title', 'Truy cập bị từ chối — 403')

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
        color: var(--warning);
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
        color: var(--warning);
        margin-bottom: var(--space-lg);
    }
</style>
@endsection

@section('content')
<div class="error-container">
    <div class="error-code">403</div>
    <div class="error-illustration">
        <i class="bi bi-shield-lock"></i>
    </div>
    <h1 class="error-title">Truy cập bị giới hạn</h1>
    <p class="error-message">
        Bạn không có quyền truy cập vào khu vực này. Nếu bạn tin rằng đây là một nhầm lẫn, 
        vui lòng liên hệ với quản trị viên hệ thống.
    </p>
    <div class="btn-group justify-center">
        <a href="{{ route('home') }}" class="btn btn-primary">
            <i class="bi bi-house"></i> Về trang chủ
        </a>
        <button onclick="history.back()" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i> Quay lại
        </button>
    </div>
</div>
@endsection
