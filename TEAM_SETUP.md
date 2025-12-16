# スキマパーク チームセットアップガイド

このガイドは、チームメンバー全員が**同じ開発環境**を構築するための手順書です。

---

## 目次
1. [前提条件](#前提条件)
2. [セットアップ手順](#セットアップ手順)
3. [コマンド解説](#コマンド解説)
4. [トラブルシューティング](#トラブルシューティング)

---

## 前提条件

### 必要なソフトウェア

| ソフトウェア | 目的 | ダウンロード |
|------------|------|------------|
| Docker Desktop | アプリ・DBをコンテナで動かす | [公式サイト](https://www.docker.com/products/docker-desktop/) |
| Git | ソースコード管理 | [公式サイト](https://git-scm.com/) |

### Docker Desktopとは？
```
「自分のPCの中に小さなサーバーを作る」ツールです。

これにより:
- 全員が同じPHP、MySQLバージョンを使える
- 「自分の環境では動くのに...」問題が起きない
- PHP・MySQLをPCに直接インストール不要
```

---

## セットアップ手順

### Step 1: リポジトリをクローン

```bash
git clone https://github.com/naruru-1225/sukima_park.git
cd sukima_park
```

**解説**:
- `git clone` = GitHubからプロジェクトをダウンロード
- `cd` = フォルダに移動

---

### Step 2: 依存パッケージをインストール

```bash
# Windows (PowerShell)
docker run --rm -v "${PWD}:/app" -w /app composer install

# Mac/Linux
docker run --rm -v "$(pwd)":/app -w /app composer install
```

**解説**:
- `docker run` = Dockerコンテナを一時的に起動
- `--rm` = 処理後にコンテナを自動削除
- `-v "${PWD}:/app"` = 現在のフォルダをコンテナ内の/appに接続
- `composer install` = Laravelが必要とするライブラリをダウンロード

**何が起きる？**
→ `vendor/` フォルダが作成され、Laravel本体や関連ライブラリがダウンロードされる

---

### Step 3: 環境設定ファイルをコピー

```bash
# Windows
copy .env.example .env

# Mac/Linux
cp .env.example .env
```

**解説**:
- `.env` = データベース情報などの設定ファイル
- `.env.example` = チームで共有するテンプレート
- `.env`自体はGitにアップロードされない（セキュリティのため）

---

### Step 4: コンテナを起動

```bash
docker compose up -d
```

**解説**:
- `docker compose up` = docker-compose.ymlに書かれたサービスを起動
- `-d` = バックグラウンドで起動（ターミナルを占有しない）

**起動するもの**:
| サービス | 説明 | ポート |
|---------|------|-------|
| app | PHPアプリケーション | 80 |
| mysql | データベース | 3306 |

---

### Step 5: アプリケーションキーを生成

```bash
docker compose exec app php artisan key:generate
```

**解説**:
- `docker compose exec app` = appコンテナ内でコマンド実行
- `php artisan key:generate` = Laravelの暗号化に使うキーを生成
- このキーは`.env`の`APP_KEY`に自動で設定される

---

### Step 6: データベースマイグレーション

```bash
docker compose exec app php artisan migrate
```

**解説**:
- `migrate` = データベースにテーブルを作成
- `database/migrations/`フォルダのファイルが実行される
- チームで同じテーブル構造を共有できる

---

### Step 7: ブラウザで確認

http://localhost にアクセス

Laravelのウェルカムページが表示されれば成功！

---

## よく使うコマンド

### 開発中に使うコマンド

```bash
# コンテナ起動
docker compose up -d

# コンテナ停止
docker compose down

# コンテナの状態確認
docker compose ps

# ログ確認
docker compose logs -f app
```

### Laravelのコマンド（artisan）

```bash
# Laravelコマンドの基本形
docker compose exec app php artisan [コマンド]

# マイグレーション実行
docker compose exec app php artisan migrate

# モデル作成
docker compose exec app php artisan make:model モデル名

# コントローラ作成
docker compose exec app php artisan make:controller コントローラ名

# 全てのルート確認
docker compose exec app php artisan route:list
```

### MySQLに接続

```bash
docker compose exec mysql mysql -u sail -ppassword sukimapark
```

---

## トラブルシューティング

### ポート80が使用中

**エラー**: `Bind for 0.0.0.0:80 failed: port is already allocated`

**解決策**:
1. `.env`ファイルを開く
2. 以下を追加:
   ```
   APP_PORT=8080
   ```
3. コンテナ再起動:
   ```bash
   docker compose down
   docker compose up -d
   ```
4. http://localhost:8080 でアクセス

---

### Dockerが起動しない

1. Docker Desktopアプリを開いて実行中か確認
2. Windowsの場合: WSL2が有効か確認
   ```
   設定 → アプリ → オプション機能 → Windows機能 → 
   「Linux用Windowsサブシステム」にチェック
   ```

---

### 変更が反映されない

```bash
# キャッシュクリア
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan view:clear
```

---

## プロジェクト構成（主要ファイル）

```
sukima_park/
├── app/                 # アプリケーションのコード
│   ├── Http/
│   │   └── Controllers/ # コントローラー
│   └── Models/          # モデル（DB操作）
├── database/
│   └── migrations/      # マイグレーション（テーブル定義）
├── resources/
│   └── views/           # ビュー（画面のHTML）
├── routes/
│   └── web.php          # ルーティング（URLの定義）
├── .env                 # 環境設定（各自のPC用）
├── .env.example         # 環境設定のテンプレート
└── docker-compose.yml   # Docker設定
```

---

*最終更新: 2025-12-16*
