# E 三輪さん 実装ガイド

## 担当画面

| 画面名 | ブランチ名 |
|------|----------|
| レンタル中一覧画面 | feature/miwa-rental-list |
| レンタル中詳細画面 | feature/miwa-rental-detail |
| レビュー画面 | feature/miwa-review |
| 取引完了一覧画面 | feature/miwa-completed-list |
| 取引完了詳細画面 | feature/miwa-completed-detail |

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

### 1. レンタル中一覧画面

**概要**: 自分がレンタル中の土地一覧を表示します。

**参照モック**: `context/画面レイアウト/active_rental_list_screen.html`

**実装手順**:
1. ブランチを作成: `git checkout -b feature/miwa-rental-list`
2. `RentalController.php`に`index`メソッドを追加
3. ルートを追加（ログイン必須）:
   ```php
   Route::get('/my-rentals', [RentalController::class, 'index'])->middleware('auth');
   ```
4. 自分のレンタル記録（STATUS=1:承認済み）を取得:
   ```php
   RentalRecord::where('USER_ID', Auth::id())
       ->where('STATUS', 1)
       ->with('land')
       ->get();
   ```
5. ビューを作成: `resources/views/rental/index.blade.php`

---

### 2. レンタル中詳細画面

**概要**: レンタル中の土地の詳細情報を表示します。

**参照モック**: `context/画面レイアウト/active_rental_detail_screen.html`

**実装手順**:
1. ブランチを作成: `git checkout -b feature/miwa-rental-detail`
2. `RentalController.php`に`show`メソッドを追加
3. 表示内容: 土地情報、オーナー情報、レンタル期間
4. 返却ボタン（取引完了処理）を設置
5. ビューを作成: `resources/views/rental/show.blade.php`

---

### 3. レビュー画面

**概要**: 取引完了後にレビューを投稿する画面です。

**参照モック**: `context/画面レイアウト/submit_review_screen.html`

**実装手順**:
1. ブランチを作成: `git checkout -b feature/miwa-review`
2. コントローラを作成:
   ```bash
   docker compose exec app php artisan make:controller ReviewController
   ```
3. `ReviewController.php`に`create`と`store`メソッドを追加
4. フォーム入力項目:
   - 評価（1-5の星）
   - コメント
5. 送信時に`REVIEW_COMMENT_TABLE`に保存
6. ビューを作成: `resources/views/review/create.blade.php`

---

### 4. 取引完了一覧画面

**概要**: 完了した取引（レンタル終了）の一覧を表示します。

**参照モック**: `context/画面レイアウト/rental_history_list_screen.html`

**実装手順**:
1. ブランチを作成: `git checkout -b feature/miwa-completed-list`
2. `RentalController.php`に`completed`メソッドを追加
3. 完了した取引（STATUS=3:完了）を取得:
   ```php
   RentalRecord::where('USER_ID', Auth::id())
       ->where('STATUS', 3)
       ->with('land')
       ->get();
   ```
4. ビューを作成: `resources/views/rental/completed.blade.php`

---

### 5. 取引完了詳細画面

**概要**: 完了した取引の詳細情報を表示します。

**参照モック**: `context/画面レイアウト/rental_history_detail_screen.html`

**実装手順**:
1. ブランチを作成: `git checkout -b feature/miwa-completed-detail`
2. `RentalController.php`に`completedShow`メソッドを追加
3. レビュー投稿済みの場合: レビュー内容を表示
4. レビュー未投稿の場合: レビュー投稿ボタンを表示
5. ビューを作成: `resources/views/rental/completed-show.blade.php`

---

## 作業完了後

各画面の実装が終わったら、GitHubでプルリクエストを作成してください。
