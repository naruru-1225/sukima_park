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

### 画面担当（member_.mdより）

| 担当 | 画面 |
|-----|------|
| A 小島 | 問い合わせ、検索結果、土地詳細、レンタル確認 |
| B 楠山 | 会員登録、ログイン、土地登録、土地登録確認 |
| C 志賀 | ユーザ画面、トップ、自己保持土地一覧、土地貸出、貸出中詳細 |
| D 我妻 | プロフィール編集、プロフィール確認、DM一覧、DM画面 |
| E 三輪 | レンタル中一覧、レンタル中詳細、取引完了一覧、取引完了詳細 |
| F 野村 | ユーザ一覧、ユーザ詳細、問い合わせ一覧、問い合わせ詳細 |

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
