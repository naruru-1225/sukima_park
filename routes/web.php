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
// 開発用ルート（本番前に削除）
// ============================================================

// データを渡して確認したい場合
Route::view('/test-design', 'login', ['EMAIL' => 'email@example.com', 'PASSWORD' => 'password']);

