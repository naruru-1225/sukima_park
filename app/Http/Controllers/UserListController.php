<?php
/**
 * ============================================================
 * ユーザー一覧コントローラー (UserListController.php)
 * ============================================================
 * 
 * 管理者向けユーザー一覧画面を担当するコントローラー
 * 
 * 【対応画面】
 *   - user_list.blade.php（ユーザー一覧画面）
 * 
 * 【主な機能】
 *   - ユーザー一覧の表示
 *   - キーワード検索（名前・メール）
 *   - ステータスによる絞り込み
 * 
 * 【使用テーブル】
 *   - MEMBER_TABLE（会員テーブル）
 * 
 * ============================================================
 */

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class UserListController extends Controller
{
  /**
   * ユーザー一覧を表示（管理者向け）
   * 
   * 【処理内容】
   * 1. 検索キーワード（名前・メール）でフィルタリング
   * 2. ステータスでフィルタリング
   * 3. ページネーション付きで一覧を取得
   * 4. user_list.blade.phpにデータを渡して表示
   * 
   * 【取得データ】
   * $users: ユーザーのページネーションコレクション
   *   - MEMBER_TABLEから取得
   *   - キーワード検索：NAME または EMAIL に部分一致
   *   - ステータス検索：STATUS カラムでフィルタ
   * 
   * @param Request $request
   * @return \Illuminate\Contracts\View\View
   */
  public function index(Request $request)
  {
    // クエリビルダーを初期化
    $query = Member::query();

    // キーワード検索（名前 or メール）
    if ($keyword = $request->input('keyword')) {
      $query->where(function ($q) use ($keyword) {
        $q->where('NAME', 'like', "%{$keyword}%")
          ->orWhere('EMAIL', 'like', "%{$keyword}%");
      });
    }

    // ステータスフィルタ
    if ($status = $request->input('status')) {
      if ($status === 'active') {
        $query->where('STATUS', 1); // 有効
      } elseif ($status === 'suspended') {
        $query->where('STATUS', 0); // 利用停止中
      }
    }

    // 登録日の降順でソート、ページネーション付きで取得
    $users = $query->orderByDesc('created_at')->paginate(20);

    // user_list.blade.phpを表示し、データを渡す
    return view('user_list', compact('users'));
  }
}
