# F 野村さん 実装ガイド（管理者機能）

## 担当画面

| 画面名 | ブランチ名 |
|------|----------|
| ユーザ一覧画面 | feature/nomura-user-list |
| ユーザ詳細画面 | feature/nomura-user-detail |
| 問い合わせ一覧画面 | feature/nomura-contact-list |
| 問い合わせ詳細画面 | feature/nomura-contact-detail |

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

## 管理者機能について

管理者機能は`App\Http\Controllers\Admin`名前空間に作成します。

コントローラ作成コマンド:
```bash
docker compose exec app php artisan make:controller Admin/UserController
docker compose exec app php artisan make:controller Admin/ContactController
```

ルートは`/admin`プレフィックスを使用:
```php
Route::prefix('admin')->middleware('auth')->group(function () {
    // 管理者ルート
});
```

---

## 各画面の実装方法

### 1. ユーザ一覧画面

**概要**: 登録ユーザーの一覧を表示します。

**参照モック**: `context/画面レイアウト/admin_user_list_screen.html`

**実装手順**:
1. ブランチを作成: `git checkout -b feature/nomura-user-list`
2. `Admin/UserController.php`に`index`メソッドを追加
3. 全ユーザーを取得し、ページネーション:
   ```php
   Member::paginate(20);
   ```
4. 表示項目: ユーザーID、名前、メール、ステータス
5. 検索・フィルター機能（任意）
6. ビューを作成: `resources/views/admin/user/index.blade.php`

---

### 2. ユーザ詳細画面

**概要**: ユーザーの詳細情報と管理操作を表示します。

**参照モック**: `context/画面レイアウト/admin_user_detail_screen.html`

**実装手順**:
1. ブランチを作成: `git checkout -b feature/nomura-user-detail`
2. `Admin/UserController.php`に`show`、`suspend`、`unsuspend`メソッドを追加
3. 表示内容: ユーザー情報、所有土地一覧、取引履歴
4. 管理操作:
   - アカウント凍結ボタン（ACCOUNT_STATUS=1に更新）
   - 凍結解除ボタン（ACCOUNT_STATUS=0に更新）
5. ビューを作成: `resources/views/admin/user/show.blade.php`

---

### 3. 問い合わせ一覧画面

**概要**: ユーザーからの問い合わせ一覧を表示します。

**参照モック**: `context/画面レイアウト/admin_contact_list_screen.html`

**実装手順**:
1. ブランチを作成: `git checkout -b feature/nomura-contact-list`
2. `Admin/ContactController.php`に`index`メソッドを追加
3. 全問い合わせを取得:
   ```php
   Contact::with('member')
       ->orderBy('created_at', 'desc')
       ->paginate(20);
   ```
4. 未対応/対応済みのフィルター機能
5. ビューを作成: `resources/views/admin/contact/index.blade.php`

---

### 4. 問い合わせ詳細画面

**概要**: 問い合わせの詳細と返信機能を表示します。

**参照モック**: `context/画面レイアウト/admin_contact_detail_screen.html`

**実装手順**:
1. ブランチを作成: `git checkout -b feature/nomura-contact-detail`
2. `Admin/ContactController.php`に`show`と`reply`メソッドを追加
3. 表示内容: 問い合わせ内容、返信履歴
4. 返信フォームを設置し、`REPLY_TABLE`に保存
5. ビューを作成: `resources/views/admin/contact/show.blade.php`

---

## フォルダ構成

```
app/Http/Controllers/Admin/
├── UserController.php
└── ContactController.php

resources/views/admin/
├── user/
│   ├── index.blade.php
│   └── show.blade.php
└── contact/
    ├── index.blade.php
    └── show.blade.php
```

---

## 作業完了後

各画面の実装が終わったら、GitHubでプルリクエストを作成してください。
