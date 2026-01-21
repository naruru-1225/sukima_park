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
use App\Http\Controllers\LandController;
use App\Http\Controllers\LandPublicController;
use App\Http\Controllers\LoanDetailController;
use App\Http\Controllers\MyLandListController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserListController;
use App\Http\Controllers\UserDetailController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============================================================
// トップ画面
// ============================================================

Route::get('/', [HomeController::class, 'index'])->name('home');


// ============================================================
// 認証ルート（ログイン・会員登録・ログアウト）
// ============================================================

// ゲスト（未ログイン）のみアクセス可能
Route::middleware('guest')->group(function () {
    // ログイン
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // 会員登録
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ログアウト
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// ============================================================
// ユーザー関連ルート（ログイン必須）
// ============================================================

Route::middleware('auth')->group(function () {

    // --- マイページ ---
    Route::get('/mypage', [UserController::class, 'mypage'])->name('mypage');

    // --- プロフィール編集 ---
    Route::get('/prof_custom', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        return view('profile_edit_screen', compact('user'));
    })->name('prof_custom');

    // プロフィール確認画面
    Route::get('/profile/confirm', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        return view('profile_comfirmation_screen', compact('user'));
    })->name('profile.confirm');

    // --- 土地管理 ---
    Route::get('/my_land_list', [MyLandListController::class, 'index'])->name('my_land_list');
    Route::get('/loan_detail/{id}', [LoanDetailController::class, 'show'])->name('loan_detail');
    Route::get('/land_public/{id}', [LandPublicController::class, 'edit'])->name('land_public');
    Route::post('/land_public/{id}/toggle_status', [LandPublicController::class, 'toggleStatus'])->name('land_public.toggle_status');

    // --- 土地登録 ---
    Route::get('/land/register', function () {
        return view('land_register');
    })->name('land.register');

    Route::get('/land/register/confirm', function () {
        return view('land_register_confirm');
    })->name('land.register.confirm');

    // --- レンタル一覧（借りている土地一覧） ---
    Route::get('/rental_list', [RentalController::class, 'index'])->name('rental_list');
    Route::get('/rental_list/{id}', [RentalController::class, 'show'])->name('rental_list.show');

    // --- 取引完了一覧 ---
    Route::get('/trade_fin_list', [RentalController::class, 'completedList'])->name('trade_fin_list');
    Route::get('/trade_detail/{recordId}', [App\Http\Controllers\TradeDetailController::class, 'show'])->name('trade.detail');

    // レンタル履歴の別名ルート
    Route::get('/rental/history', [RentalController::class, 'completedList'])->name('rental.history');

    // --- レビュー関連 ---
    Route::post('/review/store/{recordId}', [ReviewController::class, 'store'])->name('review.store');

    // --- メッセージ ---
    Route::get('/messages', function () {
        return view('message_list_screen', ['messages' => collect([])]);
    })->name('messages.index');

    Route::get('/messages/{id}', function ($id) {
        return view('message_detail_screen', ['messageId' => $id]);
    })->name('messages.show');
});


// ============================================================
// 管理者向けユーザー管理ルート
// ============================================================

Route::middleware('auth')->group(function () {
    // ユーザー一覧
    Route::get('/admin/users', [UserListController::class, 'index'])->name('admin.users.index');

    // ユーザー詳細
    Route::get('/admin/users/{id}', [UserDetailController::class, 'show'])->name('admin.users.show');

    // ユーザー更新
    Route::put('/admin/users/{id}', [UserDetailController::class, 'update'])->name('admin.users.update');

    // ユーザー削除
    Route::delete('/admin/users/{id}', [UserDetailController::class, 'destroy'])->name('admin.users.destroy');
});


// ============================================================
// 公開ビュー確認用ルート（認証不要）
// ============================================================

// 土地検索結果
Route::get('/search', function () {
    return view('search_list', ['lands' => collect([])]);
})->name('search');

// お問い合わせフォーム
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// ユーザー詳細（他ユーザープロフィール）
Route::get('/users/{id}', function ($id) {
    $user = \App\Models\Member::findOrFail($id);
    return view('user_detail', compact('user'));
})->name('user.show');


// ============================================================
// 開発用ルート（本番前に削除）
// ============================================================

// テストログイン
Route::get('/test-login', function () {
    $user = \App\Models\Member::first();
    if ($user) {
        \Illuminate\Support\Facades\Auth::login($user);
        return redirect('/mypage')->with('success', 'テストログインしました: ' . $user->USERNAME);
    }
    return 'ユーザーが存在しません。php artisan db:seed --class=TestUserSeeder を実行してください。';
});

// レイアウト確認
Route::get('/test-layout', function () {
    return view('test-layout');
});

// レンタル一覧テスト
Route::get('/test-rentals', function () {
    $rentals = collect([
        (object) [
            'RECORD_ID' => 1,
            'PRICE' => 3000,
            'PRICE_UNIT' => 0,
            'RENTAL_START_DATE' => now()->addDays(2),
            'RENTAL_END_DATE' => now()->addDays(9),
            'land' => (object) [
                'LAND_ID' => 1,
                'CITY' => '渋谷区',
                'STREET_ADDRESS' => '神南1-2-3',
                'AREA' => 25.50,
                'IMAGE' => null,
            ]
        ],
    ]);
    return view('rental_list', ['rentals' => $rentals, 'detailRoute' => 'dev.rental-detail']);
});

// レンタル詳細テスト
Route::get('/dev/rental-detail', function () {
    $rental = (object) [
        'RECORD_ID' => 1,
        'PRICE' => 3000,
        'PRICE_UNIT' => 0,
        'RENTAL_START_DATE' => now()->addDays(2),
        'RENTAL_END_DATE' => now()->addDays(7),
        'RENTAL_START_TIME' => now()->setTime(8, 0),
        'RENTAL_END_TIME' => now()->setTime(20, 0),
        'land' => (object) [
            'CITY' => '渋谷区',
            'STREET_ADDRESS' => '神南1-2-3',
            'AREA' => 25.5,
            'IMAGE' => null,
        ],
        'review' => (object) [
            'RATING' => 5,
            'COMMENT' => 'テストレビューです。',
            'created_at' => now()->subDay(),
        ],
    ];
    return view('rental_detail', ['rental' => $rental, 'backRoute' => 'test-rentals']);
})->name('dev.rental-detail');

// ユーザー一覧テスト
Route::get('/test-users', function () {
    $users = collect([
        (object) ['id' => 1, 'name' => '田中 太郎', 'email' => 'tanaka.taro@example.com', 'created_at' => now()->subDays(30)],
        (object) ['id' => 2, 'name' => '佐藤 花子', 'email' => 'sato.hanako@example.com', 'created_at' => now()->subDays(60)],
    ]);
    return view('user_list', compact('users'));
});

// ユーザー詳細テスト
Route::get('/test-user-detail', function () {
    $user = (object) [
        'id' => 1,
        'login_id' => 'tanaka_taro',
        'name' => '田中 太郎',
        'email' => 'tanaka.taro@example.com',
        'phone' => '090-1234-5678',
        'birthday' => '1990-04-15',
        'gender' => 'male',
        'birthday_public' => 'private',
        'gender_public' => 'public',
        'status' => 'active',
        'bio' => '都内在住のフリーランスエンジニアです。',
        'avatar' => null,
        'created_at' => now()->subDays(30),
        'updated_at' => now()->subDays(5),
    ];
    return view('user_detail', compact('user'));
});
