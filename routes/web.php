<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// トップページ
Route::get('/', function () {
    return view('welcome');
});

// レイアウト確認用（後で削除）
Route::get('/test-layout', function () {
    return view('test-layout');
});

/*
|--------------------------------------------------------------------------
| 認証ルート
|--------------------------------------------------------------------------
*/

// ゲスト（未ログイン）のみアクセス可能
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ログアウト（認証済みのみ）
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
