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

### 作業を始める前に

1. **プロジェクトフォルダに移動する**
   - VSCodeでsukimaparkフォルダを開きます

2. **最新のコードを取得する**
   - ターミナルで `git pull` を実行します
   - 他のメンバーの変更を自分のPCに反映させます

3. **Dockerを起動する**
   - ターミナルで `docker compose up -d` を実行します
   - これでLaravelが動くようになります

4. **ブラウザで確認する**
   - `http://localhost` を開いて、サイトが表示されればOKです

### 作業中

- こまめにコミットしましょう
- `git add .` で変更をステージング
- `git commit -m "何を変更したか"` で保存

### 作業を終わるとき

1. コミットする（まだしていなければ）
2. `git push` でGitHubにアップロード
3. `docker compose down` でDockerを停止
4. `wsl --shutdown` でWSLをシャットダウン（重要！）

---

## 各画面の作り方

### 会員登録画面

**何をする画面？**
新しいユーザーが会員登録するためのフォームです。

**参考にするモック**
`context/画面レイアウト/register_screen.html` を開いて、デザインを確認してください。

**作成の流れ**
1. ブランチを作成する（`git checkout -b feature/kusuyama-register`）
2. AuthControllerはリーダーが作成済みなので、ビューを作成します
3. `resources/views/auth/register.blade.php` を新規作成
4. 共通レイアウト `@extends('layouts.app')` を使う
5. フォームの入力項目：
   - ユーザー名（必須）
   - メールアドレス（必須）
   - パスワード（必須、8文字以上）
   - パスワード確認（必須）
   - 電話番号（任意）
   - 生年月日（任意）
   - 性別（任意）
6. 用意されたCSSクラス（`.form-group`, `.form-input`）を使う
7. エラーメッセージの表示も忘れずに

---

### ログイン画面

**何をする画面？**
登録済みユーザーがログインするためのフォームです。

**参考にするモック**
`context/画面レイアウト/login_screen.html` を確認してください。

**作成の流れ**
1. ブランチを作成する（`git checkout -b feature/kusuyama-login`）
2. `resources/views/auth/login.blade.php` を新規作成
3. フォームの入力項目：
   - メールアドレス（必須）
   - パスワード（必須）
   - ログイン状態を保持するチェックボックス
4. ログインボタン
5. 「新規登録はこちら」リンク
6. エラー時のメッセージ表示

---

### 土地登録画面

**何をする画面？**
ログインユーザーが自分の土地を登録するフォームです。

**参考にするモック**
`context/画面レイアウト/land_register_form_screen.html` を確認してください。

**作成の流れ**
1. ブランチを作成する（`git checkout -b feature/kusuyama-land-register`）
2. LandControllerを作成する（artisanコマンドを使う）
3. フォーム表示用と保存処理用のメソッドを作る
4. この画面はログイン必須（ミドルウェアで制限）
5. ルートを設定する
6. ビューを作成する
7. フォームの入力項目：
   - 都道府県（必須、セレクトボックス）
   - 市区町村（必須）
   - 住所（必須）
   - 面積（必須、数値）
   - 料金（任意）
   - 説明（任意）
   - 画像（任意）

---

### 土地登録確認画面

**何をする画面？**
土地登録する前の最終確認画面です。

**参考にするモック**
`context/画面レイアウト/land_register_confirmation_screen.html` を確認してください。

**作成の流れ**
1. ブランチを作成する（`git checkout -b feature/kusuyama-land-confirm`）
2. LandControllerに確認画面用のメソッドを追加
3. 入力内容をセッションに一時保存して確認画面に渡す
4. 確認画面で入力内容を表示
5. 「登録する」ボタンで LAND_TABLEに保存
6. 「戻る」ボタンで入力画面に戻れるようにする

---

## 困ったときは

- **TEAM_SETUP.md** を読み返す
- **トップ画面の作成フロー.md** を参考にする
- リーダーに聞く
- GitHubのコードを参考にする

---

## 作業完了後

各画面の実装が終わったら、GitHubでプルリクエストを作成してください。
