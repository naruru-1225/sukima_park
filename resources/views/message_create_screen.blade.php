@extends('layouts.app')

@section('title', '新規メッセージ')

@push('styles')
<style>
    .main-content {
        padding: 40px 0;
    }

    .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .page-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 30px;
    }

    .back-btn {
        background: none;
        border: none;
        color: #4caf50;
        font-size: 24px;
        cursor: pointer;
        padding: 5px;
        text-decoration: none;
    }

    .page-title {
        color: #2e7d32;
        font-size: 24px;
        font-weight: 600;
    }

    .user-list {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .user-item {
        display: flex;
        align-items: center;
        padding: 16px 20px;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        transition: background 0.2s;
        text-decoration: none;
        color: inherit;
    }

    .user-item:hover {
        background: #f5f5f5;
    }

    .user-item:last-child {
        border-bottom: none;
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #66bb6a, #4caf50);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        font-weight: bold;
        margin-right: 15px;
    }

    .user-info {
        flex: 1;
    }

    .user-name {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .user-email {
        font-size: 13px;
        color: #888;
        margin-top: 2px;
    }

    .no-users {
        text-align: center;
        padding: 40px 20px;
        color: #888;
    }
</style>
@endpush

@section('content')
<main class="main-content">
    <div class="container">
        <div class="page-header">
            <a href="{{ route('messages.index') }}" class="back-btn">←</a>
            <h1 class="page-title">新規メッセージ</h1>
        </div>

        <div class="user-list">
            @forelse($users as $user)
                <a href="{{ route('messages.show', $user->USER_ID) }}" class="user-item">
                    <div class="user-avatar">{{ mb_substr($user->USERNAME, 0, 1) }}</div>
                    <div class="user-info">
                        <div class="user-name">{{ $user->USERNAME }}</div>
                        <div class="user-email">{{ $user->EMAIL }}</div>
                    </div>
                </a>
            @empty
                <div class="no-users">
                    メッセージを送れるユーザーがいません
                </div>
            @endforelse
        </div>
    </div>
</main>
@endsection
