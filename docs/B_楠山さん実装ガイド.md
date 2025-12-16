# B 楠山さん 実装ガイド

## 担当画面

| 画面名 | ブランチ名 |
|------|----------|
| 会員登録画面 | feature/kusuyama-register |
| ログイン画面 | feature/kusuyama-login |
| 土地登録画面 | feature/kusuyama-land-register |
| 土地登録確認画面 | feature/kusuyama-land-confirm |

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

### 1. 会員登録画面

**概要**: 新規ユーザーの登録フォームを作成します。

**参照モック**: `context/画面レイアウト/register_screen.html`

**実装手順**:
1. ブランチを作成: `git checkout -b feature/kusuyama-register`
2. 既存の`AuthController.php`を使用（リーダーが作成済み）
3. ビューを作成: `resources/views/auth/register.blade.php`
4. フォーム入力項目:
   - ユーザー名（必須）
   - メールアドレス（必須）
   - パスワード（必須、8文字以上）
   - パスワード確認（必須）
   - 電話番号（任意）
   - 生年月日（任意）
   - 性別（任意）
5. 共通レイアウト`@extends('layouts.app')`を使用
6. フォームクラス`.form-group`, `.form-input`を使用

---

### 2. ログイン画面

**概要**: 既存ユーザーのログインフォームを作成します。

**参照モック**: `context/画面レイアウト/login_screen.html`

**実装手順**:
1. ブランチを作成: `git checkout -b feature/kusuyama-login`
2. 既存の`AuthController.php`を使用
3. ビューを作成: `resources/views/auth/login.blade.php`
4. フォーム入力項目:
   - メールアドレス（必須）
   - パスワード（必須）
   - ログイン状態保持チェックボックス
5. エラーメッセージの表示（`@error`ディレクティブ使用）

---

### 3. 土地登録画面

**概要**: ログインユーザーが土地を登録するフォームです。

**参照モック**: `context/画面レイアウト/land_register_form_screen.html`

**実装手順**:
1. ブランチを作成: `git checkout -b feature/kusuyama-land-register`
2. `LandController.php`に`create`と`store`メソッドを追加
3. ルートを追加（ログイン必須）:
   ```php
   Route::middleware('auth')->group(function () {
       Route::get('/lands/create', [LandController::class, 'create']);
       Route::post('/lands', [LandController::class, 'store']);
   });
   ```
4. フォーム入力項目:
   - 都道府県（必須、セレクトボックス）
   - 市区町村（必須）
   - 住所（必須）
   - 面積（必須、数値）
   - 料金（任意）
   - 説明（任意）
   - 画像（任意）
5. ビューを作成: `resources/views/land/create.blade.php`

---

### 4. 土地登録確認画面

**概要**: 土地登録前の確認画面です。

**参照モック**: `context/画面レイアウト/land_register_confirmation_screen.html`

**実装手順**:
1. ブランチを作成: `git checkout -b feature/kusuyama-land-confirm`
2. `LandController.php`に`confirm`メソッドを追加
3. セッションに入力内容を一時保存して確認画面に渡す
4. 確定ボタンで`LAND_TABLE`に保存
5. ビューを作成: `resources/views/land/confirm.blade.php`

---

## 作業完了後

各画面の実装が終わったら、GitHubでプルリクエストを作成してください。
