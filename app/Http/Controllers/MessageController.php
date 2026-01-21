<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * ============================================================
 * メッセージコントローラー (MessageController.php)
 * ============================================================
 * 
 * DM（ダイレクトメッセージ）機能を担当するコントローラー
 * 
 * 【対応画面】
 *   - message_list_screen.blade.php（メッセージ一覧画面）
 *   - message_detail_screen.blade.php（メッセージ詳細画面）
 * 
 * 【主な機能】
 *   - 会話一覧の表示
 *   - 会話詳細の表示
 *   - メッセージの送信
 * 
 * 【使用テーブル】
 *   - CHAT_TABLE（チャットテーブル）
 *   - MEMBER_TABLE（会員テーブル）
 * 
 * ============================================================
 */
class MessageController extends Controller
{
    /**
     * メッセージ一覧を表示
     * 
     * 【処理内容】
     * 1. ログインユーザーが関与する会話を取得
     * 2. 相手ユーザーごとにグループ化
     * 3. 各会話の最新メッセージと未読数を取得
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        $userId = $user->USER_ID;

        // ログインユーザーが関与する全ての会話相手を取得
        // サブクエリで各相手との最新メッセージを取得
        $conversations = DB::table('CHAT_TABLE')
            ->select(DB::raw('
                CASE 
                    WHEN USER_ID_FROM = ? THEN USER_ID_TO 
                    ELSE USER_ID_FROM 
                END as partner_id
            '))
            ->addBinding($userId, 'select')
            ->where('USER_ID_FROM', $userId)
            ->orWhere('USER_ID_TO', $userId)
            ->groupBy('partner_id')
            ->pluck('partner_id');

        // 各会話の詳細情報を構築
        $messages = collect();

        foreach ($conversations as $partnerId) {
            // 相手ユーザー情報を取得
            $partner = Member::find($partnerId);
            if (!$partner) continue;

            // この相手との最新メッセージを取得
            $latestMessage = Chat::where(function ($query) use ($userId, $partnerId) {
                    $query->where('USER_ID_FROM', $userId)->where('USER_ID_TO', $partnerId);
                })
                ->orWhere(function ($query) use ($userId, $partnerId) {
                    $query->where('USER_ID_FROM', $partnerId)->where('USER_ID_TO', $userId);
                })
                ->orderByDesc('DATE')
                ->orderByDesc('TIME')
                ->first();

            if (!$latestMessage) continue;

            // 未読メッセージ数を計算（相手から自分へのメッセージ）
            // ※現在のDBには既読フラグがないため、とりあえず0とする
            $unreadCount = 0;

            // 相対時間を計算
            $messageDateTime = \Carbon\Carbon::parse($latestMessage->DATE->format('Y-m-d') . ' ' . $latestMessage->TIME);
            $timeAgo = $this->getTimeAgo($messageDateTime);

            $messages->push((object)[
                'id' => $partnerId,
                'sender_name' => $partner->USERNAME,
                'icon_image' => $partner->ICON_IMAGE,
                'preview' => mb_strlen($latestMessage->MESSAGE) > 30 
                    ? mb_substr($latestMessage->MESSAGE, 0, 30) . '...' 
                    : $latestMessage->MESSAGE,
                'time_ago' => $timeAgo,
                'unread' => $unreadCount > 0,
                'unread_count' => $unreadCount,
                'last_message_date' => $messageDateTime,
            ]);
        }

        // 最新メッセージ順にソート
        $messages = $messages->sortByDesc('last_message_date')->values();

        return view('message_list_screen', compact('messages'));
    }

    /**
     * 相対時間を取得
     * 
     * @param \Carbon\Carbon $dateTime
     * @return string
     */
    private function getTimeAgo($dateTime)
    {
        $now = now();
        $diff = $now->diffInMinutes($dateTime);

        if ($diff < 1) {
            return 'たった今';
        } elseif ($diff < 60) {
            return $diff . '分前';
        } elseif ($diff < 1440) {
            return floor($diff / 60) . '時間前';
        } elseif ($diff < 2880) {
            return '昨日';
        } else {
            return floor($diff / 1440) . '日前';
        }
    }

    /**
     * 新規メッセージ作成画面を表示
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // メッセージ可能なユーザー一覧を取得（自分以外）
        $users = Member::where('USER_ID', '!=', $user->USER_ID)
            ->where('ACCOUNT_STATUS', 0) // 通常ユーザーのみ
            ->get();

        return view('message_create_screen', compact('users'));
    }

    /**
     * 会話詳細を表示
     * 
     * @param int $partnerId 会話相手のユーザーID
     * @return \Illuminate\Contracts\View\View
     */
    public function show($partnerId)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        $userId = $user->USER_ID;

        // 相手ユーザー情報を取得
        $recipient = Member::find($partnerId);
        
        if (!$recipient) {
            abort(404, 'ユーザーが見つかりません');
        }

        // この相手とのメッセージを全て取得（時系列順）
        $messages = Chat::where(function ($query) use ($userId, $partnerId) {
                $query->where('USER_ID_FROM', $userId)->where('USER_ID_TO', $partnerId);
            })
            ->orWhere(function ($query) use ($userId, $partnerId) {
                $query->where('USER_ID_FROM', $partnerId)->where('USER_ID_TO', $userId);
            })
            ->orderBy('DATE')
            ->orderBy('TIME')
            ->get()
            ->map(function ($chat) use ($userId) {
                return (object)[
                    'id' => $chat->CHAT_ID,
                    'content' => $chat->MESSAGE,
                    'created_at' => \Carbon\Carbon::parse($chat->DATE->format('Y-m-d') . ' ' . $chat->TIME),
                    'is_sent' => $chat->USER_ID_FROM == $userId,
                    'image' => $chat->IMAGE,
                ];
            });

        // 受信者オブジェクトをビュー用に整形
        $recipient = (object)[
            'id' => $recipient->USER_ID,
            'name' => $recipient->USERNAME,
            'icon_image' => $recipient->ICON_IMAGE,
        ];

        return view('message_detail_screen', compact('messages', 'recipient'));
    }

    /**
     * メッセージを送信
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'recipient_id' => 'required|integer|exists:MEMBER_TABLE,USER_ID',
            'content' => 'required|string|max:512',
        ]);

        $now = now();

        Chat::create([
            'USER_ID_FROM' => $user->USER_ID,
            'USER_ID_TO' => $validated['recipient_id'],
            'MESSAGE' => $validated['content'],
            'IMAGE' => null,
            'YEAR' => $now->format('Y-m-d'),
            'DATE' => $now->format('Y-m-d'),
            'TIME' => $now->format('H:i:s'),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * 新着メッセージを取得（ポーリング用API）
     * 
     * @param Request $request
     * @param int $partnerId 会話相手のユーザーID
     * @return \Illuminate\Http\JsonResponse
     */
    public function poll(Request $request, $partnerId)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $userId = $user->USER_ID;
        $lastId = $request->query('last_id', 0);

        // 指定ID以降の新着メッセージを取得
        $newMessages = Chat::where('CHAT_ID', '>', $lastId)
            ->where(function ($query) use ($userId, $partnerId) {
                $query->where(function ($q) use ($userId, $partnerId) {
                    $q->where('USER_ID_FROM', $userId)->where('USER_ID_TO', $partnerId);
                })->orWhere(function ($q) use ($userId, $partnerId) {
                    $q->where('USER_ID_FROM', $partnerId)->where('USER_ID_TO', $userId);
                });
            })
            ->orderBy('DATE')
            ->orderBy('TIME')
            ->get()
            ->map(function ($chat) use ($userId) {
                return [
                    'id' => $chat->CHAT_ID,
                    'content' => $chat->MESSAGE,
                    'created_at' => \Carbon\Carbon::parse($chat->DATE->format('Y-m-d') . ' ' . $chat->TIME)->format('Y-m-d H:i:s'),
                    'time' => \Carbon\Carbon::parse($chat->TIME)->format('H:i'),
                    'is_sent' => $chat->USER_ID_FROM == $userId,
                ];
            });

        return response()->json([
            'messages' => $newMessages,
            'last_id' => $newMessages->isNotEmpty() ? $newMessages->last()['id'] : $lastId,
        ]);
    }
}
