{{--
============================================================
レンタルカード コンポーネント (rental-card.blade.php)
============================================================

【このコンポーネントの役割】
  - 1つのレンタル記録を表示するカード

【受け取るプロパティ】
  - $rental: RentalRecord モデルのインスタンス

============================================================
--}}

<div class="rental-card">
    <div class="rental-card-image">
        @if($rental->land && $rental->land->image)
            <img src="{{ asset('storage/' . $rental->land->image) }}" alt="{{ $rental->land->LAND_NAME ?? '土地' }}">
        @else
            📍 画像なし
        @endif
    </div>
    <div class="rental-card-body">
        <h3 class="rental-card-title">
            {{ $rental->land->LAND_NAME ?? '未指定の土地' }}
        </h3>
        <div class="rental-card-info">
            <div class="info-row">
                <span class="info-label">レンタル期間:</span>
                <span class="info-value rental-period">
                    {{ $rental->RENTAL_START_DATE->format('Y年m月d日') }}
                    ～
                    {{ $rental->RENTAL_END_DATE->format('Y年m月d日') }}
                </span>
            </div>
            @if($rental->land && $rental->land->ADDRESS)
            <div class="info-row">
                <span class="info-label">住所:</span>
                <span class="info-value">{{ $rental->land->ADDRESS }}</span>
            </div>
            @endif
        </div>
        <div class="rental-price">
            ¥{{ number_format($rental->PRICE) }}/{{ $rental->PRICE_UNIT ?? '月' }}
        </div>
        <div class="rental-card-footer">
            <a href="{{ route('rentals.show', $rental->RECORD_ID) }}" class="btn btn-primary">詳細を見る</a>
        </div>
    </div>
</div>
