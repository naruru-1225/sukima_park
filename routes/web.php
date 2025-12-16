<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// レイアウト確認用（後で削除）
Route::get('/test-layout', function () {
    return view('test-layout');
});
