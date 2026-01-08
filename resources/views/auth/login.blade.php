@extends('layouts.app')

@section('title', 'ログイン')

@push('styles')
<style>
    .login-section {
        padding: 60px 0;
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
    }

    .form-container {
        background: var(--bg-white, #fff);
        border-radius: 8px;
        padding: 40px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        max-width: 480px;
        margin: 0 auto;
        width: 100%;
    }

    .form-container h1 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
        color: #222;
        text-align: center;
    }

    .form-subtitle {
        text-align: center;
        color: #888;
        font-size: 14px;
        margin-bottom: 32px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 6px;
        color: #555;
    }

    .required {
        color: #d32f2f;
        font-size: 12px;
        margin-left: 4px;
    }

    .form-control {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #2e7d32;
        box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
    }

    .form-control.is-invalid {
        border-color: #d32f2f;
    }

    .invalid-feedback {
        font-size: 12px;
        color: #d32f2f;
        margin-top: 4px;
        display: block;
    }

    .general-error {
        background: #ffebee;
        color: #d32f2f;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 20px;
        text-align: center;
        font-size: 14px;
    }

    .password-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 8px;
        font-size: 13px;
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        color: #555;
    }

    .remember-me input[type="checkbox"] {
        cursor: pointer;
        accent-color: #2e7d32;
    }

    .forgot-password {
        color: #2e7d32;
        text-decoration: none;
        font-weight: 500;
    }

    .forgot-password:hover {
        text-decoration: underline;
    }

    .submit-btn {
        width: 100%;
        padding: 12px;
        background: #2e7d32;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 24px;
        transition: all 0.2s;
    }

    .submit-btn:hover {
        background: #1b5e20;
    }

    .divider {
        text-align: center;
        margin: 24px 0;
        position: relative;
    }

    .divider::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #e0e0e0;
    }

    .divider span {
        background: #fff;
        padding: 0 16px;
        color: #888;
        font-size: 13px;
        position: relative;
        z-index: 1;
    }

    .register-link {
        text-align: center;
        font-size: 14px;
        color: #555;
    }

    .register-link a {
        color: #2e7d32;
        text-decoration: none;
        font-weight: 500;
    }

    .register-link a:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .login-section {
            padding: 40px 0;
        }

        .form-container {
            padding: 32px 24px;
        }

        .form-container h1 {
            font-size: 22px;
        }

        .password-options {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
    }
</style>
@endpush

@section('content')
<section class="login-section">
    <div class="form-container">
        <h1>ログイン</h1>
        <p class="form-subtitle">アカウントにログインしてください</p>

        {{-- エラーメッセージ（認証失敗時） --}}
        @if ($errors->has('email') && !$errors->first('email') == '必須項目です')
            <div class="general-error">
                {{ $errors->first('email') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- メールアドレス --}}
            <div class="form-group">
                <label for="email">
                    メールアドレス<span class="required">必須</span>
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="メールアドレスを入力"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                >
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            {{-- パスワード --}}
            <div class="form-group">
                <label for="password">
                    パスワード<span class="required">必須</span>
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="パスワードを入力"
                    required
                    autocomplete="current-password"
                >
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            {{-- パスワードオプション --}}
            <div class="password-options">
                <label class="remember-me">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>ログイン状態を保持する</span>
                </label>
                <a href="#" class="forgot-password" onclick="alert('パスワード再設定用のメールを送信しました。'); return false;">パスワードをお忘れですか？</a>
            </div>

            <button type="submit" class="submit-btn">ログイン</button>
        </form>

        <div class="divider">
            <span>または</span>
        </div>

        <div class="register-link">
            アカウントをお持ちでない方は
            <a href="{{ route('register') }}">新規登録</a>
        </div>
    </div>
</section>
@endsection
