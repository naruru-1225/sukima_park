# D 我妻さん 実装ガイド

## 担当画面

| 画面名 | ブランチ名 |
|------|----------|
| プロフィール編集画面 | feature/azuma-profile-edit |
| プロフィール確認画面 | feature/azuma-profile-confirm |
| DM一覧画面 | feature/azuma-dm-list |
| DM画面 | feature/azuma-dm-chat |

---

## 毎日の作業の流れ

### 作業開始時（必ず実行）

```bash
# 1. プロジェクトフォルダに移動
cd sukimapark

# 2. 最新のコードを取得（他のメンバーの変更を反映）
git pull

# 3. Dockerコンテナを起動
docker compose up -d

# 4. 動作確認
#    ブラウザで http://localhost を開く
```

### 作業中（こまめにコミット）

```bash
# 変更を保存（こまめに行う）
git add .
git commit -m "変更内容を書く"
```

### 作業終了時（必ず実行）

```bash
# 1. 変更をコミット（まだしていなければ）
git add .
git commit -m "作業内容"

# 2. GitHubにプッシュ
git push

# 3. Dockerコンテナを停止
docker compose down

# 4. WSLをシャットダウン（重要！）
wsl --shutdown
```

---

## 各画面の実装方法

### 1. プロフィール編集画面

**概要**: ログインユーザーが自分のプロフィールを編集するフォームです。

**参照モック**: `context/画面レイアウト/profile_edit_screen.html`

**実装手順**:
1. ブランチを作成: `git checkout -b feature/azuma-profile-edit`
2. コントローラを作成:
   ```bash
   docker compose exec app php artisan make:controller ProfileController
   ```
3. `ProfileController.php`に`edit`と`update`メソッドを追加
4. ルートを追加（ログイン必須）:
   ```php
   Route::middleware('auth')->group(function () {
       Route::get('/profile/edit', [ProfileController::class, 'edit']);
       Route::put('/profile', [ProfileController::class, 'update']);
   });
   ```
5. 編集項目:
   - ユーザー名
   - 自己紹介
   - 電話番号
   - アイコン画像
   - 生年月日公開設定（チェックボックス）
   - 性別公開設定（チェックボックス）
6. ビューを作成: `resources/views/profile/edit.blade.php`

---

### 2. プロフィール確認画面

**概要**: プロフィール編集前の確認画面です。

**参照モック**: `context/画面レイアウト/profile_edit_confirmation_screen.html`

**実装手順**:
1. ブランチを作成: `git checkout -b feature/azuma-profile-confirm`
2. `ProfileController.php`に`confirm`メソッドを追加
3. セッションに入力内容を一時保存
4. 確定ボタンで`MEMBER_TABLE`を更新
5. ビューを作成: `resources/views/profile/confirm.blade.php`

---

### 3. DM一覧画面

**概要**: ダイレクトメッセージの相手一覧を表示します。

**参照モック**: `context/画面レイアウト/message_list_screen.html`

**実装手順**:
1. ブランチを作成: `git checkout -b feature/azuma-dm-list`
2. コントローラを作成:
   ```bash
   docker compose exec app php artisan make:controller ChatController
   ```
3. `ChatController.php`に`index`メソッドを追加
4. 自分が送信または受信したメッセージの相手をグループ化:
   ```php
   Chat::where('USER_ID_FROM', Auth::id())
       ->orWhere('USER_ID_TO', Auth::id())
       ->orderBy('SEND_DATE', 'desc')
       ->get()
       ->groupBy(function($chat) use ($userId) {
           return $chat->USER_ID_FROM == $userId 
               ? $chat->USER_ID_TO 
               : $chat->USER_ID_FROM;
       });
   ```
5. ビューを作成: `resources/views/chat/index.blade.php`

---

### 4. DM画面

**概要**: 特定ユーザーとのメッセージ履歴を表示し、メッセージを送信します。

**参照モック**: `context/画面レイアウト/message_detail_screen.html`

**実装手順**:
1. ブランチを作成: `git checkout -b feature/azuma-dm-chat`
2. `ChatController.php`に`show`と`store`メソッドを追加
3. ルートを追加:
   ```php
   Route::middleware('auth')->group(function () {
       Route::get('/chats/{user}', [ChatController::class, 'show']);
       Route::post('/chats/{user}', [ChatController::class, 'store']);
   });
   ```
4. メッセージ送信時に`CHAT_TABLE`に保存
5. LINEのようなチャット形式で表示
6. ビューを作成: `resources/views/chat/show.blade.php`

---

## 作業完了後

各画面の実装が終わったら、GitHubでプルリクエストを作成してください。
