# C 志賀さん 実装ガイド

## 担当画面

| 画面名 | ブランチ名 |
|------|----------|
| ユーザ画面(自アカウント) | feature/shiga-user-self |
| ユーザ画面(他アカウント) | feature/shiga-user-other |
| トップ画面 | ✅ リーダー実装済み |
| 自己保持土地一覧画面 | feature/shiga-my-lands |
| 土地貸出画面 | feature/shiga-rental-lend |
| 貸出中詳細画面 | feature/shiga-lending-detail |

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

### 1. ユーザ画面(自アカウント) / ユーザ画面(他アカウント)

**概要**: ユーザーのプロフィールを表示します。自分と他人で表示を切り替えます。

**参照モック**: 
- `context/画面レイアウト/my_profile_screen.html`（自アカウント）
- `context/画面レイアウト/user_profile_screen.html`（他アカウント）

**実装手順**:
1. ブランチを作成: `git checkout -b feature/shiga-user-self`
2. コントローラを作成:
   ```bash
   docker compose exec app php artisan make:controller UserController
   ```
3. `UserController.php`に`show`メソッドを追加
4. 自分の場合と他人の場合で条件分岐:
   ```php
   $isOwner = Auth::check() && Auth::id() == $user->USER_ID;
   ```
5. 自分の場合: 編集ボタンを表示
6. 他人の場合: DMボタンを表示
7. ビューを作成: `resources/views/user/show.blade.php`

---

### 2. 自己保持土地一覧画面

**概要**: ログインユーザーが所有する土地の一覧を表示します。

**参照モック**: `context/画面レイアウト/my_lands_list_screen.html`

**実装手順**:
1. ブランチを作成: `git checkout -b feature/shiga-my-lands`
2. `LandController.php`に`myLands`メソッドを追加
3. ルートを追加（ログイン必須）:
   ```php
   Route::get('/my-lands', [LandController::class, 'myLands'])->middleware('auth');
   ```
4. `Auth::id()`で自分のUSER_IDを取得し、土地を絞り込み
5. 土地ごとに編集・削除ボタンを表示
6. ビューを作成: `resources/views/land/my-lands.blade.php`

---

### 3. 土地貸出画面

**概要**: 自分の土地へのレンタル申請一覧を表示し、承認/拒否します。

**参照モック**: `context/画面レイアウト/listed_lands_screen.html`

**実装手順**:
1. ブランチを作成: `git checkout -b feature/shiga-rental-lend`
2. `RentalController.php`に`lend`、`approve`、`reject`メソッドを追加
3. 自分の土地への申請を取得:
   ```php
   RentalRecord::whereHas('land', function($q) {
       $q->where('USER_ID', Auth::id());
   })->where('STATUS', 0)->get();
   ```
4. 承認/拒否ボタンでSTATUSを更新
5. ビューを作成: `resources/views/rental/lend.blade.php`

---

### 4. 貸出中詳細画面

**概要**: 現在貸し出し中の土地の詳細情報を表示します。

**参照モック**: `context/画面レイアウト/my_land_detail_screen.html`

**実装手順**:
1. ブランチを作成: `git checkout -b feature/shiga-lending-detail`
2. `RentalController.php`に`lendingShow`メソッドを追加
3. 表示内容: 土地情報、借り手情報、レンタル期間、ステータス
4. ビューを作成: `resources/views/rental/lending-show.blade.php`

---

## 作業完了後

各画面の実装が終わったら、GitHubでプルリクエストを作成してください。
