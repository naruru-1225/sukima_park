<?php
/**
 * ============================================================
 * 土地コントローラー (LandController.php)
 * ============================================================
 * 
 * 土地の検索・一覧表示・詳細表示を行う
 * 
 * 【機能】
 * - index(): 検索結果一覧表示
 * - show(): 土地詳細表示
 */

namespace App\Http\Controllers;

use App\Models\Land;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandController extends Controller
{
    /**
     * 都道府県名のマッピング
     */
    private const PREFECTURES = [
        1 => '北海道',
        2 => '青森県',
        3 => '岩手県',
        4 => '宮城県',
        5 => '秋田県',
        6 => '山形県',
        7 => '福島県',
        8 => '茨城県',
        9 => '栃木県',
        10 => '群馬県',
        11 => '埼玉県',
        12 => '千葉県',
        13 => '東京都',
        14 => '神奈川県',
        15 => '新潟県',
        16 => '富山県',
        17 => '石川県',
        18 => '福井県',
        19 => '山梨県',
        20 => '長野県',
        21 => '岐阜県',
        22 => '静岡県',
        23 => '愛知県',
        24 => '三重県',
        25 => '滋賀県',
        26 => '京都府',
        27 => '大阪府',
        28 => '兵庫県',
        29 => '奈良県',
        30 => '和歌山県',
        31 => '鳥取県',
        32 => '島根県',
        33 => '岡山県',
        34 => '広島県',
        35 => '山口県',
        36 => '徳島県',
        37 => '香川県',
        38 => '愛媛県',
        39 => '高知県',
        40 => '福岡県',
        41 => '佐賀県',
        42 => '長崎県',
        43 => '熊本県',
        44 => '大分県',
        45 => '宮崎県',
        46 => '鹿児島県',
        47 => '沖縄県',
    ];

    /**
     * 1ページあたりの表示件数
     */
    private const PER_PAGE = 20;

    /**
     * 検索結果一覧表示
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // クエリビルダー開始
        $query = Land::query();

        // NOTE: STATUSカラムでのフィルタは、カラム追加後に有効化してください
        // $query->where('STATUS', true);

        // 検索条件の取得
        $keyword = $request->input('keyword');
        $fuzzy = $request->boolean('fuzzy'); // あいまい検索（チェックボックス: ON=OR検索、OFF=AND検索）
        $useDate = $request->input('use_date');
        $prefecture = $request->input('prefecture');
        $city = $request->input('city');
        $timeStart = $request->input('time_start');
        $timeEnd = $request->input('time_end');
        $priceMax = $request->input('price_max');
        $areaMin = $request->input('area_min');
        $sort = $request->input('sort', 'recommend');

        // OR検索の場合（あいまい検索ON）
        if ($fuzzy) {
            $query->where(function ($q) use ($keyword, $prefecture, $city, $timeStart, $timeEnd, $priceMax, $areaMin) {
                if (!empty($keyword)) {
                    $q->orWhere('NAME', 'LIKE', "%{$keyword}%")
                        ->orWhere('DESCRIPTION', 'LIKE', "%{$keyword}%");
                }
                if (!empty($prefecture)) {
                    $q->orWhere('PEREFECTURES', $prefecture);
                }
                if (!empty($city)) {
                    $q->orWhere('CITY', 'LIKE', "%{$city}%");
                }
                if (!empty($timeStart)) {
                    $q->orWhere('RENTAL_START_TIME', '<=', $timeStart);
                }
                if (!empty($timeEnd)) {
                    $q->orWhere('RENTAL_END_TIME', '>=', $timeEnd);
                }
                if (!empty($priceMax)) {
                    $q->orWhere('PRICE', '<=', $priceMax);
                }
                if (!empty($areaMin)) {
                    $q->orWhere('AREA', '>=', $areaMin);
                }
            });
        } else {
            // AND検索（デフォルト）
            if (!empty($keyword)) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('NAME', 'LIKE', "%{$keyword}%")
                        ->orWhere('DESCRIPTION', 'LIKE', "%{$keyword}%");
                });
            }
            if (!empty($prefecture)) {
                $query->where('PEREFECTURES', $prefecture);
            }
            if (!empty($city)) {
                $query->where('CITY', 'LIKE', "%{$city}%");
            }
            if (!empty($timeStart)) {
                $query->where('RENTAL_START_TIME', '<=', $timeStart);
            }
            if (!empty($timeEnd)) {
                $query->where('RENTAL_END_TIME', '>=', $timeEnd);
            }
            if (!empty($priceMax)) {
                $query->where('PRICE', '<=', $priceMax);
            }
            if (!empty($areaMin)) {
                $query->where('AREA', '>=', $areaMin);
            }
        }

        // ソート処理
        $query = $this->applySorting($query, $sort);

        // ページネーション
        $lands = $query->paginate(self::PER_PAGE);

        return view('search_list', [
            'lands' => $lands,
            'filters' => $request->all(),
        ]);
    }

    /**
     * 土地詳細表示
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show(int $id)
    {
        $land = Land::with(['owner', 'reviews'])->findOrFail($id);

        // 平均評価を計算
        $avgRating = $land->reviews->avg('LAND_REVIEW') ?? 0;

        return view('land_detail', [
            'land' => $land,
            'avgRating' => round($avgRating, 1),
        ]);
    }

    /**
     * ソート処理を適用
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $sort
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applySorting($query, string $sort)
    {
        switch ($sort) {
            case 'rating_desc':
                // 評価が高い順（レビューテーブルをJOINして平均を計算）
                $query->leftJoin('REVIEW_COMMENT_TABLE', 'LAND_TABLE.LAND_ID', '=', 'REVIEW_COMMENT_TABLE.LAND_ID')
                    ->select('LAND_TABLE.*', DB::raw('AVG(REVIEW_COMMENT_TABLE.LAND_REVIEW) as avg_rating'))
                    ->groupBy('LAND_TABLE.LAND_ID')
                    ->orderByDesc('avg_rating');
                break;

            case 'rating_asc':
                // 評価が低い順
                $query->leftJoin('REVIEW_COMMENT_TABLE', 'LAND_TABLE.LAND_ID', '=', 'REVIEW_COMMENT_TABLE.LAND_ID')
                    ->select('LAND_TABLE.*', DB::raw('AVG(REVIEW_COMMENT_TABLE.LAND_REVIEW) as avg_rating'))
                    ->groupBy('LAND_TABLE.LAND_ID')
                    ->orderBy('avg_rating');
                break;

            case 'price_asc':
                // 料金が安い順
                $query->orderBy('PRICE', 'asc');
                break;

            case 'price_desc':
                // 料金が高い順
                $query->orderBy('PRICE', 'desc');
                break;

            case 'area_desc':
                // 面積が広い順
                $query->orderBy('AREA', 'desc');
                break;

            case 'area_asc':
                // 面積が狭い順
                $query->orderBy('AREA', 'asc');
                break;

            case 'usage_count':
                // 利用回数順
                $query->leftJoin('RENTAL_RECORD_TABLE', 'LAND_TABLE.LAND_ID', '=', 'RENTAL_RECORD_TABLE.LAND_ID')
                    ->select('LAND_TABLE.*', DB::raw('COUNT(RENTAL_RECORD_TABLE.RECORD_ID) as rental_count'))
                    ->groupBy('LAND_TABLE.LAND_ID')
                    ->orderByDesc('rental_count');
                break;

            case 'recommend':
            default:
                // おすすめ順（新着順 + 評価を考慮）
                $query->orderByDesc('LAND_ID');
                break;
        }

        return $query;
    }

    /**
     * 都道府県名を取得
     */
    public static function getPrefectureName(int $id): string
    {
        return self::PREFECTURES[$id] ?? '';
    }
}
