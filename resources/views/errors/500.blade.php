@extends('layouts.app')

@section('title', 'Lỗi hệ thống — 500')

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
        color: var(--danger);
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
        color: var(--danger);
        margin-bottom: var(--space-lg);
    }
</style>
@endsection

@section('content')
<div class="error-container">
    <div class="error-code">500</div>
    <div class="error-illustration">
        <i class="bi bi-exclamation-triangle"></i>
    </div>
    <h1 class="error-title">Hệ thống đang gặp sự cố</h1>
    <p class="error-message">
        Chúng tôi xin lỗi vì sự bất tiện này. Đã có một lỗi xảy ra trong quá trình xử lý yêu cầu của bạn. 
        Đội ngũ kỹ thuật đã được thông báo và sẽ xử lý sớm nhất có thể.
    </p>
    <div class="btn-group justify-center">
        <a href="{{ route('home') }}" class="btn btn-primary">
            <i class="bi bi-house"></i> Về trang chủ
        </a>
        <a href="mailto:cntt@ntu.edu.vn" class="btn btn-outline">
            <i class="bi bi-envelope"></i> Báo cáo lỗi
        </a>
    </div>
</div>
@endsection
