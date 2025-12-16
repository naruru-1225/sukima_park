# A 小島さん 実装ガイド

## 担当画面

| 画面名 | ブランチ名 |
|------|----------|
| 問い合わせ画面 | feature/kojima-contact |
| 検索結果一覧画面 | feature/kojima-search-result |
| 土地詳細画面 | feature/kojima-land-detail |
| レンタル確認画面 | feature/kojima-rental-confirm |

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

### 1. 問い合わせ画面

**概要**: サイトへの問い合わせフォームを作成します。

**参照モック**: `context/画面レイアウト/contact_form_screen.html`

**実装手順**:
1. ブランチを作成: `git checkout -b feature/kojima-contact`
2. コントローラを作成:
   ```bash
   docker compose exec app php artisan make:controller ContactController
   ```
3. `ContactController.php`に`create`と`store`メソッドを追加
4. ルートを追加（`routes/web.php`）:
   ```php
   Route::get('/contact', [ContactController::class, 'create']);
   Route::post('/contact', [ContactController::class, 'store']);
   ```
5. ビューを作成: `resources/views/contact/create.blade.php`
6. フォーム入力項目: タイトル、内容
7. 送信時に`CONTACT_TABLE`に保存

---

### 2. 検索結果一覧画面

**概要**: トップ画面の検索フォームから渡されたパラメータで土地を検索し、結果を一覧表示します。

**参照モック**: `context/画面レイアウト/search_results_screen.html`

**実装手順**:
1. ブランチを作成: `git checkout -b feature/kojima-search-result`
2. コントローラを作成:
   ```bash
   docker compose exec app php artisan make:controller LandController
   ```
3. `LandController.php`に`index`メソッドを追加
4. 検索条件: 都道府県、市区町村、料金上限、面積下限
5. クエリビルダで絞り込み（`where`, `when`を使用）
6. ページネーション機能を実装（`paginate(12)`）
7. ビューを作成: `resources/views/land/index.blade.php`

---

### 3. 土地詳細画面

**概要**: 土地の詳細情報を表示します。

**参照モック**: `context/画面レイアウト/land_detail_screen.html`

**実装手順**:
1. ブランチを作成: `git checkout -b feature/kojima-land-detail`
2. `LandController.php`に`show`メソッドを追加
3. ルートを追加: `Route::get('/lands/{id}', [LandController::class, 'show'])`
4. 表示内容: 土地情報、所有者情報、レビュー一覧
5. ログインユーザーにはレンタル申請ボタンを表示（`@auth`を使用）
6. ビューを作成: `resources/views/land/show.blade.php`

---

### 4. レンタル確認画面

**概要**: レンタル申請前の確認画面です。

**参照モック**: `context/画面レイアウト/booking_confirmation_screen.html`

**実装手順**:
1. ブランチを作成: `git checkout -b feature/kojima-rental-confirm`
2. コントローラを作成:
   ```bash
   docker compose exec app php artisan make:controller RentalController
   ```
3. `RentalController.php`に`confirm`と`store`メソッドを追加
4. ログイン必須: ルートに`middleware('auth')`を設定
5. 確定ボタンで`RENTAL_RECORD_TABLE`に保存
6. ビューを作成: `resources/views/rental/confirm.blade.php`

---

## 作業完了後

各画面の実装が終わったら、GitHubでプルリクエストを作成してください。
