<?php

namespace App\Http\Controllers;

use App\Models\ReviewComment;
use App\Models\RentalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ============================================================
 * レビューコントローラー (ReviewController.php)
 * ============================================================
 * 
 * 【このコントローラーの役割】
 * - レビュー・コメントの投稿を処理
 * 
 * 【主な機能】
 *   - レビューの投稿
 *   - レビュー情報の保存
 * 
 * 【使用テーブル】
 *   - REVIEW_COMMENT_TABLE（レビュー・コメント）
 *   - RENTAL_RECORD_TABLE（貸出記録）
 * 
 * ============================================================
 */
class ReviewController extends Controller
{
    /**
     * レビューを投稿
     * 
     * @param Request $request
     * @param int $recordId 貸出記録ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $recordId)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // レンタル記録を取得（自分のレンタルのみ）
        $rental = RentalRecord::where('RECORD_ID', $recordId)
            ->where('USER_ID', $user->USER_ID)
            ->with('land')
            ->firstOrFail();

        // バリデーション
        $validated = $request->validate([
            'land_review' => 'required|integer|between:1,5',
            'land_comment' => 'nullable|string|max:512',
            'user_review' => 'required|integer|between:1,5',
            'user_comment' => 'nullable|string|max:512',
        ], [
            'land_review.required' => '土地の評価は必須です',
            'land_review.between' => '土地の評価は1から5の間で選択してください',
            'user_review.required' => '貸し手の評価は必須です',
            'user_review.between' => '貸し手の評価は1から5の間で選択してください',
            'land_comment.max' => 'コメントは512文字以内です',
            'user_comment.max' => 'コメントは512文字以内です',
        ]);

        // 既存のレビューをチェック
        $existingReview = ReviewComment::where('RECORD_ID', $recordId)->first();

        if ($existingReview) {
            // 既存のレビューを更新
            $existingReview->update([
                'LAND_REVIEW' => $validated['land_review'],
                'LAND_COMMENT' => $validated['land_comment'] ?? null,
                'USER_REVIEW' => $validated['user_review'],
                'USER_COMMENT' => $validated['user_comment'] ?? null,
                'DATE' => now()->toDateString(),
            ]);
        } else {
            // 新しいレビューを作成
            ReviewComment::create([
                'LAND_REVIEW' => $validated['land_review'],
                'LAND_COMMENT' => $validated['land_comment'] ?? null,
                'USER_REVIEW' => $validated['user_review'],
                'USER_COMMENT' => $validated['user_comment'] ?? null,
                'DATE' => now()->toDateString(),
                'USER_ID' => $user->USER_ID,
                'LAND_ID' => $rental->LAND_ID,
                'RECORD_ID' => $recordId,
            ]);
        }

        return redirect()
            ->route('trade.detail', $recordId)
            ->with('success', 'レビューを投稿しました');
    }
}
