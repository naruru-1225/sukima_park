{{--
============================================================
問い合わせ一覧画面 (contact_list.blade.php)
============================================================

【対応画面定義】
  - 管理画面 - 問い合わせ一覧

【このファイルの役割】
  - 管理者向け問い合わせ一覧の表示
  - 問い合わせの検索・フィルタリング

【受け取るデータ】
  - $contacts: 問い合わせデータのコレクション

【画面構成】
  1. ヘッダー（ナビゲーション）
  2. フィルターボックス
  3. 問い合わせ一覧テーブル

============================================================
--}}

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>問い合わせ一覧 - スキマパーク管理</title>
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
        .filter-box {
            background: #fff;
            border-radius: 8px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 32px;
        }
        .filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 16px;
            align-items: flex-end;
        }
        .filter-group {
            margin-bottom: 0;
            text-align: left;
        }
        .filter-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 6px;
            color: #555;
        }
        .filter-btn {
            width: 100%;
            padding: 10px 12px;
            background: #2e7d32;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }
        .filter-btn:hover {
            background: #1b5e20;
        }

        /* ★ 問い合わせ一覧用のテーブルスタイル */
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th,
        .data-table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
        }
        .data-table th {
            background: #f9f9f9;
            font-weight: 600;
            color: #555;
        }
        .data-table tr:last-child td {
            border-bottom: none;
        }
        .data-table tr:hover {
            background: #f5f5f5;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            white-space: nowrap;
        }
        .status-new {
            background: #fff8e1;
            color: #f57f17;
        }
        .status-open {
            background: #e3f2fd;
            color: #1565c0;
        }
        .status-closed {
            background: #f1f8e9;
            color: #33691e;
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
                    <h2 class="section-title">問い合わせ一覧</h2>
                    <a href="{{ url('/admin/users') }}" class="view-all">← ユーザー一覧に戻る</a>
                </div>

                <div class="filter-box">
                    <form class="filter-form" method="GET" action="{{ url('/admin/contacts') }}">
                        <div class="filter-group" style="grid-column: 1 / span 2">
                            <label for="keyword">件名 or 内容</label>
                            <input
                                type="text"
                                id="keyword"
                                name="keyword"
                                class="form-control"
                                placeholder="例: 料金, 登録方法"
                                value="{{ request('keyword') }}"
                            />
                        </div>
                        <div class="filter-group">
                            <label for="user_email">ユーザーEメール</label>
                            <input
                                type="text"
                                id="user_email"
                                name="user_email"
                                class="form-control"
                                value="{{ request('user_email') }}"
                            />
                        </div>
                        <div class="filter-group">
                            <label for="status">ステータス</label>
                            <select id="status" name="status" class="form-control">
                                <option value="">すべて</option>
                                <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>新規</option>
                                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>対応中</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>完了</option>
                            </select>
                        </div>
                        <button type="submit" class="filter-btn">絞り込む</button>
                    </form>
                </div>

                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>日付</th>
                                <th>ユーザー名</th>
                                <th>件名</th>
                                <th>ステータス</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- 問い合わせデータをループして表示 --}}
                            @forelse($contacts ?? [] as $contact)
                                <tr>
                                    <td>{{ $contact->id }}</td>
                                    <td>{{ $contact->created_at?->format('Y-m-d') }}</td>
                                    <td>{{ $contact->user->name ?? '不明' }}</td>
                                    <td>{{ $contact->subject }}</td>
                                    <td>
                                        @switch($contact->status)
                                            @case('new')
                                                <span class="status-badge status-new">新規</span>
                                                @break
                                            @case('open')
                                                <span class="status-badge status-open">対応中</span>
                                                @break
                                            @case('closed')
                                                <span class="status-badge status-closed">完了</span>
                                                @break
                                            @default
                                                <span class="status-badge">{{ $contact->status }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ url('/admin/contacts/' . $contact->id) }}" class="btn btn-secondary">詳細</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 40px; color: #666;">
                                        問い合わせデータがありません。
                                    </td>
                                </tr>
                            @endforelse


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
