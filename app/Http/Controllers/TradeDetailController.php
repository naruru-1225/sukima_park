<?php

namespace App\Http\Controllers;

use App\Models\RentalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ============================================================
 * 取引詳細コントローラー (TradeDetailController.php)
 * ============================================================
 * 
 * 【このコントローラーの役割】
 * - 完了した取引の詳細を表示
 * - レンタル記録とレビュー情報を取得
 * 
 * ============================================================
 */
class TradeDetailController extends Controller
{
    /**
     * 取引詳細を表示
     * 
     * @param int $recordId 貸出記録ID
     * @return \Illuminate\View\View
     */
    public function show($recordId)
    {
        // ログインユーザーが借りた記録を取得
        $rental = RentalRecord::with([
            'land.owner',  // 土地と土地のオーナー情報
            'review'       // レビュー情報
        ])
        ->where('RECORD_ID', $recordId)
        ->where('USER_ID', Auth::id())
        ->firstOrFail();

        // レビューデータを整形
        $reviews = collect();
        
        if ($rental->review) {
            $review = $rental->review;
            
            // 土地へのレビュー
            if ($review->LAND_REVIEW && $review->LAND_COMMENT) {
                $reviews->push((object)[
                    'reviewable_type' => 'land',
                    'rating' => $review->LAND_REVIEW,
                    'comment' => $review->LAND_COMMENT,
                    'created_at' => $review->DATE,
                ]);
            }
            
            // ユーザー（貸し手）へのレビュー
            if ($review->USER_REVIEW && $review->USER_COMMENT) {
                $reviews->push((object)[
                    'reviewable_type' => 'user',
                    'rating' => $review->USER_REVIEW,
                    'comment' => $review->USER_COMMENT,
                    'created_at' => $review->DATE,
                ]);
            }
        }

        // レンタル情報に必要なプロパティを追加
        $rental->start_date = $rental->RENTAL_START_DATE;
        $rental->end_date = $rental->RENTAL_END_DATE;
        $rental->total_amount = $this->calculateTotalAmount($rental);
        $rental->status_label = '取引完了';

        return view('trade_detail', compact('rental', 'reviews'));
    }

    /**
     * 合計金額を計算
     * 
     * @param RentalRecord $rental
     * @return int
     */
    private function calculateTotalAmount(RentalRecord $rental): int
    {
        $days = $rental->RENTAL_START_DATE->diffInDays($rental->RENTAL_END_DATE) + 1;
        
        return match($rental->PRICE_UNIT) {
            'day' => $rental->PRICE * $days,
            'month' => $rental->PRICE,
            'year' => $rental->PRICE,
            default => $rental->PRICE * $days,
        };
    }
}
