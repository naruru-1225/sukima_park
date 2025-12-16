@extends('layouts.app')

@section('title', '新規登録')

@section('content')
    <div class="auth-container">
        <div class="card auth-card">
            <div class="card-header">
                <h1>新規登録</h1>
            </div>
            <div class="card-body">
                <form action="{{ url('/register') }}" method="POST">
                    @csrf
                    
                    {{-- ユーザー名 --}}
                    <div class="form-group">
                        <label class="form-label required">ユーザー名</label>
                        <input type="text" 
                               name="username" 
                               class="form-input @error('username') is-invalid @enderror" 
                               value="{{ old('username') }}"
                               required 
                               autofocus>
                        @error('username')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- メールアドレス --}}
                    <div class="form-group">
                        <label class="form-label required">メールアドレス</label>
                        <input type="email" 
                               name="email" 
                               class="form-input @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}"
                               required>
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- パスワード --}}
                    <div class="form-group">
                        <label class="form-label required">パスワード（8文字以上）</label>
                        <input type="password" 
                               name="password" 
                               class="form-input @error('password') is-invalid @enderror"
                               required>
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- パスワード確認 --}}
                    <div class="form-group">
                        <label class="form-label required">パスワード（確認）</label>
                        <input type="password" 
                               name="password_confirmation" 
                               class="form-input"
                               required>
                    </div>
                    
                    {{-- 電話番号（任意） --}}
                    <div class="form-group">
                        <label class="form-label">電話番号（任意）</label>
                        <input type="tel" 
                               name="tel" 
                               class="form-input" 
                               value="{{ old('tel') }}"
                               placeholder="090-1234-5678">
                    </div>
                    
                    {{-- 生年月日（任意） --}}
                    <div class="form-group">
                        <label class="form-label">生年月日（任意）</label>
                        <input type="date" 
                               name="birth" 
                               class="form-input" 
                               value="{{ old('birth') }}">
                    </div>
                    
                    {{-- 性別（任意） --}}
                    <div class="form-group">
                        <label class="form-label">性別（任意）</label>
                        <select name="gender" class="form-select">
                            <option value="">選択しない</option>
                            <option value="1" {{ old('gender') == '1' ? 'selected' : '' }}>男性</option>
                            <option value="2" {{ old('gender') == '2' ? 'selected' : '' }}>女性</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">
                        登録する
                    </button>
                </form>
            </div>
            <div class="card-footer text-center">
                <p>すでにアカウントをお持ちの方は <a href="{{ url('/login') }}">ログイン</a></p>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .auth-container {
        display: flex;
        justify-content: center;
        padding: 40px 20px;
    }
    .auth-card {
        width: 100%;
        max-width: 480px;
    }
    .auth-card h1 {
        margin: 0;
        font-size: 1.5rem;
        text-align: center;
    }
    .btn-block {
        width: 100%;
        margin-top: 20px;
    }
    .text-center {
        text-align: center;
    }
    .is-invalid {
        border-color: var(--error) !important;
    }
</style>
@endpush
