@extends('layouts.app')

@section('title', 'プロフィール確認')

@push('styles')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI",
          "Hiragino Sans", sans-serif;
        line-height: 1.6;
        color: #333;
        background: #fafafa;
    }

    .container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 20px;
    }

    header {
        background: #fff;
        border-bottom: 1px solid #e0e0e0;
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .header-inner {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 60px;
    }

    .logo {
        font-size: 18px;
        font-weight: 600;
        color: #2e7d32;
        text-decoration: none;
    }

    .header-nav {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-primary {
        background: #2e7d32;
        color: #fff;
    }

    .btn-primary:hover {
        background: #1b5e20;
    }

    .btn-secondary {
        background: #f5f5f5;
        color: #333;
    }

    .btn-secondary:hover {
        background: #e0e0e0;
    }

    .icon-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #f5f5f5;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
    }

    .icon-btn:hover {
        background: #e0e0e0;
    }

    .main-content {
        padding: 40px 0;
    }

    h2 {
        color: #2e7d32;
        text-align: center;
        margin-bottom: 10px;
        font-size: 28px;
    }

    .subtitle {
        text-align: center;
        color: #66bb6a;
        margin-bottom: 30px;
        font-size: 14px;
    }

    .profile-image-section {
        text-align: center;
        margin-bottom: 30px;
    }

    .profile-image {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #4caf50;
        background: #e8f5e9;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        color: #66bb6a;
    }

    .confirm-section {
        background: #f1f8f4;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
    }

    .confirm-item {
        display: flex;
        padding: 15px 0;
        border-bottom: 1px solid #c8e6c9;
    }

    .confirm-item:last-child {
        border-bottom: none;
    }

    .confirm-label {
        color: #2e7d32;
        font-weight: 600;
        min-width: 120px;
        font-size: 14px;
    }

    .confirm-value {
        color: #333;
        flex: 1;
        font-size: 16px;
    }

    .button-group {
        display: flex;
        gap: 15px;
        margin-top: 35px;
    }

    button {
        flex: 1;
        padding: 14px;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-back {
        background: #e0e0e0;
        color: #666;
    }

    .btn-back:hover {
        background: #d0d0d0;
    }

    .btn-submit {
        background: linear-gradient(135deg, #4caf50 0%, #66bb6a 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
    }

    .notice {
        background: #fff9c4;
        border-left: 4px solid #fbc02d;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 25px;
        font-size: 14px;
        color: #666;
    }

    .notice-icon {
        display: inline-block;
        margin-right: 8px;
    }
</style>
@endpush

@section('content')
<header>
    <div class="container header-inner">
        <a href="{{ route('home') }}" class="logo">スキマパーク</a>
        <div class="header-nav">
            <a href="{{ route('land.register') }}" class="btn btn-primary">土地を登録</a>
            <button class="icon-btn" title="メッセージ">💬</button>
            <button class="icon-btn" title="アカウント">👤</button>
        </div>
    </div>
</header>

<main class="main-content">
    <div class="container">
        <h2>プロフィール確認</h2>
        <p class="subtitle">以下の内容で登録します。よろしいですか？</p>
        
        <div class="notice">
            <span class="notice-icon">ℹ️</span>
            内容を確認の上、「登録する」ボタンを押してください。修正する場合は「戻る」ボタンを押してください。
        </div>

        <form action="{{ route('profile.store') }}" method="POST">
            @csrf
            
            <!-- 非公開情報 -->
            <h3 style="color: #2e7d32; margin-bottom: 20px; border-bottom: 2px solid #c8e6c9; padding-bottom: 10px;">非公開情報</h3>
            <div class="confirm-section">
                <div class="confirm-item">
                    <div class="confirm-label">ログインID</div>
                    <div class="confirm-value">{{ $profileData['login_id'] ?? 'yamada_taro' }}</div>
                </div>

                <div class="confirm-item">
                    <div class="confirm-label">メールアドレス</div>
                    <div class="confirm-value">{{ $profileData['email'] ?? 'example@email.com' }}</div>
                </div>

                <div class="confirm-item">
                    <div class="confirm-label">パスワード</div>
                    <div class="confirm-value">********</div>
                </div>

                <div class="confirm-item">
                    <div class="confirm-label">電話番号</div>
                    <div class="confirm-value">{{ $profileData['phone'] ?? '090-1234-5678' }}</div>
                </div>
            </div>

            <!-- 公開情報 -->
            <h3 style="color: #2e7d32; margin: 40px 0 20px; border-bottom: 2px solid #c8e6c9; padding-bottom: 10px;">公開情報</h3>
            <div class="confirm-section">
                <div class="confirm-item">
                    <div class="confirm-label">生年月日</div>
                    <div class="confirm-value">
                        @if(isset($profileData['birthdate']))
                            {{ \Carbon\Carbon::parse($profileData['birthdate'])->locale('ja')->isoFormat('YYYY年M月D日') }}
                        @else
                            1990年1月1日
                        @endif
                    </div>
                </div>

                <div class="confirm-item">
                    <div class="confirm-label">生年月日の公開設定</div>
                    <div class="confirm-value">
                        {{ ($profileData['birthdate_visibility'] ?? 'public') === 'public' ? '公開' : '非公開' }}
                    </div>
                </div>

                <div class="confirm-item">
                    <div class="confirm-label">性別</div>
                    <div class="confirm-value">
                        @php
                            $genderLabels = [
                                'male' => '男性',
                                'female' => '女性',
                                'other' => 'その他',
                                'no_answer' => '回答しない'
                            ];
                            $gender = $profileData['gender'] ?? 'male';
                        @endphp
                        {{ $genderLabels[$gender] ?? $gender }}
                    </div>
                </div>

                <div class="confirm-item">
                    <div class="confirm-label">性別の公開設定</div>
                    <div class="confirm-value">
                        {{ ($profileData['gender_visibility'] ?? 'public') === 'public' ? '公開' : '非公開' }}
                    </div>
                </div>

                <div class="confirm-item">
                    <div class="confirm-label">ユーザ名</div>
                    <div class="confirm-value">{{ $profileData['username'] ?? '山田 太郎' }}</div>
                </div>

                <div class="confirm-item">
                    <div class="confirm-label">自己紹介</div>
                    <div class="confirm-value">{{ $profileData['bio'] ?? 'よろしくお願いします。' }}</div>
                </div>

                <div class="confirm-item">
                    <div class="confirm-label">アイコン画像</div>
                    <div class="profile-image-section" style="margin-top: 10px;">
                        @if(isset($profileData['profile_image']))
                            <img src="{{ $profileData['profile_image'] }}" alt="プロフィール画像" class="profile-image">
                        @else
                            <div class="profile-image">👤</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 隠しフィールドでデータを保持 -->
            @foreach($profileData ?? [] as $key => $value)
                @if($key !== 'password' && $key !== 'profile_image')
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach

            <div class="button-group">
                <button type="button" class="btn-back" onclick="history.back()">戻る</button>
                <button type="submit" class="btn-submit">登録する</button>
            </div>
        </form>
    </div>
</main>

@include('partials.footer')
@endsection

@push('scripts')
<script>
    // フォーム送信後の処理
    @if(session('success'))
        alert('{{ session('success') }}');
    @endif
</script>
@endpush