# チームリーダー用ガイド

このドキュメントは**チームリーダー専用**の管理ガイドです。

---

## 目次
1. [リーダーの役割](#リーダーの役割)
2. [リポジトリ管理](#リポジトリ管理)
3. [プルリクエストのレビュー](#プルリクエストのレビュー)
4. [メンバーへの作業割り当て](#メンバーへの作業割り当て)
5. [環境の初期セットアップ](#環境の初期セットアップ)
6. [トラブル対応](#トラブル対応)

---

## リーダーの役割

| 役割 | 内容 |
|-----|------|
| **コードレビュー** | メンバーのプルリクエストを確認・マージ |
| **作業管理** | 誰が何を担当するか割り当て |
| **問題解決** | エラーや困りごとの相談対応 |
| **品質管理** | コードの一貫性・動作確認 |

---

## リポジトリ管理

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

### Collaboratorsの追加

メンバーをリポジトリに招待：

1. Settings → Collaborators
2. 「Add people」
3. メンバーのGitHubユーザー名を入力
4. 招待メールが送られる

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

```
□ コードは動作するか（ローカルで確認）
□ 変数名・関数名は分かりやすいか
□ 不要なコメントアウトは残っていないか
□ コミットメッセージは適切か
□ ファイルの場所は正しいか
```

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

### マージ後のクリーンアップ

```bash
# ローカルのブランチ一覧を更新
git fetch --prune

# マージ済みのローカルブランチを削除
git branch -d feature/古いブランチ名
```

---

## メンバーへの作業割り当て

### システム機能一覧

| # | 機能 | 説明 | 優先度 |
|---|------|------|-------|
| 1 | 会員登録 | 新規ユーザーの登録 | ★★★ |
| 2 | ログイン/ログアウト | 認証機能 | ★★★ |
| 3 | プロフィール編集 | ユーザー情報の変更 | ★★ |
| 4 | 土地登録 | オーナーが土地を登録 | ★★★ |
| 5 | 土地一覧・検索 | 条件で土地を検索 | ★★★ |
| 6 | 土地詳細表示 | 土地の詳細情報表示 | ★★★ |
| 7 | レンタル予約 | 土地の予約申し込み | ★★★ |
| 8 | レンタル承認 | オーナーが予約を承認 | ★★ |
| 9 | レンタル中一覧 | 借りている土地一覧 | ★★ |
| 10 | 自己保持土地一覧 | 自分が登録した土地一覧 | ★★ |
| 11 | DM/チャット | ユーザー間のメッセージ | ★ |
| 12 | レビュー・評価 | 取引後の評価 | ★ |
| 13 | 問い合わせ | サイトへの問い合わせ | ★ |
| 14 | 管理者：ユーザー管理 | ユーザーの一覧・詳細 | ★ |
| 15 | 管理者：問い合わせ管理 | 問い合わせ対応 | ★ |

---

### 開発フェーズと担当分担（仮）

#### Phase 1: 共通基盤（リーダー担当）
```
期間: 1週目
内容:
- データベースマイグレーション作成（全テーブル）
- 共通レイアウト（ヘッダー、フッター）
- 認証機能の基盤
```

#### Phase 2: コア機能（週2〜3）

| 担当 | 機能 | ブランチ名 | 作成ファイル |
|-----|------|----------|-------------|
| **B 楠山** | 会員登録 | feature/kusuyama-register | AuthController, Member Model |
| **B 楠山** | ログイン | feature/kusuyama-login | LoginController, auth views |
| **B 楠山** | 土地登録 | feature/kusuyama-land-register | LandController@create/store |
| **A 小島** | 土地検索 | feature/kojima-land-search | LandController@search |
| **A 小島** | 土地詳細 | feature/kojima-land-detail | LandController@show |
| **C 志賀** | トップページ | feature/shiga-home | HomeController |
| **C 志賀** | 自己保持土地一覧 | feature/shiga-my-lands | LandController@myLands |

#### Phase 3: レンタル機能（週4）

| 担当 | 機能 | ブランチ名 | 作成ファイル |
|-----|------|----------|-------------|
| **A 小島** | レンタル確認 | feature/kojima-rental-confirm | RentalController@confirm |
| **C 志賀** | 土地貸出承認 | feature/shiga-rental-approve | RentalController@approve |
| **E 三輪** | レンタル中一覧 | feature/miwa-rental-list | RentalController@index |
| **E 三輪** | レンタル中詳細 | feature/miwa-rental-detail | RentalController@show |
| **E 三輪** | 取引完了一覧 | feature/miwa-completed-list | RentalController@completed |

#### Phase 4: ユーザー機能（週5）

| 担当 | 機能 | ブランチ名 | 作成ファイル |
|-----|------|----------|-------------|
| **D 我妻** | プロフィール編集 | feature/azuma-profile-edit | ProfileController@edit/update |
| **D 我妻** | プロフィール確認 | feature/azuma-profile-show | ProfileController@show |
| **D 我妻** | DM一覧 | feature/azuma-dm-list | ChatController@index |
| **D 我妻** | DM画面 | feature/azuma-dm-chat | ChatController@show |

#### Phase 5: 管理者機能（週6）

| 担当 | 機能 | ブランチ名 | 作成ファイル |
|-----|------|----------|-------------|
| **F 野村** | ユーザー一覧 | feature/nomura-user-list | Admin/UserController@index |
| **F 野村** | ユーザー詳細 | feature/nomura-user-detail | Admin/UserController@show |
| **F 野村** | 問い合わせ一覧 | feature/nomura-contact-list | Admin/ContactController@index |
| **F 野村** | 問い合わせ詳細 | feature/nomura-contact-detail | Admin/ContactController@show |

---

### 画面担当一覧（member_.mdより）

| 担当 | 担当画面 |
|-----|---------|
| A 小島 | 問い合わせ、検索結果、土地詳細、レンタル確認 |
| B 楠山 | 会員登録、ログイン、土地登録、土地登録確認 |
| C 志賀 | ユーザ画面、トップ、自己保持土地一覧、土地貸出、貸出中詳細 |
| D 我妻 | プロフィール編集、プロフィール確認、DM一覧、DM画面 |
| E 三輪 | レンタル中一覧、レンタル中詳細、取引完了一覧、取引完了詳細 |
| F 野村 | ユーザ一覧、ユーザ詳細、問い合わせ一覧、問い合わせ詳細 |

---

### 作業の進め方

1. **ブランチ名のルール**を徹底
   ```
   feature/担当者名-機能名
   例: feature/kojima-search-results
   ```

2. **1機能1プルリクエスト**
   - 大きな変更は分割してPRを出してもらう

3. **毎日の進捗確認**
   - 困っていることがないか確認
   - プルリクエストは早めにレビュー

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

新しいテーブルを追加する場合：

```bash
# リーダーがマイグレーション作成
docker compose exec app php artisan make:migration create_lands_table

# ファイルを編集後、コミット
git add database/migrations/
git commit -m "Add lands table migration"
git push
```

### 共通レイアウト作成

```bash
# レイアウトファイル作成
resources/views/layouts/app.blade.php
```

---

## トラブル対応

### メンバーの環境が動かない場合

1. **Docker起動確認**
   ```bash
   docker compose ps
   # 全てのサービスがrunningか確認
   ```

2. **ログ確認**
   ```bash
   docker compose logs app
   docker compose logs mysql
   ```

3. **キャッシュクリア**
   ```bash
   docker compose exec app php artisan cache:clear
   docker compose exec app php artisan config:clear
   ```

4. **再構築**
   ```bash
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
# <<<<< と >>>>> の間を修正

# 解決後
git add .
git commit -m "Resolve merge conflict"
git push
```

### 本番デプロイ前チェックリスト

```
□ 全てのプルリクエストがマージ済み
□ ローカルで全機能の動作確認
□ .envの本番設定確認
□ マイグレーション実行
□ キャッシュクリア
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

*最終更新: 2025-12-16*
