@extends('admin.layout')

@section('title', 'Tạo bài đăng sự kiện')
@section('page-title', 'Tạo bài đăng sự kiện')

@section('styles')
<style>
    .create-event {
        padding-bottom: calc(var(--space-2xl) * 2);
    }

    .input-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: var(--space-md);
    }

    .submit-bar {
        position: fixed;
        left: var(--sidebar-w);
        right: 0;
        bottom: 0;
        background: var(--card);
        border-top: 1px solid var(--border);
        padding: var(--space-md) var(--space-lg);
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 100;
    }

    @media (max-width: 991.98px) {
        .input-grid {
            grid-template-columns: 1fr;
        }

        .submit-bar {
            left: 0;
            flex-direction: column;
            gap: var(--space-sm);
        }
    }
</style>
@endsection

@section('content')
    @include('admin.su_kien._composer', [
        'mode' => 'create',
        'submitRoute' => route('admin.su-kien.store'),
        'httpMethod' => null,
        'eventModel' => null,
    ])
@endsection
