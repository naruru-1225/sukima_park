{{--
============================================================
トップ画面 (home.blade.php)aaaaaaaaaaaaaaaaaaaaaaaaaaaa
============================================================

【対応画面定義】
  - index.csv（トップ画面 - index.php）

【このファイルの役割】
  - サイトのトップページを表示
  - 土地検索フォーム
  - 最近借りた土地の一覧（ログイン時のみ）

【受け取るデータ】
  - $recentRentals: ログインユーザーが最近借りた土地（5件まで）
    → HomeController@index から渡される
    → RentalRecordモデルのコレクション（landリレーション含む）

【画面構成】
  1. ヒーローセクション（検索フォーム）
     - フリーワード検索
     - あいまい検索（オン/オフ）
     - 利用日（カレンダー）
     - 都道府県（プルダウン）
     - 市区町村（テキスト入力）
     - 利用時間（開始・終了 15分刻み）
     - 料金上限
     - 料金単位（日/時間/15分あたり）
     - 面積
     - 地図から検索
     
  2. 最近借りた土地セクション
     - ログイン時: 土地カード5件表示
     - 未ログイン時: ログイン促進メッセージ

============================================================
--}}

{{-- layouts/app.blade.phpを継承（共通のHTML構造を使用） --}}
@extends('layouts.app')

{{-- ページタイトル（<title>タグの内容） --}}
@section('title', 'スキマパーク - あなたに合った土地を見つけよう')

{{-- メインコンテンツ開始 --}}
@section('content')
    {{-- 
    =====================================================
    ヒーローセクション
    =====================================================
    サイトのメインビジュアルエリア
    キャッチコピーと検索フォームを配置
    --}}

@endsection

@push('styles')
<style>
    .hero {
        padding: 60px 0;
        text-align: center;
        background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%);
    }
    .hero h1 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 16px;
        color: #222;
    }
    .hero p {
        font-size: 16px;
        color: #666;
        margin-bottom: 40px;
    }
    .search-box {
        background: #fff;
        border-radius: 12px;
        padding: 32px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        max-width: 640px;
        margin: 0 auto;
        text-align: left;
    }
    .search-box h2 {
        font-size: 20px;
        margin-bottom: 24px;
        color: #333;
        text-align: center;
        font-weight: 600;
    }
    .btn-block {
        width: 100%;
        margin-top: 20px;
        padding: 14px;
        font-size: 16px;
    }
    .radio-group {
        display: flex;
        gap: 24px;
    }
    .radio-label {
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        font-size: 14px;
    }
    .radio-label input[type="radio"] {
        width: 18px;
        height: 18px;
        accent-color: var(--primary);
    }
    .time-range {
        display: flex;
        align-items: flex-end;
        gap: 12px;
    }
    .time-select {
        flex: 1;
    }
    .time-separator {
        padding-bottom: 10px;
        color: #666;
    }
    .sub-label {
        display: block;
        font-size: 12px;
        color: #666;
        margin-bottom: 4px;
    }
    .price-input-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .price-input-group .form-input {
        flex: 1;
    }
    .input-with-suffix {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .input-with-suffix .form-input {
        flex: 1;
    }
    .suffix {
        white-space: nowrap;
        color: #666;
        font-size: 14px;
    }
    .map-search {
        background: linear-gradient(135deg, #e3f2fd 0%, #e8f5e9 100%);
        border: 2px dashed #66bb6a;
        border-radius: 12px;
        padding: 30px 20px;
        text-align: center;
        margin-top: 24px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .map-search:hover {
        background: linear-gradient(135deg, #bbdefb 0%, #c8e6c9 100%);
        border-color: #43a047;
        transform: translateY(-2px);
    }
    .map-icon {
        font-size: 48px;
        margin-bottom: 12px;
    }
    .map-text {
        font-size: 18px;
        font-weight: 600;
        color: #1b5e20;
        margin-bottom: 8px;
    }
    .map-subtext {
        font-size: 14px;
        color: #558b2f;
    }
    .section {
        padding: 60px 0;
    }
    .section-light {
        background: #fafafa;
    }
    .section-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 30px;
        color: #333;
    }
    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 24px;
    }
    .card-scroll {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        padding-bottom: 16px;
    }
    .card-scroll::-webkit-scrollbar {
        height: 8px;
    }
    .card-scroll::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    .card-scroll::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 4px;
    }
    .card-horizontal {
        min-width: 300px;
        flex-shrink: 0;
    }
    .card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
    }
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }
    .card-image {
        height: 180px;
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #999;
    }
    .card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .placeholder-image {
        color: #999;
        font-size: 14px;
    }
    .card-body {
        padding: 16px;
    }
    .card-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
    }
    .card-text {
        font-size: 14px;
        color: #666;
        margin-bottom: 8px;
    }
    .card-price {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
    }
    .no-data {
        text-align: center;
        color: #666;
        grid-column: 1 / -1;
        padding: 40px;
    }
    .login-prompt {
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border-radius: 12px;
        grid-column: 1 / -1;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }
    .login-prompt p {
        font-size: 16px;
        color: #666;
        margin-bottom: 20px;
    }
    .login-prompt .btn {
        margin: 0 8px;
    }
    @media (max-width: 768px) {
        .hero h1 {
            font-size: 24px;
        }
        .search-box {
            padding: 24px 16px;
        }
        .time-range {
            flex-direction: column;
            gap: 8px;
        }
        .time-separator {
            display: none;
        }
    }
</style>
@endpush
