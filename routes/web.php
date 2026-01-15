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
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LandPublicController;
use App\Http\Controllers\LoanDetailController;
use App\Http\Controllers\MyLandListController;
use App\Http\Controllers\SearchListController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserListController;
use App\Http\Controllers\UserDetailController;
use App\Http\Controllers\RentalController;
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
// 土地検索ルート
// ============================================================

/**
 * 土地検索結果一覧
 * 
 * URL: /lands
 * コントローラー: SearchListController@index
 * ルート名: lands.index
 * 
 * 画面定義: search_list.csv
 */
Route::get('/lands', [SearchListController::class, 'index'])->name('lands.index');

/**
 * 土地詳細画面
 * 
 * URL: /lands/{id}
 * コントローラー: SearchListController@show
 * ルート名: lands.show
 * 
 * 画面定義: land_detail.csv
 */
Route::get('/lands/{id}', [SearchListController::class, 'show'])->name('lands.show');


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
    Route::get('/trade_fin_list', function () {
        return view('trade_list', ['trades' => collect([])]);
    })->name('trade_fin_list');

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
        (object) [
            'RECORD_ID' => 2,
            'PRICE' => 500,
            'PRICE_UNIT' => 1,
            'RENTAL_START_DATE' => now()->addDays(5),
            'RENTAL_END_DATE' => now()->addDays(5),
            'land' => (object) [
                'LAND_ID' => 2,
                'CITY' => '新宿区',
                'STREET_ADDRESS' => '西新宿2-8-1',
                'AREA' => 15.00,
                'IMAGE' => null,
            ]
        ],
        (object) [
            'RECORD_ID' => 3,
            'PRICE' => 5000,
            'PRICE_UNIT' => 0,
            'RENTAL_START_DATE' => now()->addDays(15),
            'RENTAL_END_DATE' => now()->addDays(20),
            'land' => (object) [
                'LAND_ID' => 3,
                'CITY' => '港区',
                'STREET_ADDRESS' => '六本木6-10-1',
                'AREA' => 30.00,
                'IMAGE' => null,
            ]
        ],
    ]);
    return view('rental_list', [
        'rentals' => $rentals,
        'detailRoute' => 'dev.rental-detail',
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

    /**
     * プロフィール編集（仮実装）
     * URL: /prof_custom (GET)
     * ルート名: prof_custom
     * 
     * 画面定義: prof_custom.csv
     * TODO: ProfileControllerを作成後、コントローラーに置き換える
     */
    Route::get('/prof_custom', function () {
        return 'プロフィール編集画面（未実装）';
    })->name('prof_custom');

    /**
     * 自己保持土地一覧
     * URL: /my_land_list (GET)
     * コントローラー: MyLandListController@index
     * ルート名: my_land_list
     * 
     * 画面定義: my_land_list.csv
     */
    Route::get('/my_land_list', [MyLandListController::class, 'index'])->name('my_land_list');

    /**
     * 貸出中詳細
     * URL: /loan_detail/{id} (GET)
     * コントローラー: LoanDetailController@show
     * ルート名: loan_detail
     */
    Route::get('/loan_detail/{id}', [LoanDetailController::class, 'show'])->name('loan_detail');

    /**
     * 土地貸出設定
     * URL: /land_public/{id} (GET)
     * コントローラー: LandPublicController@edit
     * ルート名: land_public
     * 
     * 画面定義: land_public.csv
     */
    Route::get('/land_public/{id}', [LandPublicController::class, 'edit'])->name('land_public');

    /**
     * 土地公開ステータス切り替え
     * URL: /land_public/{id}/toggle_status (POST)
     * コントローラー: LandPublicController@toggleStatus
     * ルート名: land_public.toggle_status
     * 
     * ステータス変更後のリダイレクト:
     * - 非公開→公開: loan_detail画面へ
     * - 公開→非公開: land_public画面へ
     */
    Route::post('/land_public/{id}/toggle_status', [LandPublicController::class, 'toggleStatus'])->name('land_public.toggle_status');

    /**
     * レンタル中一覧（仮実装）
     * URL: /rental_list (GET)
     * ルート名: rental_list
     * 
     * 画面定義: rental_list.csv
     * TODO: RentalControllerを作成後、コントローラーに置き換える
     */
    Route::get('/rental_list', function () {
        return 'レンタル中一覧画面（未実装）';
    })->name('rental_list');

    /**
     * 取引完了一覧（仮実装）
     * URL: /trade_fin_list (GET)
     * ルート名: trade_fin_list
     * 
     * 画面定義: trade_fin_list.csv
     * TODO: TradeControllerを作成後、コントローラーに置き換える
     */
    Route::get('/trade_fin_list', function () {
        return '取引完了一覧画面（未実装）';
    })->name('trade_fin_list');

    /**
     * お問い合わせフォーム表示
     * URL: /contact (GET)
     * コントローラー: ContactController@showForm
     * ルート名: contact.form
     */
    Route::get('/contact', [ContactController::class, 'showForm'])->name('contact.form');

    /**
     * お問い合わせ送信処理
     * URL: /contact (POST)
     * コントローラー: ContactController@store
     * ルート名: contact
     */
    Route::post('/contact', [ContactController::class, 'store'])->name('contact');
});


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
