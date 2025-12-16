@extends('layouts.app')

@section('title', 'ログイン')

@section('content')
    <div class="auth-container">
        <div class="card auth-card">
            <div class="card-header">
                <h1>ログイン</h1>
            </div>
            <div class="card-body">
                <form action="{{ url('/login') }}" method="POST">
                    @csrf
                    
                    {{-- メールアドレス --}}
                    <div class="form-group">
                        <label class="form-label required">メールアドレス</label>
                        <input type="email" 
                               name="email" 
                               class="form-input @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}"
                               required 
                               autofocus>
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- パスワード --}}
                    <div class="form-group">
                        <label class="form-label required">パスワード</label>
                        <input type="password" 
                               name="password" 
                               class="form-input @error('password') is-invalid @enderror"
                               required>
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- ログイン状態を保持 --}}
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember" value="1">
                            ログイン状態を保持する
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">
                        ログイン
                    </button>
                </form>
            </div>
            <div class="card-footer text-center">
                <p>アカウントをお持ちでない方は <a href="{{ url('/register') }}">新規登録</a></p>
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
        max-width: 400px;
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
    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
    .text-center {
        text-align: center;
    }
    .is-invalid {
        border-color: var(--error) !important;
    }
</style>
@endpush
