{{--
============================================================
レンタル詳細画面 (rental_detail.blade.php)
============================================================

【対応画面定義】
  - rental_detail.csv（レンタル詳細）

【このファイルの役割】
  - レンタル記録の詳細情報を表示
  - レビュー情報の表示

【受け取るデータ】
  - $rental: レンタル記録の詳細
    → RentalController@show から渡される
    → RentalRecord モデル（landリレーション、reviewリレーション含む）

【表示内容】
  1. 取引情報（土地情報、利用期間、支払金額など）
  2. レビューセクション

============================================================
--}}

@extends('layouts.app')

@section('title', 'レンタル詳細 - スキマパーク')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/rental-detail.css') }}">
@endpush

@section('content')
{{-- パンくずリスト --}}
<div class="breadcrumb">
    <div class="container">
        <nav class="breadcrumb-list">
            <a href="{{ route('home') }}" class="breadcrumb-item">トップ</a>
            <span class="breadcrumb-separator">›</span>
            <a href="{{ route('rental_list') }}" class="breadcrumb-item">レンタル中一覧</a>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-current">{{ $rental->land->name }}</span>
        </nav>
    </div>
</div>

{{-- メインコンテンツ --}}
<div class="section">
    <div class="container-wide">
        <a href="{{ route('rental_list') }}" class="back-link">
            ← 一覧に戻る
        </a>

        <div class="status-badge">レンタル中</div>

        <h1 class="page-title">{{ $rental->land->name }}</h1>

        <div class="detail-layout">
            {{-- 左側：詳細情報 --}}
            <div class="detail-main">
                {{-- 写真ギャラリー --}}
                <div class="gallery">
                    <div class="gallery-main">
                        @if($rental->land->main_image)
                            <img src="{{ Storage::url($rental->land->main_image) }}" alt="{{ $rental->land->name }}">
                        @else
                            メイン写真
                        @endif
                    </div>
                    {{-- サムネイルは将来的に複数画像対応時に実装 --}}
                    {{-- <div class="gallery-thumbs">
                        <div class="gallery-thumb">写真1</div>
                        <div class="gallery-thumb">写真2</div>
                        <div class="gallery-thumb">写真3</div>
                        <div class="gallery-thumb">写真4</div>
                    </div> --}}
                </div>

                {{-- 土地情報 --}}
                <div class="info-card">
                    <h2 class="info-card-title">土地情報</h2>
                    <div class="info-row">
                        <span class="info-label">所在地</span>
                        <span class="info-value">{{ $rental->land->full_address }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">面積</span>
                        <span class="info-value">{{ $rental->land->AREA }}㎡</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">利用可能時間</span>
                        <span class="info-value">
                            {{ $rental->land->RENTAL_START_TIME }} 〜 {{ $rental->land->RENTAL_END_TIME }}
                        </span>
                    </div>

                    {{-- 地図（将来的にGoogle Maps API連携） --}}
                    <div class="map-container">[Google Map埋め込みエリア]</div>
                </div>

                {{-- 詳細説明 --}}
                @if($rental->land->DESCRIPTION)
                    <div class="info-card">
                        <h2 class="info-card-title">詳細説明</h2>
                        <div class="description-text">{{ $rental->land->DESCRIPTION }}</div>
                    </div>
                @endif
            </div>

            {{-- 右側：サイドバー --}}
            <div class="detail-sidebar">
                {{-- 支払い情報 --}}
                <div class="payment-summary">
                    <div class="payment-row">
                        <span class="payment-label">利用開始</span>
                        <span class="payment-value">{{ $rental->RENTAL_START_DATE->format('Y/m/d') }}</span>
                    </div>
                    <div class="payment-row">
                        <span class="payment-label">利用終了</span>
                        <span class="payment-value">{{ $rental->RENTAL_END_DATE->format('Y/m/d') }}</span>
                    </div>
                    <div class="payment-row">
                        <span class="payment-label">単価</span>
                        <span class="payment-value">
                            ¥{{ number_format($rental->PRICE) }} / 
                            @switch($rental->PRICE_UNIT)
                                @case('day') 日 @break
                                @case('month') 月 @break
                                @case('year') 年 @break
                                @default 日
                            @endswitch
                        </span>
                    </div>
                    <div class="payment-row">
                        <span class="payment-label">合計金額</span>
                        <span class="payment-value">¥{{ number_format($totalAmount) }}</span>
                    </div>
                </div>

                {{-- 貸し手情報 --}}
                <div class="owner-card">
                    <h3 style="font-size: 14px; font-weight: 600; color: #666; margin-bottom: 12px;">
                        貸し手情報
                    </h3>
                    <div class="owner-header">
                        <div class="owner-avatar">
                            @if($rental->land->owner->ICON_IMAGE)
                            
                                <img src="{{ Storage::url($rental->land->owner->ICON_IMAGE) }}" alt="{{ $rental->land->owner->name }}">
                            @else
                                👤
                            @endif
                        </div>
                        <div class="owner-info">
                            <div class="owner-name">{{ $rental->land->owner->name }}</div>
                            <div class="owner-label">オーナー</div>
                        </div>
                    </div>
                    <div class="action-buttons">
                        <a href="{{ route('messages.show', $rental->land->owner->USER_ID) }}" class="btn btn-primary btn-large">
                            💬 貸し手に連絡する
                        </a>
                    </div>
                </div>

                {{-- その他のアクション --}}
                <div class="info-card">
                    <h3 style="font-size: 14px; font-weight: 600; color: #666; margin-bottom: 12px;">
                        サポート
                    </h3>
                    <div class="action-buttons">
                        <a href="{{ route('contact') }}" class="btn btn-outline btn-large">
                            ⚠️ 運営に問い合わせる
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
