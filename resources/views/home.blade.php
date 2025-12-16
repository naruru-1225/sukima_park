@extends('layouts.app')

@section('title', 'スキマパーク - あなたに合った土地を見つけよう')

@section('content')
    {{-- ヒーローセクション --}}
    <div class="hero">
        <div class="container">
            <h1>あなたに合った土地を見つけよう</h1>
            <p>使いたい人と貸したい人を繋ぐプラットフォーム</p>

            {{-- 検索フォーム --}}
            <div class="search-box">
                <h2>検索する</h2>
                <form action="{{ url('/lands') }}" method="GET">
                    {{-- フリーワード検索 --}}
                    <div class="form-group">
                        <label class="form-label">フリーワード検索</label>
                        <input type="text" name="keyword" class="form-input" placeholder="例: 畑, 駐車場, イベントスペース">
                    </div>

                    {{-- 都道府県 --}}
                    <div class="form-group">
                        <label class="form-label">都道府県</label>
                        <select name="prefecture" class="form-select">
                            <option value="">すべての都道府県</option>
                            <option value="1">北海道</option>
                            <option value="2">青森県</option>
                            <option value="3">岩手県</option>
                            <option value="4">宮城県</option>
                            <option value="5">秋田県</option>
                            <option value="6">山形県</option>
                            <option value="7">福島県</option>
                            <option value="8">茨城県</option>
                            <option value="9">栃木県</option>
                            <option value="10">群馬県</option>
                            <option value="11">埼玉県</option>
                            <option value="12">千葉県</option>
                            <option value="13">東京都</option>
                            <option value="14">神奈川県</option>
                            <option value="15">新潟県</option>
                            <option value="16">富山県</option>
                            <option value="17">石川県</option>
                            <option value="18">福井県</option>
                            <option value="19">山梨県</option>
                            <option value="20">長野県</option>
                            <option value="21">岐阜県</option>
                            <option value="22">静岡県</option>
                            <option value="23">愛知県</option>
                            <option value="24">三重県</option>
                            <option value="25">滋賀県</option>
                            <option value="26">京都府</option>
                            <option value="27">大阪府</option>
                            <option value="28">兵庫県</option>
                            <option value="29">奈良県</option>
                            <option value="30">和歌山県</option>
                            <option value="31">鳥取県</option>
                            <option value="32">島根県</option>
                            <option value="33">岡山県</option>
                            <option value="34">広島県</option>
                            <option value="35">山口県</option>
                            <option value="36">徳島県</option>
                            <option value="37">香川県</option>
                            <option value="38">愛媛県</option>
                            <option value="39">高知県</option>
                            <option value="40">福岡県</option>
                            <option value="41">佐賀県</option>
                            <option value="42">長崎県</option>
                            <option value="43">熊本県</option>
                            <option value="44">大分県</option>
                            <option value="45">宮崎県</option>
                            <option value="46">鹿児島県</option>
                            <option value="47">沖縄県</option>
                        </select>
                    </div>

                    {{-- 市区町村 --}}
                    <div class="form-group">
                        <label class="form-label">市区町村</label>
                        <input type="text" name="city" class="form-input" placeholder="例: 渋谷区, 札幌市">
                    </div>

                    {{-- 料金上限 --}}
                    <div class="form-group">
                        <label class="form-label">料金（上限）</label>
                        <div class="input-with-suffix">
                            <input type="number" name="price_max" class="form-input" placeholder="例: 50000" min="0" step="1000">
                            <span class="suffix">円まで</span>
                        </div>
                    </div>

                    {{-- 面積 --}}
                    <div class="form-group">
                        <label class="form-label">面積</label>
                        <div class="input-with-suffix">
                            <input type="number" name="area_min" class="form-input" placeholder="例: 100" min="0">
                            <span class="suffix">㎡以上</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">検索する</button>
                </form>

                {{-- 地図検索 --}}
                <div class="map-search">
                    <div class="map-icon">🗺️</div>
                    <div class="map-text">地図から検索する</div>
                    <div class="map-subtext">マップ上で直接エリアを選んで土地を探せます</div>
                </div>
            </div>
        </div>
    </div>

    {{-- おすすめリスティング --}}
    <div class="section">
        <div class="container">
            <h2 class="section-title">おすすめのリスティング</h2>
            <div class="card-grid">
                @forelse($recommendedLands as $land)
                    <div class="card">
                        <div class="card-image">
                            @if($land->LAND_IMAGE)
                                <img src="{{ asset('storage/' . $land->LAND_IMAGE) }}" alt="{{ $land->TITLE }}">
                            @else
                                <div class="placeholder-image">土地の写真</div>
                            @endif
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">{{ $land->TITLE ?? '土地タイトル' }}</h3>
                            <p class="card-text">{{ $land->CITY }}</p>
                            <div class="card-price">
                                @if($land->PRICE)
                                    {{ number_format($land->PRICE) }}円
                                @else
                                    要相談
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="no-data">現在、登録されている土地はありません。</p>
                @endforelse
            </div>
        </div>
    </div>
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
        border-radius: 8px;
        padding: 32px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        max-width: 600px;
        margin: 0 auto;
        text-align: left;
    }
    .search-box h2 {
        font-size: 18px;
        margin-bottom: 20px;
        color: #333;
        text-align: center;
    }
    .btn-block {
        width: 100%;
        margin-top: 16px;
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
    }
    .map-search {
        background: #e8f5e9;
        border: 2px dashed #66bb6a;
        border-radius: 8px;
        padding: 30px 20px;
        text-align: center;
        margin-top: 20px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .map-search:hover {
        background: #c8e6c9;
        border-color: #43a047;
    }
    .map-icon {
        font-size: 40px;
        margin-bottom: 12px;
    }
    .map-text {
        font-size: 16px;
        font-weight: 600;
        color: #1b5e20;
        margin-bottom: 6px;
    }
    .map-subtext {
        font-size: 14px;
        color: #558b2f;
    }
    .section {
        padding: 60px 0;
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
    .card-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 8px;
    }
    .card-text {
        font-size: 14px;
        color: #666;
        margin-bottom: 8px;
    }
    .card-price {
        font-size: 16px;
        font-weight: 600;
        color: var(--primary);
    }
    .no-data {
        text-align: center;
        color: #666;
        grid-column: 1 / -1;
    }
    @media (max-width: 768px) {
        .hero h1 {
            font-size: 24px;
        }
        .search-box {
            padding: 24px;
        }
    }
</style>
@endpush
