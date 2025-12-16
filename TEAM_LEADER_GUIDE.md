# チームリーダー用ガイド

このドキュメントは**チームリーダー専用**の管理ガイドです。
メンバー向けの環境構築・開発方法は `TEAM_SETUP.md` を参照してください。

---

## 目次

1. [リーダーの役割](#リーダーの役割)
2. [リポジトリ管理](#リポジトリ管理)
3. [プルリクエストのレビュー](#プルリクエストのレビュー)
4. [メンバーへの作業割り当て](#メンバーへの作業割り当て)
5. [データベース構成](#データベース構成)
6. [環境の初期セットアップ](#環境の初期セットアップ)
7. [トラブル対応](#トラブル対応)

---

## リーダーの役割

| 役割 | 内容 | 例えると |
|-----|------|---------|
| **コードレビュー** | メンバーのプルリクエストを確認・マージ | 先生の添削 |
| **作業管理** | 誰が何を担当するか割り当て | 班長の仕事分担 |
| **問題解決** | エラーや困りごとの相談対応 | 困った時の相談役 |
| **品質管理** | コードの一貫性・動作確認 | 最終チェック |

---

## リポジトリ管理

### GitHubアカウント作成の案内

メンバーには以下を案内してください：

1. https://github.com/ でアカウント作成
2. ユーザー名をリーダーに伝える
3. 招待メールを承認する

### Collaboratorsの追加（メンバー招待）

```
Settings → Collaborators → Add people → ユーザー名を入力
```

招待後、メンバーに「招待メールが届いたらAcceptしてください」と伝える。

### ブランチ保護ルールの設定

mainブランチに直接プッシュできないようにする設定：

1. GitHubでリポジトリを開く
2. Settings → Branches
3. 「Add branch protection rule」
4. Branch name pattern: `main`
5. 以下にチェック:
   - ☑ Require a pull request before merging
   - ☑ Require approvals (1人以上)
6. 「Create」をクリック

---

## プルリクエストのレビュー

### レビューの流れ

```
1. メンバーがプルリクエストを作成
2. GitHubから通知メールが届く
3. 「Files changed」タブでコードを確認
4. 問題があればコメント
5. OKなら「Approve」→「Merge pull request」
```

### レビュー時のチェックポイント

| チェック項目 | 確認内容 |
|------------|---------|
| 動作確認 | ローカルで動くか |
| 変数名 | 分かりやすいか |
| コメント | 不要なコメントアウトがないか |
| コミットメッセージ | 内容が適切か |
| ファイル配置 | 正しいフォルダにあるか |

### ローカルでプルリクエストを確認する方法

```bash
# メンバーのブランチを取得
git fetch origin
git checkout feature/メンバーのブランチ名

# サーバー起動して動作確認
docker compose up -d

# 確認後、mainに戻る
git checkout main
```

---

## メンバーへの作業割り当て

### システム機能一覧

| # | 機能 | 説明 | 優先度 | 作成ファイル |
|---|------|------|-------|-------------|
| 1 | 会員登録 | 新規ユーザーの登録 | ★★★ | AuthController, register.blade.php |
| 2 | ログイン/ログアウト | 認証機能 | ★★★ | LoginController, login.blade.php |
| 3 | プロフィール編集 | ユーザー情報の変更 | ★★ | ProfileController, profile/edit.blade.php |
| 4 | 土地登録 | オーナーが土地を登録 | ★★★ | LandController@create/store, land/create.blade.php |
| 5 | 土地一覧・検索 | 条件で土地を検索 | ★★★ | LandController@index/search, land/index.blade.php |
| 6 | 土地詳細表示 | 土地の詳細情報表示 | ★★★ | LandController@show, land/show.blade.php |
| 7 | レンタル予約 | 土地の予約申し込み | ★★★ | RentalController@confirm/store |
| 8 | レンタル承認 | オーナーが予約を承認 | ★★ | RentalController@approve |
| 9 | レンタル中一覧 | 借りている土地一覧 | ★★ | RentalController@index |
| 10 | 自己保持土地一覧 | 自分が登録した土地一覧 | ★★ | LandController@myLands |
| 11 | DM/チャット | ユーザー間のメッセージ | ★ | ChatController |
| 12 | レビュー・評価 | 取引後の評価 | ★ | ReviewController |
| 13 | 問い合わせ | サイトへの問い合わせ | ★ | ContactController |
| 14 | 管理者機能 | ユーザー・問い合わせ管理 | ★ | Admin/UserController, Admin/ContactController |

---

### 開発フェーズと担当分担

#### Phase 1: 共通基盤（リーダー担当）- 1週目

| 作業内容 | 状態 |
|---------|------|
| データベースマイグレーション作成 | ✅ 完了 |
| モデル作成 | ✅ 完了 |
| 共通レイアウト（ヘッダー、フッター） | 未着手 |
| 認証機能の基盤 | 未着手 |

---

#### Phase 2: コア機能 - 週2〜3

| 担当 | 機能 | ブランチ名 | 作成ファイル |
|-----|------|----------|-------------|
| **B 楠山** | 会員登録画面 | feature/kusuyama-register | `AuthController`, `views/auth/register.blade.php` |
| **B 楠山** | ログイン画面 | feature/kusuyama-login | `LoginController`, `views/auth/login.blade.php` |
| **B 楠山** | 土地登録画面 | feature/kusuyama-land-register | `LandController@create/store`, `views/land/create.blade.php` |
| **A 小島** | 土地検索画面 | feature/kojima-land-search | `LandController@search`, `views/land/index.blade.php` |
| **A 小島** | 土地詳細画面 | feature/kojima-land-detail | `LandController@show`, `views/land/show.blade.php` |
| **C 志賀** | トップページ | feature/shiga-home | `HomeController`, `views/home.blade.php` |
| **C 志賀** | 自己保持土地一覧 | feature/shiga-my-lands | `LandController@myLands`, `views/land/my-lands.blade.php` |

---

#### Phase 3: レンタル機能 - 週4

| 担当 | 機能 | ブランチ名 | 作成ファイル |
|-----|------|----------|-------------|
| **A 小島** | レンタル確認画面 | feature/kojima-rental-confirm | `RentalController@confirm`, `views/rental/confirm.blade.php` |
| **C 志賀** | 土地貸出承認画面 | feature/shiga-rental-approve | `RentalController@approve`, `views/rental/approve.blade.php` |
| **E 三輪** | レンタル中一覧 | feature/miwa-rental-list | `RentalController@index`, `views/rental/index.blade.php` |
| **E 三輪** | レンタル中詳細 | feature/miwa-rental-detail | `RentalController@show`, `views/rental/show.blade.php` |
| **E 三輪** | 取引完了一覧 | feature/miwa-completed-list | `RentalController@completed`, `views/rental/completed.blade.php` |

---

#### Phase 4: ユーザー機能 - 週5

| 担当 | 機能 | ブランチ名 | 作成ファイル |
|-----|------|----------|-------------|
| **D 我妻** | プロフィール編集 | feature/azuma-profile-edit | `ProfileController@edit/update`, `views/profile/edit.blade.php` |
| **D 我妻** | プロフィール確認 | feature/azuma-profile-show | `ProfileController@show`, `views/profile/show.blade.php` |
| **D 我妻** | DM一覧 | feature/azuma-dm-list | `ChatController@index`, `views/chat/index.blade.php` |
| **D 我妻** | DM画面 | feature/azuma-dm-chat | `ChatController@show`, `views/chat/show.blade.php` |

---

#### Phase 5: 管理者機能 - 週6

| 担当 | 機能 | ブランチ名 | 作成ファイル |
|-----|------|----------|-------------|
| **F 野村** | ユーザー一覧 | feature/nomura-user-list | `Admin/UserController@index`, `views/admin/user/index.blade.php` |
| **F 野村** | ユーザー詳細 | feature/nomura-user-detail | `Admin/UserController@show`, `views/admin/user/show.blade.php` |
| **F 野村** | 問い合わせ一覧 | feature/nomura-contact-list | `Admin/ContactController@index`, `views/admin/contact/index.blade.php` |
| **F 野村** | 問い合わせ詳細 | feature/nomura-contact-detail | `Admin/ContactController@show`, `views/admin/contact/show.blade.php` |

---

### 画面担当一覧（member_.mdより）

| 担当 | 担当画面 | 画面数 |
|-----|---------|-------|
| A 小島 | 問い合わせ、検索結果、土地詳細、レンタル確認 | 4画面 |
| B 楠山 | 会員登録、ログイン、土地登録、土地登録確認 | 4画面 |
| C 志賀 | ユーザ画面、トップ、自己保持土地一覧、土地貸出、貸出中詳細 | 5画面 |
| D 我妻 | プロフィール編集、プロフィール確認、DM一覧、DM画面 | 4画面 |
| E 三輪 | レンタル中一覧、レンタル中詳細、取引完了一覧、取引完了詳細 | 4画面 |
| F 野村 | ユーザ一覧、ユーザ詳細、問い合わせ一覧、問い合わせ詳細 | 4画面 |

---

### ブランチ名のルール

```
feature/担当者名-機能名
例: feature/kojima-search-results
```

### 作業の進め方

1. **1機能1プルリクエスト**
   - 大きな変更は分割してPRを出してもらう

2. **毎日の進捗確認**
   - 困っていることがないか確認
   - プルリクエストは早めにレビュー

3. **コミットメッセージのルール**
   ```
   ✅ 良い例: 土地一覧画面を作成
   ❌ 悪い例: 修正、更新、あ
   ```

---

## データベース構成

> ✅ **マイグレーション・モデルは作成済み**
> メンバーが触る必要はありません。

### テーブル一覧

| テーブル名 | 説明 | カラム数 |
|-----------|------|---------|
| MEMBER_TABLE | 会員情報 | 13 |
| LAND_TABLE | 土地情報 | 16 |
| RENTAL_RECORD_TABLE | 貸し出し記録 | 9 |
| REVIEW_COMMENT_TABLE | レビュー・コメント | 9 |
| CONTACT_TABLE | 問い合わせ | 6 |
| REPLY_TABLE | 返信 | 5 |
| CHAT_TABLE | 連絡（DM） | 8 |

### 主要なリレーション

```
Member (1) ─────→ (多) Land        : 会員は複数の土地を所有できる
Land (1) ─────→ (多) RentalRecord : 土地には複数の貸出記録がある
Member (1) ─────→ (多) RentalRecord: 会員は複数回レンタルできる
```

詳細は `TEAM_SETUP.md` の「データベーステーブル一覧」を参照。

---

## 環境の初期セットアップ

### 新プロジェクト作成時（リーダーが実行）

```bash
# 1. Laravelプロジェクト作成
docker run --rm -v "${PWD}:/app" -w /app composer create-project laravel/laravel sukimapark

# 2. プロジェクトに移動
cd sukimapark

# 3. Sailインストール
docker run --rm -v "${PWD}:/app" -w /app composer require laravel/sail --dev

# 4. Git初期化
git init
git add .
git commit -m "Initial Laravel project setup"

# 5. GitHubにプッシュ
git remote add origin https://github.com/チーム/リポジトリ名.git
git branch -M main
git push -u origin main
```

### データベースマイグレーション作成

```bash
# マイグレーション作成
docker compose exec app php artisan make:migration create_テーブル名_table

# マイグレーション実行
docker compose exec app php artisan migrate

# マイグレーションリセット（開発中のみ）
docker compose exec app php artisan migrate:fresh
```

---

## トラブル対応

### メンバーの環境が動かない場合

**確認手順：**

```bash
# 1. Docker起動確認
docker compose ps

# 2. ログ確認
docker compose logs app
docker compose logs mysql

# 3. キャッシュクリア
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear

# 4. 再構築
docker compose down
docker compose up -d --build
```

### マージコンフリクト発生時

```bash
# 最新のmainを取得
git checkout main
git pull

# 問題のブランチに移動
git checkout feature/問題のブランチ

# mainをマージ（コンフリクト発生）
git merge main

# コンフリクトを解決（ファイルを手動編集）
# <<<<<<< と >>>>>>> の間を修正

# 解決後
git add .
git commit -m "Resolve merge conflict"
git push
```

### 環境が壊れた場合

> ⚠️ **WSLシャットダウンの重要性**
>
> メンバーが作業終了時に `wsl --shutdown` を実行しないと環境が壊れることがあります。
> トラブル時はまず以下を試してください：

```bash
# 1. WSLをリセット
wsl --shutdown
# 2. Docker Desktopを再起動
# 3. 再度 docker compose up -d
```

---

## 定期的にやること

| 頻度 | タスク |
|-----|-------|
| 毎日 | プルリクエストのレビュー |
| 毎日 | メンバーの困りごと確認 |
| 週1回 | 進捗の全体確認 |
| リリース前 | 全機能の動作確認 |

---

## 本番デプロイ前チェックリスト

- [ ] 全てのプルリクエストがマージ済み
- [ ] ローカルで全機能の動作確認
- [ ] .envの本番設定確認
- [ ] マイグレーション実行
- [ ] キャッシュクリア

---

*最終更新: 2025-12-16*
