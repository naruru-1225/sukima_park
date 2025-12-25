<?php
/**
 * ============================================================
 * Webルート定義 (web.php)
 * ============================================================
 * 
 * 【このファイルの役割】
 * URLとコントローラーのアクションを紐づける「ルーティング定義」
 * 
 * 【基本的な書き方】
 * Route::get('/URL', [コントローラー::class, 'メソッド名'])->name('ルート名');
 * 
 * 【HTTPメソッド】
 * - get:  データの取得（画面表示など）
 * - post: データの送信（フォーム送信など）
 * 
 * 【ミドルウェア】
 * - guest: 未ログインユーザーのみアクセス可能
 * - auth:  ログインユーザーのみアクセス可能
 * 
 * ============================================================
 */

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| ここでアプリケーションのWebルートを定義します。
| これらのルートはRouteServiceProviderによってロードされます。
|
*/

// ============================================================
// トップ画面ルート
// ============================================================

/**
 * トップ画面（index.php相当）
 * 
 * URL: /
 * コントローラー: HomeController@index
 * ルート名: home
 * 
 * 画面定義: index.csv
 */
Route::get('/', [HomeController::class, 'index'])->name('home');


// ============================================================
// 開発用ルート（本番前に削除）
// ============================================================

/**
 * レイアウト確認用テストページ
 * ※開発完了後は削除してください
 */
Route::get('/test-layout', function () {
    return view('test-layout');
});

/**
 * テストログイン（開発用）
 * URL: /test-login
 * 
 * データベースの最初のユーザーで自動ログインする
 * ※開発完了後は必ず削除してください
 */
Route::get('/test-login', function () {
    $user = \App\Models\Member::first();
    if ($user) {
        \Illuminate\Support\Facades\Auth::login($user);
        return redirect('/mypage')->with('success', 'テストログインしました: ' . $user->USERNAME);
    }
    return 'ユーザーが存在しません。php artisan db:seed --class=TestUserSeeder を実行してください。';
});


// ============================================================
// 認証ルート（ログイン・会員登録）
// ============================================================

/**
 * ゲスト（未ログイン）のみアクセス可能なルート
 * 
 * ログイン済みユーザーがアクセスした場合は
 * 自動的にホームにリダイレクトされます
 */
Route::middleware('guest')->group(function () {
    
    /**
     * ログインフォーム表示
     * URL: /login (GET)
     * 画面定義: login.csv
     */
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    
    /**
     * ログイン処理
     * URL: /login (POST)
     * フォームからのデータを受け取り認証を行う
     */
    Route::post('/login', [AuthController::class, 'login']);
    
    /**
     * 会員登録フォーム表示
     * URL: /register (GET)
     * 画面定義: menber_register.csv
     */
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    
    /**
     * 会員登録処理
     * URL: /register (POST)
     * フォームからのデータを受け取り会員を登録する
     */
    Route::post('/register', [AuthController::class, 'register']);
});

/**
 * ログアウト処理
 * URL: /logout (POST)
 * 
 * 認証済み（auth）のみアクセス可能
 * セッションを破棄してログアウトする
 */
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// ============================================================
// ユーザー関連ルート（ログイン必須）
// ============================================================

/**
 * ログイン必須のルート
 * 
 * 未ログインユーザーがアクセスした場合は
 * 自動的にログイン画面にリダイレクトされます
 */
Route::middleware('auth')->group(function () {
    
    /**
     * マイページ
     * URL: /mypage (GET)
     * コントローラー: UserController@mypage
     * ルート名: mypage
     * 
     * 画面定義: user_my.csv
     */
    Route::get('/mypage', [UserController::class, 'mypage'])->name('mypage');
});
