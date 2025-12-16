<header class="header">
    <div class="header-container">
        {{-- ロゴ --}}
        <a href="{{ url('/') }}" class="logo">
            <span class="logo-icon">🏞️</span>
            <span class="logo-text">スキマパーク</span>
        </a>
        
        {{-- ナビゲーション --}}
        <nav class="nav">
            <ul class="nav-list">
                <li><a href="{{ url('/') }}" class="nav-link">トップ</a></li>
                <li><a href="{{ url('/lands') }}" class="nav-link">土地を探す</a></li>
                
                @auth
                    {{-- ログイン済みユーザー向けメニュー --}}
                    <li><a href="{{ url('/lands/create') }}" class="nav-link">土地を登録</a></li>
                    <li><a href="{{ url('/my-lands') }}" class="nav-link">マイ土地</a></li>
                    <li><a href="{{ url('/rentals') }}" class="nav-link">レンタル管理</a></li>
                    <li><a href="{{ url('/chats') }}" class="nav-link">メッセージ</a></li>
                    
                    <li class="nav-dropdown">
                        <button class="nav-link dropdown-toggle">
                            {{ Auth::user()->USERNAME ?? 'ユーザー' }}
                            <span class="dropdown-arrow">▼</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('/profile') }}">プロフィール</a></li>
                            <li><a href="{{ url('/profile/edit') }}">設定</a></li>
                            <li>
                                <form action="{{ url('/logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="logout-btn">ログアウト</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    {{-- 未ログインユーザー向けメニュー --}}
                    <li><a href="{{ url('/login') }}" class="nav-link">ログイン</a></li>
                    <li><a href="{{ url('/register') }}" class="nav-link btn-primary">新規登録</a></li>
                @endauth
            </ul>
        </nav>
        
        {{-- モバイルメニューボタン --}}
        <button class="mobile-menu-btn" aria-label="メニューを開く">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>
