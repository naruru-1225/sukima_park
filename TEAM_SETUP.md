# スキマパーク チームセットアップガイド

このガイドは、**プログラミング初心者**でも迷わず環境構築ができるように詳しく書かれています。

---

## 目次
1. [はじめに読んでほしいこと](#はじめに読んでほしいこと)
2. [Git（ギット）とは](#gitギットとは)
3. [必要なソフトのインストール](#必要なソフトのインストール)
4. [セットアップ手順](#セットアップ手順)
5. [Laravelのフォルダ構成](#laravelのフォルダ構成)
6. [よく使うGitコマンド](#よく使うgitコマンド)
7. [よく使うDockerコマンド](#よく使うdockerコマンド)
8. [トラブルシューティング](#トラブルシューティング)

---

## はじめに読んでほしいこと

### このプロジェクトで使うツール

| ツール | 何をするもの？ | 例えると... |
|-------|--------------|------------|
| **Git** | コードの変更履歴を管理 | Wordの「変更履歴」機能 |
| **GitHub** | Gitのデータをネット上に保存 | Google Driveのようなもの |
| **Docker** | 開発環境を作る | パソコンの中に「仮想サーバー」を作る |
| **Laravel** | Webアプリを作るためのツール | 「Webアプリの骨組み」を提供 |

---

## Git（ギット）とは

### Gitが解決する問題

```
❌ Gitがない場合:
├── sukimapark_最新.zip
├── sukimapark_最新_修正.zip
├── sukimapark_最新_修正2_田中編集.zip
├── sukimapark_本当に最新.zip
└── どれが最新か分からない...
```

```
✅ Gitがある場合:
├── 全ての変更履歴が記録される
├── 誰がいつ何を変更したか分かる
├── 間違えても前の状態に戻せる
└── チーム全員が同じコードで作業できる
```

### Gitの基本用語

| 用語 | 読み方 | 意味 |
|-----|-------|------|
| **リポジトリ** | リポジトリ | プロジェクトのフォルダ（変更履歴込み） |
| **クローン** | クローン | GitHubからコピーを作ること |
| **コミット** | コミット | 変更を記録すること（セーブポイント） |
| **プッシュ** | プッシュ | 自分の変更をGitHubにアップロード |
| **プル** | プル | 他の人の変更をダウンロード |
| **ブランチ** | ブランチ | 作業用の「枝」を作って安全に開発 |

### Gitの流れ（図解）

```
【あなたのPC】          【GitHub】          【チームメンバーのPC】
     │                    │                      │
     │  ← git clone ───   │                      │
     │                    │                      │
     │  コードを編集       │                      │
     │  git add           │                      │
     │  git commit        │                      │
     │                    │                      │
     │  ─── git push →   │                      │
     │                    │                      │
     │                    │   ← git pull ────   │
     │                    │                      │
```

---

## 必要なソフトのインストール

### 1. Git のインストール

1. https://git-scm.com/download/win にアクセス
2. 「Click here to download」をクリック
3. ダウンロードしたファイルを実行
4. **全てデフォルト設定でOK**（Next連打でOK）
5. インストール完了

**確認方法**:
```
コマンドプロンプトを開いて入力:
git --version

↓ こう表示されればOK:
git version 2.xx.x
```

### 2. Docker Desktop のインストール

1. https://www.docker.com/products/docker-desktop/ にアクセス
2. 「Download for Windows」をクリック
3. ダウンロードしたファイルを実行
4. 「Use WSL 2 instead of Hyper-V」にチェック ✓
5. インストール完了後、**PCを再起動**
6. Docker Desktopアプリを起動

**確認方法**:
- Docker Desktopの画面左下が「Engine running」になっていればOK

### 3. VS Code のインストール（推奨）

1. https://code.visualstudio.com/ にアクセス
2. 「Download for Windows」をクリック
3. インストール

---

## セットアップ手順

### Step 1: フォルダを作成

1. デスクトップに「開発」フォルダを作成
2. そのフォルダを右クリック →「ターミナルで開く」

### Step 2: プロジェクトをクローン

```bash
git clone https://github.com/naruru-1225/sukima_park.git
```

**何が起きる?**
→ 「sukima_park」フォルダが作成され、プロジェクト全体がダウンロードされる

### Step 3: フォルダに移動

```bash
cd sukima_park
```

**解説**:
- `cd` = Change Directory（フォルダ移動）

### Step 4: パッケージをインストール

```bash
docker run --rm -v "${PWD}:/app" -w /app composer install
```

**何が起きる?**
→ Laravelが必要とするライブラリが「vendor」フォルダにダウンロードされる

（初回は5〜10分かかります）

### Step 5: 設定ファイルをコピー

```bash
copy .env.example .env
```

**解説**:
- `.env` = 環境設定ファイル（データベースのパスワードなど）
- `.env.example` = チームで共有するテンプレート

### Step 6: サーバーを起動

```bash
docker compose up -d
```

**何が起きる?**
→ あなたのPC上でWebサーバーとデータベースが起動する

### Step 7: アプリの初期設定

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

**解説**:
- `key:generate` = セキュリティ用の秘密キーを生成
- `migrate` = データベースにテーブルを作成

### Step 8: 動作確認

ブラウザで http://localhost にアクセス

**Laravelのロゴが表示されればセットアップ完了！** 🎉

---

## Laravelのフォルダ構成

```
sukima_park/
│
├── 📁 app/                    ★ アプリのメインコード
│   ├── 📁 Http/
│   │   ├── 📁 Controllers/    ★★★ 処理を書く場所
│   │   └── 📁 Middleware/     リクエストの前処理
│   ├── 📁 Models/             ★★★ データベースとの接続
│   └── 📁 Providers/          アプリの初期設定
│
├── 📁 bootstrap/              アプリ起動時の処理（触らない）
│
├── 📁 config/                 設定ファイル（触らない）
│
├── 📁 database/               ★ データベース関連
│   ├── 📁 migrations/         ★★★ テーブル定義
│   ├── 📁 factories/          テストデータ生成
│   └── 📁 seeders/            初期データ投入
│
├── 📁 public/                 公開ファイル
│   ├── index.php              アプリの入口
│   ├── 📁 css/                CSSファイル
│   └── 📁 js/                 JavaScriptファイル
│
├── 📁 resources/              ★ 画面関連
│   ├── 📁 views/              ★★★ HTMLテンプレート
│   ├── 📁 css/                ソースCSS
│   └── 📁 js/                 ソースJS
│
├── 📁 routes/                 ★ URL設定
│   └── web.php                ★★★ URLと処理の紐付け
│
├── 📁 storage/                ログ・キャッシュ（触らない）
│
├── 📁 tests/                  テストコード
│
├── 📁 vendor/                 外部ライブラリ（Gitにアップしない）
│
├── .env                       環境設定（Gitにアップしない）
├── .env.example               .envのテンプレート
├── composer.json              使用ライブラリ一覧
└── docker-compose.yml         Docker設定
```

### 開発で主に触るフォルダ

| フォルダ | 役割 | 何を書く？ |
|---------|------|-----------|
| `app/Http/Controllers/` | **コントローラー** | ユーザーのリクエストを処理 |
| `app/Models/` | **モデル** | データベースの操作 |
| `resources/views/` | **ビュー** | 画面のHTML |
| `routes/web.php` | **ルーティング** | URLと処理の対応 |
| `database/migrations/` | **マイグレーション** | テーブル定義 |

### MVCアーキテクチャ

```
ユーザー → URL → routes/web.php → Controller → Model → データベース
                                       ↓
                                     View → HTML → ユーザー
```

**例: 土地一覧を表示する流れ**
```
1. ユーザーが /lands にアクセス
2. web.php が LandController@index を呼ぶ
3. LandController が Land モデルでDBからデータ取得
4. lands/index.blade.php にデータを渡して表示
```

---

## よく使うGitコマンド

### 毎日使うコマンド

```bash
# 作業開始時: 最新のコードを取得
git pull

# 作業終了時: 変更をアップロード
git add .
git commit -m "変更内容のメッセージ"
git push
```

### コミットメッセージの書き方

```
✅ 良い例:
git commit -m "土地登録機能を追加"
git commit -m "ログイン画面のバリデーション修正"
git commit -m "会員テーブルにphone列を追加"

❌ 悪い例:
git commit -m "修正"
git commit -m "aaa"
git commit -m "作業完了"
```

### その他のよく使うコマンド

```bash
# 変更したファイルを確認
git status

# 変更内容を詳しく見る
git diff

# 変更を取り消す（コミット前）
git checkout -- ファイル名

# 直前のコミットを取り消す
git reset --soft HEAD~1
```

---

## よく使うDockerコマンド

```bash
# サーバー起動
docker compose up -d

# サーバー停止
docker compose down

# ログを見る
docker compose logs -f app

# Laravelコマンド実行
docker compose exec app php artisan [コマンド]

# MySQLに接続
docker compose exec mysql mysql -u sail -ppassword sukimapark
```

---

## トラブルシューティング

### 「port is already allocated」エラー

**原因**: ポート80が他のアプリに使われている

**解決方法**:
1. `.env`ファイルを開く
2. 以下を追加:
   ```
   APP_PORT=8080
   ```
3. `docker compose down` → `docker compose up -d`
4. http://localhost:8080 でアクセス

### 「変更が反映されない」

```bash
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan view:clear
```

### 「git pushできない」

**原因**: 他の人の変更を取り込んでいない

**解決方法**:
```bash
git pull
# コンフリクトがあれば解決
git push
```

### 「Dockerが起動しない」

1. Docker Desktopアプリを再起動
2. PCを再起動
3. タスクマネージャーでDockerプロセスを終了してから再起動

---

## 質問があるときは

分からないことがあったら、**まず調べる前にチームに聞いてください**。
同じ問題で悩んでいる人がいるかもしれません。

---

*最終更新: 2025-12-16*
