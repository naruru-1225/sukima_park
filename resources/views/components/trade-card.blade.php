{{--
============================================================
取引完了カード コンポーネント (trade-card.blade.php)
============================================================

【このコンポーネントの役割】
  - 1つの取引完了記録を表示するカード

【受け取るプロパティ】
  - $trade: 取引記録データ
    - title: タイトル
    - seller: 貸し手名
    - start_date: 利用開始日
    - end_date: 利用終了日
    - location: 所在地
    - price: 支払い金額
    - review_status: 'completed' または 'pending'
    - image: 画像URL（オプション）

============================================================
--}}

<div class="trade-card">
    <div class="trade-card-image">
        @if($trade['image'] ?? false)
            <img src="{{ asset('storage/' . $trade['image']) }}" alt="{{ $trade['title'] }}">
        @else
            <span>📍 画像なし</span>
        @endif
    </div>
    <div class="trade-card-body">
        <h3 class="trade-card-title">{{ $trade['title'] }}</h3>
        <div class="trade-card-info">
            <div class="info-row">
                <span class="info-label">貸し手:</span>
                <span class="info-value">{{ $trade['seller'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">利用期間:</span>
                <span class="info-value">{{ $trade['start_date'] }} 〜 {{ $trade['end_date'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">所在地:</span>
                <span class="info-value">{{ $trade['location'] }}</span>
            </div>
        </div>
        
        @if($trade['review_status'] === 'completed')
            <div class="review-badge completed">✓ レビュー投稿済み</div>
        @else
            <div class="review-badge pending">! 未レビュー</div>
        @endif

        <div class="trade-price">支払い金額: ¥{{ number_format($trade['price']) }}</div>
        <div class="trade-card-footer">
            @if($trade['review_status'] === 'pending')
                <a href="{{ route('reviews.create') }}" class="btn btn-outline">レビューを書く</a>
            @endif
            <a href="#" class="btn btn-primary">詳細を見る</a>
        </div>
    </div>
</div>
