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
use App\Http\Controllers\RentalController;
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
 * レンタル一覧テストページ（認証なし）
 * ※開発用：認証なしでレンタル一覧を表示
 */
Route::get('/test-rentals', function () {
    // テスト用の仮データを作成
    $rentals = collect([
        (object)[
            'RECORD_ID' => 1,
            'PRICE' => 3000,
            'PRICE_UNIT' => 0, // 0:日 1:時間 2:15分
            'RENTAL_START_DATE' => now()->addDays(2),
            'RENTAL_END_DATE' => now()->addDays(9),
            'land' => (object)[
                'LAND_ID' => 1,
                'CITY' => '渋谷区',
                'STREET_ADDRESS' => '神南1-2-3',
                'AREA' => 25.50,
                'IMAGE' => null,
            ]
        ],
        (object)[
            'RECORD_ID' => 2,
            'PRICE' => 500,
            'PRICE_UNIT' => 1,
            'RENTAL_START_DATE' => now()->addDays(5),
            'RENTAL_END_DATE' => now()->addDays(5),
            'land' => (object)[
                'LAND_ID' => 2,
                'CITY' => '新宿区',
                'STREET_ADDRESS' => '西新宿2-8-1',
                'AREA' => 15.00,
                'IMAGE' => null,
            ]
        ],
        (object)[
            'RECORD_ID' => 3,
            'PRICE' => 5000,
            'PRICE_UNIT' => 0,
            'RENTAL_START_DATE' => now()->addDays(15),
            'RENTAL_END_DATE' => now()->addDays(20),
            'land' => (object)[
                'LAND_ID' => 3,
                'CITY' => '港区',
                'STREET_ADDRESS' => '六本木6-10-1',
                'AREA' => 30.00,
                'IMAGE' => null,
            ]
        ],
    ]);
    return view('rental_list', ['rentals' => $rentals]);
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
// レンタル管理ルート
// ============================================================

/**
 * レンタル中の土地一覧
 * 
 * URL: /my-rentals
 * ミドルウェア: auth（ログイン必須）
 * 
 * ログインユーザーが現在借りている土地の一覧を表示
 */
Route::get('/my-rentals', [RentalController::class, 'index'])
    ->name('rentals.index')
    ->middleware('auth');

/**
 * レンタル詳細
 * 
 * URL: /my-rentals/{id}
 * ミドルウェア: auth（ログイン必須）
 * 
 * レンタル記録の詳細情報を表示
 */
Route::get('/my-rentals/{id}', [RentalController::class, 'show'])
    ->name('rentals.show')
    ->middleware('auth');

//テスト用ルート

Route::get('/test', function () {
    // テスト用ダミーデータ
    $users = collect([
        (object) ['id' => 1, 'name' => '田中 太郎', 'email' => 'tanaka.taro@example.com', 'created_at' => now()->subDays(30)],
        (object) ['id' => 2, 'name' => '佐藤 花子', 'email' => 'sato.hanako@example.com', 'created_at' => now()->subDays(60)],
        (object) ['id' => 3, 'name' => '鈴木 一郎', 'email' => 'suzuki.ichiro@example.com', 'created_at' => now()->subDays(90)],
    ]);
    return view('user_list', compact('users'));
});

