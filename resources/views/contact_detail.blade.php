{{--
============================================================
問い合わせ詳細画面 (contact_detail.blade.php)
============================================================

【対応画面定義】
  - 管理画面 - 問い合わせ詳細

【このファイルの役割】
  - 管理者向け問い合わせ詳細の表示
  - ステータスの変更
  - 返信の送信

【受け取るデータ】
  - $contact: 問い合わせデータ（userリレーション含む）

【画面構成】
  1. ヘッダー（ナビゲーション）
  2. 問い合わせ詳細ボックス
  3. ステータス変更フォーム
  4. 返信フォーム

============================================================
--}}

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>問い合わせ詳細 - スキマパーク管理</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI",
            "Hiragino Sans", sans-serif;
            line-height: 1.6;
            color: #333;
            background: #fafafa;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px;
        }
        header {
            background: #fff;
            border-bottom: 1px solid #e0e0e0;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .header-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 60px;
        }
        .logo {
            font-size: 18px;
            font-weight: 600;
            color: #2e7d32;
            text-decoration: none;
        }
        .header-nav {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .btn-primary {
            background: #2e7d32;
            color: #fff;
        }
        .btn-primary:hover {
            background: #1b5e20;
        }
        .btn-secondary {
            background: #f5f5f5;
            color: #333;
        }
        .btn-secondary:hover {
            background: #e0e0e0;
        }
        .icon-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #f5f5f5;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
        }
        .icon-btn:hover {
            background: #e0e0e0;
        }
        .section {
            padding: 40px 0;
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .section-title {
            font-size: 22px;
            font-weight: 600;
            color: #222;
        }
        .view-all {
            color: #2e7d32;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
        .view-all:hover {
            text-decoration: underline;
        }
        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
        }
        .form-group {
            margin-bottom: 16px;
            text-align: left;
        }
        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 6px;
            color: #555;
        }
        /* ★ 詳細表示用のスタイル */
        .detail-box {
            background: #fff;
            border-radius: 8px;
            padding: 32px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            max-width: 800px;
            margin: 0 auto 24px auto;
        }
        .detail-item {
            margin-bottom: 16px;
        }
        .detail-item label {
            font-size: 14px;
            font-weight: 500;
            color: #555;
            margin-bottom: 4px;
            display: block;
        }
        .detail-value {
            font-size: 15px;
            color: #222;
            padding: 10px 12px;
            background: #f9f9f9;
            border-radius: 6px;
            white-space: pre-wrap; /* 改行を反映 */
            word-wrap: break-word; /* 長い単語を折り返す */
        }
        .detail-value a {
            color: #2e7d32;
            text-decoration: none;
        }
        .detail-value a:hover {
            text-decoration: underline;
        }
        .status-form {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #e0e0e0;
        }
        .reply-box {
            background: #fff;
            border-radius: 8px;
            padding: 32px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            max-width: 800px;
            margin: 0 auto;
        }
        .reply-box h3 {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .reply-box textarea {
            min-height: 150px;
            resize: vertical;
        }
        .reply-box .btn {
            width: 100%;
            padding-top: 12px;
            padding-bottom: 12px;
            font-size: 16px;
        }
        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
        .alert-error {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-inner">
            <a href="{{ url('/admin/users') }}" class="logo">スキマパーク (管理画面)</a>
            <div class="header-nav">
                <a href="{{ url('/admin/contacts') }}" class="btn btn-secondary">問い合わせ一覧</a>
                <a href="{{ url('/admin/users/create') }}" class="btn btn-primary">新規ユーザー追加</a>
                <button class="icon-btn" title="メッセージ">💬</button>
                <a href="{{ route('logout') }}" class="btn btn-secondary"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    ログアウト
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </header>

    <main>
        <div class="section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">問い合わせ詳細 (ID: {{ $contact->id ?? '---' }})</h2>
                    <a href="{{ url('/admin/contacts') }}" class="view-all">← 問い合わせ一覧に戻る</a>
                </div>

                {{-- 成功メッセージ --}}
                @if(session('success'))
                  <div class="alert alert-success" style="max-width: 800px; margin: 0 auto 20px auto;">
                      {{ session('success') }}
                  </div>
                @endif

                {{-- エラーメッセージ --}}
                @if(session('error'))
                  <div class="alert alert-error" style="max-width: 800px; margin: 0 auto 20px auto;">
                      {{ session('error') }}
                  </div>
                @endif

                <div class="detail-box">
                    <div class="detail-item">
                        <label>日付</label>
                        <div class="detail-value">{{ $contact->created_at?->format('Y-m-d') ?? '---' }}</div>
                    </div>
                    <div class="detail-item">
                        <label>ユーザー名</label>
                        <div class="detail-value">
                            @if($contact->user ?? null)
                              <a href="{{ url('/admin/users/' . $contact->user->id) }}">{{ $contact->user->name ?? $contact->user->NAME ?? '不明' }}</a>
                              ({{ $contact->user->email ?? $contact->user->EMAIL ?? '---' }})
                            @else
                              不明
                            @endif
                        </div>
                    </div>
                    <div class="detail-item">
                        <label>件名</label>
                        <div class="detail-value">{{ $contact->subject ?? '---' }}</div>
                    </div>
                    <div class="detail-item">
                        <label>問い合わせ内容</label>
                        <div class="detail-value">{{ $contact->content ?? '---' }}</div>
                    </div>

                    <form class="status-form" method="POST" action="{{ url('/admin/contacts/' . ($contact->id ?? 0) . '/status') }}">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="status">ステータスを変更</label>
                            <div style="display: flex; gap: 12px">
                                <select id="status" name="status" class="form-control">
                                    <option value="new" {{ ($contact->status ?? '') == 'new' ? 'selected' : '' }}>新規</option>
                                    <option value="open" {{ ($contact->status ?? '') == 'open' ? 'selected' : '' }}>対応中</option>
                                    <option value="closed" {{ ($contact->status ?? '') == 'closed' ? 'selected' : '' }}>完了</option>
                                </select>
                                <button type="submit" class="btn btn-secondary" style="flex-shrink: 0">変更</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="reply-box">
                    <h3>返信する</h3>
                    <form method="POST" action="{{ url('/admin/contacts/' . ($contact->id ?? 0) . '/reply') }}">
                        @csrf
                        <div class="form-group">
                            <label for="reply_body">返信内容</label>
                            <textarea
                                id="reply_body"
                                name="reply_body"
                                class="form-control"
                                placeholder="{{ ($contact->user->name ?? $contact->user->NAME ?? 'お客') }}様

お問い合わせありがとうございます。
スキマパークサポート担当です。"
                            ></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">返信を送信</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
