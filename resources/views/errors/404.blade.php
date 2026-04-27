@extends('layouts.app')

@section('title', 'Không tìm thấy trang — 404')

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
    <div class="error-code">404</div>
    <div class="error-illustration">
        <i class="bi bi-search"></i>
    </div>
    <h1 class="error-title">Ôi! Trang này không tồn tại</h1>
    <p class="error-message">
        Có vẻ như đường dẫn bạn đang truy cập đã bị thay đổi hoặc không còn tồn tại trên hệ thống. 
        Hãy thử kiểm tra lại địa chỉ hoặc quay về trang chủ.
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
