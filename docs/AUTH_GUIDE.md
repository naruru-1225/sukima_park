# ユーザ認証ガイド

このドキュメントでは、スキマパークアプリケーションにおけるユーザ認証の仕組みと使用方法について説明します。

---

## 目次

1. [認証システムの概要](#1-認証システムの概要)
2. [関連ファイル一覧](#2-関連ファイル一覧)
3. [認証の仕組み](#3-認証の仕組み)
4. [コントローラーでの使用方法](#4-コントローラーでの使用方法)
5. [Bladeテンプレートでの使用方法](#5-bladeテンプレートでの使用方法)
6. [ルートの保護](#6-ルートの保護)
7. [実装例](#7-実装例)

---

## 🚀 クイックリファレンス

**よく使う認証メソッド一覧**

| 場所 | 方法 | 説明 | 例 |
|------|------|------|-----|
| コントローラー | `Auth::check()` | ログイン状態を確認（true/false） | `if (Auth::check()) { ... }` |
| コントローラー | `Auth::guest()` | 未ログイン状態を確認（true/false） | `if (Auth::guest()) { ... }` |
| コントローラー | `Auth::id()` | ログインユーザーのUSER_IDを取得 | `$userId = Auth::id();` |
| コントローラー | `Auth::user()` | ログインユーザーのモデルを取得 | `$user = Auth::user();` |
| コントローラー | `Auth::login($user)` | ユーザーをログインさせる | `Auth::login($member);` |
| コントローラー | `Auth::logout()` | ログアウトする | `Auth::logout();` |
| Bladeテンプレート | `@auth` | ログイン中のみ表示 | `@auth ... @endauth` |
| Bladeテンプレート | `@guest` | 未ログイン時のみ表示 | `@guest ... @endguest` |
| ルート | `middleware('auth')` | ログイン必須のルート | `Route::get(...)->middleware('auth')` |
| ルート | `middleware('guest')` | 未ログイン専用のルート | `Route::get(...)->middleware('guest')` |

---


## 1. 認証システムの概要

このアプリケーションはLaravelの標準認証機能を使用しています。

### 認証方式
- **セッションベース認証**: ログイン状態はサーバー側のセッションで管理
- **クッキー保存**: セッションIDはブラウザのクッキーに保存

### ユーザモデル
- **テーブル**: `MEMBER_TABLE`
- **モデル**: `App\Models\Member`
- **主キー**: `USER_ID`

---

## 2. 関連ファイル一覧

| ファイル | 役割 |
|---------|------|
| `config/auth.php` | 認証設定（ユーザプロバイダーの指定） |
| `app/Models/Member.php` | ユーザモデル（認証対象） |
| `app/Http/Controllers/AuthController.php` | ログイン・会員登録・ログアウト処理 |
| `routes/web.php` | 認証関連のルート定義 |
| `resources/views/auth/login.blade.php` | ログインフォーム画面 |
| `resources/views/auth/register.blade.php` | 会員登録フォーム画面 |

---

## 3. 認証の仕組み

### 3.1 Memberモデルの設定

```php
// app/Models/Member.php

use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable  // ← 認証可能なモデルにするため継承
{
    protected $table = 'MEMBER_TABLE';   // テーブル名
    protected $primaryKey = 'USER_ID';    // 主キー
    
    // Laravel認証用：パスワードカラム名を指定
    public function getAuthPassword()
    {
        return $this->PASSWORD;
    }
}
```

**ポイント**:
- `Authenticatable`を継承することで、Laravelの認証機能が使えるようになる
- `getAuthPassword()`でパスワードカラム名を指定（デフォルトは`password`）

### 3.2 認証設定

```php
// config/auth.php

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\Member::class,  // Memberモデルを使用
    ],
],
```

### 3.3 ログイン処理の流れ

```
1. ユーザがログインフォームを送信
       ↓
2. AuthController@login が受け取る
       ↓
3. メールアドレスでMemberを検索
       ↓
4. パスワードをHash::checkで照合
       ↓
5. Auth::login($member) でセッションに保存
       ↓
6. トップページにリダイレクト
```

---

## 4. コントローラーでの使用方法

### 4.1 必要なuse文

```php
use Illuminate\Support\Facades\Auth;
```

### 4.2 ログイン状態の確認

```php
// ログインしているかどうか（true/false）
if (Auth::check()) {
    // ログイン中の処理
}

// ログインしていない場合
if (Auth::guest()) {
    // 未ログインの処理
}
```

### 4.3 ログインユーザの情報取得

```php
// ログインユーザのIDを取得
$userId = Auth::id();

// ログインユーザのモデル全体を取得
$user = Auth::user();

// ユーザ情報にアクセス
$username = Auth::user()->USERNAME;
$email = Auth::user()->EMAIL;
```

### 4.4 ログイン・ログアウト

```php
// ログイン（$memberはMemberモデルのインスタンス）
Auth::login($member);

// ログイン（"ログイン状態を保持"オプション付き）
Auth::login($member, $remember = true);

// ログアウト
Auth::logout();
```

### 4.5 実際の使用例

```php
// app/Http/Controllers/HomeController.php

public function index()
{
    $recentRentals = collect();

    // ログイン中のユーザーの最近借りた土地を取得
    if (Auth::check()) {
        $recentRentals = RentalRecord::with('land')
            ->where('USER_ID', Auth::id())  // ログインユーザのIDで絞り込み
            ->orderByDesc('RECORD_ID')
            ->take(5)
            ->get();
    }

    return view('home', compact('recentRentals'));
}
```

---

## 5. Bladeテンプレートでの使用方法

### 5.1 ログイン状態による表示切り替え

```blade
{{-- ログイン中のみ表示 --}}
@auth
    <p>ようこそ、{{ Auth::user()->USERNAME }}さん！</p>
    <a href="{{ route('logout') }}">ログアウト</a>
@endauth

{{-- 未ログイン時のみ表示 --}}
@guest
    <a href="{{ route('login') }}">ログイン</a>
    <a href="{{ route('register') }}">会員登録</a>
@endguest
```

### 5.2 auth と guest の組み合わせ

```blade
@auth
    {{-- ログイン中 --}}
    <div class="user-menu">
        <span>{{ Auth::user()->USERNAME }}</span>
    </div>
@else
    {{-- 未ログイン --}}
    <div class="guest-menu">
        <a href="{{ route('login') }}">ログイン</a>
    </div>
@endauth
```

### 5.3 ユーザ情報の表示

```blade
{{-- ログインユーザの情報を表示 --}}
@auth
    <p>ユーザ名: {{ Auth::user()->USERNAME }}</p>
    <p>メール: {{ Auth::user()->EMAIL }}</p>
    <p>ユーザID: {{ Auth::id() }}</p>
@endauth
```

---

## 6. ルートの保護

### 6.1 ミドルウェアの種類

| ミドルウェア | 説明 | 使用例 |
|-------------|------|--------|
| `auth` | ログイン必須 | マイページ、土地登録など |
| `guest` | 未ログイン専用 | ログインページ、会員登録ページ |

### 6.2 使用方法

```php
// routes/web.php

// 単一のルートに適用
Route::get('/mypage', [UserController::class, 'mypage'])
    ->middleware('auth');

// グループで適用（複数のルートをまとめて保護）
Route::middleware('auth')->group(function () {
    Route::get('/mypage', [UserController::class, 'mypage']);
    Route::get('/land/register', [LandController::class, 'create']);
    Route::post('/land/register', [LandController::class, 'store']);
});

// 未ログイン専用（ログイン済みはホームにリダイレクト）
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm']);
    Route::get('/register', [AuthController::class, 'showRegisterForm']);
});
```

### 6.3 現在のルート定義例

```php
// routes/web.php

// トップ画面（誰でもアクセス可能）
Route::get('/', [HomeController::class, 'index'])->name('home');

// ゲスト専用（ログイン済みはアクセス不可）
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ログアウト（認証済みのみ）
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');
```

---

## 7. 実装例

### 7.1 新しい画面にログイン必須を設定する

**ルート定義（routes/web.php）:**
```php
// 土地登録画面（ログイン必須）
Route::middleware('auth')->group(function () {
    Route::get('/land/register', [LandController::class, 'create'])->name('land.create');
    Route::post('/land/register', [LandController::class, 'store'])->name('land.store');
});
```

**コントローラー:**
```php
// app/Http/Controllers/LandController.php

public function create()
{
    // ログインユーザの情報を使用
    $user = Auth::user();
    
    return view('land.register', compact('user'));
}

public function store(Request $request)
{
    // ログインユーザのIDを使って土地を登録
    Land::create([
        'USER_ID' => Auth::id(),  // 所有者をログインユーザに設定
        'CITY' => $request->city,
        // ...その他のフィールド
    ]);
    
    return redirect()->route('home')->with('success', '土地を登録しました');
}
```

### 7.2 ヘッダーにユーザメニューを表示

```blade
{{-- resources/views/layouts/header.blade.php --}}

<header>
    <nav>
        <a href="{{ route('home') }}">スキマパーク</a>
        
        <div class="nav-right">
            @auth
                {{-- ログイン中: ユーザメニュー --}}
                <span>{{ Auth::user()->USERNAME }}</span>
                <a href="{{ route('mypage') }}">マイページ</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">ログアウト</button>
                </form>
            @else
                {{-- 未ログイン: ログイン・会員登録リンク --}}
                <a href="{{ route('login') }}">ログイン</a>
                <a href="{{ route('register') }}">会員登録</a>
            @endauth
        </div>
    </nav>
</header>
```

---

## よくある質問

### Q: ログインしていないのに `Auth::id()` を呼ぶとどうなる？
**A:** `null` が返ります。エラーにはなりません。

### Q: `Auth::user()` と `Auth::id()` の違いは？
**A:**
- `Auth::user()` → Memberモデルのインスタンス全体を取得
- `Auth::id()` → USER_ID（整数）のみを取得

### Q: パスワードはどのように保存される？
**A:** `Hash::make()` でハッシュ化されて保存されます。平文では保存されません。

```php
$member = Member::create([
    'PASSWORD' => Hash::make($request->password),  // ハッシュ化
    // ...
]);
```

### Q: ログイン状態はどれくらい維持される？
**A:** `.env` の `SESSION_LIFETIME` で設定（デフォルト120分）。
「ログイン状態を保持」にチェックすると、ブラウザを閉じても維持されます。

---

## 関連ドキュメント

- [TEAM_SETUP.md](./TEAM_SETUP.md) - チーム開発セットアップガイド
- [DOCKER_MIGRATION.md](./DOCKER_MIGRATION.md) - Docker環境移行ガイド
