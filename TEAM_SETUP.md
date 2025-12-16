# スキマパーク チームセットアップガイド

このガイドは、**プログラミング初心者**でも迷わず環境構築ができるように詳しく書かれています。

> 🐵 **サルでもわかる**を目指して書いています！
>
> わからない単語があっても大丈夫。このドキュメントに全部書いてあります。
> 上から順番に読んでいけば、必ず理解できます。

---

## 目次

### 🔴 最初にやること（作業が必要）

1. [はじめに読んでほしいこと](#はじめに読んでほしいこと) - 📖 読むだけ
2. [Git（ギット）とは](#gitギットとは) - 📖 読むだけ
3. [Gitブランチの使い方](#gitブランチの使い方) - 📖 読むだけ
4. [Docker（ドッカー）とは](#dockerドッカーとは) - 📖 読むだけ
5. [必要なソフトのインストール](#必要なソフトのインストール) - 🔴 **作業必要**
6. [セットアップ手順](#セットアップ手順) - 🔴 **作業必要**

### 📖 開発中に参照するもの

7. [メンバー別の担当画面](#メンバー別の担当画面) - 自分の担当を確認
8. [毎日の作業の流れ](#毎日の作業の流れ) - 毎日の開始・終了手順
9. [Laravelのフォルダ構成](#laravelのフォルダ構成)
10. [PHP基礎文法](#php基礎文法)
11. [Laravelの書き方](#laravelの書き方)
12. [CRUDとは](#crudとは)
13. [共通レイアウトの使い方](#共通レイアウトの使い方)
14. [認証機能の使い方](#認証機能の使い方)
15. [データベーステーブル一覧](#データベーステーブル一覧)
16. [phpMyAdminの使い方](#phpmyadminの使い方)
17. [モデルの使い方](#モデルの使い方)
18. [よく使うコマンド](#よく使うコマンド)
19. [トラブルシューティング](#トラブルシューティング)
20. [初心者がよくやる間違いTOP10](#-初心者がよくやる間違いtop10)
21. [理解度チェッククイズ](#-理解度チェッククイズ)
22. [さらに学ぶために](#-さらに学ぶために)

### 📚 内容の分類について

このドキュメントには以下の種類の情報があります：

| マーク | 意味 | 説明 |
|-------|------|------|
| 🔴 **作業必要** | やること | 手順に従って実際に作業する必要がある |
| 📖 **読むだけ** | 参照用 | 理解のために読むだけでOK |
| 🔷 **Laravel** | Laravel標準機能 | どのLaravelプロジェクトでも使える知識 |
| 🟠 **プロジェクト固有** | スキマパーク専用 | このプロジェクトだけの設定 |

---

## 📖 はじめに読んでほしいこと

### このプロジェクトで使うツール

| ツール            | 何をするもの？              | 例えると...                          |
| ----------------- | --------------------------- | ------------------------------------ |
| **Git**     | コードの変更履歴を管理      | Wordの「変更履歴」機能               |
| **GitHub**  | Gitのデータをネット上に保存 | Google Driveのようなもの             |
| **Docker**  | 開発環境を作る              | パソコンの中に「仮想サーバー」を作る |
| **Laravel** | Webアプリを作るためのツール | 「Webアプリの骨組み」を提供          |

### GitHubアカウントの作成（必須）

> ⚠️ **このプロジェクトに参加するには、GitHubアカウントが必要です。**

#### Step 1: GitHubにアクセス

https://github.com/ にアクセス

#### Step 2: アカウント作成

1. 「Sign up」をクリック
2. メールアドレスを入力
3. パスワードを作成（15文字以上、または8文字以上で数字と小文字を含む）
4. ユーザー名を入力（英数字とハイフンのみ、例: `tanaka-taro`）
5. メール認証を完了

#### Step 3: チームリーダーにユーザー名を伝える

- チームリーダーがリポジトリの**コラボレーター**として追加します
- 追加されるとメールが届くので「Accept invitation」をクリック
- これでプロジェクトにpush/pullできるようになります

```
コラボレーター招待の流れ:
1. あなた: GitHubアカウント作成 → ユーザー名をリーダーに伝える
2. リーダー: Settings → Collaborators → Add people → あなたを追加
3. あなた: 招待メールを承認
4. 完了！プロジェクトにアクセスできます
```

### このプロジェクトの開発状況

> ✅ **以下は作成済みです（触らなくてOK）**
>
> - **マイグレーション**: データベースのテーブル定義（`database/migrations/`）
> - **モデル**: データベース操作用のクラス（`app/Models/`）
> - **Docker設定**: 開発環境の設定ファイル（`docker-compose.yml`）
>
> 🔧 **これから作成するもの**
>
> - **コントローラ**: 処理を書くファイル（`app/Http/Controllers/`）
> - **ビュー**: 画面のHTML（`resources/views/`）
> - **ルーティング**: URLと処理の紐付け（`routes/web.php`）

---

## 📖 Git（ギット）とは

### Gitが解決する問題

```
❌ Gitがない場合:
├── sukimapark_最新.zip
├── sukimapark_最新_修正.zip
├── sukimapark_最新_修正2_田中編集.zip
└── どれが最新か分からない...

✅ Gitがある場合:
├── 全ての変更履歴が記録される
├── 誰がいつ何を変更したか分かる
├── 間違えても前の状態に戻せる
└── チーム全員が同じコードで作業できる
```

### Gitの基本用語

| 用語                     | 意味                                   | 🎯 例えると                            |
| ------------------------ | -------------------------------------- | -------------------------------------- |
| **リポジトリ**     | プロジェクトのフォルダ（変更履歴込み） | 変更履歴付きのプロジェクトフォルダ     |
| **クローン**       | GitHubからコピーを作ること             | 図書館の本をコピーして持ち帰る         |
| **コミット**       | 変更を記録すること                     | ゲームの「セーブ」ボタン               |
| **プッシュ**       | 自分の変更をGitHubにアップロード       | クラウドに保存（Google Driveにアップ） |
| **プル**           | 他の人の変更をダウンロード             | クラウドから最新版を取得               |
| **ブランチ**       | 作業用の「枝」を作って安全に開発       | 下書き用のコピーを作る                 |
| **マージ**         | ブランチの変更を統合する               | 下書きを本番に反映                     |
| **プルリクエスト** | 変更をレビューしてもらう依頼           | 「これで合ってる？」と確認を依頼       |

> 💡 **イメージで覚えよう**
>
> **コミット** = ゲームでいうセーブ。失敗してもセーブポイントに戻れる！
>
> **プッシュ** = クラウドにバックアップ。PCが壊れても安心！
>
> **プル** = 仲間の作業を取り込む。みんなで同じデータを使える！

---

## 📖 Gitブランチの使い方

### ブランチとは？

```
ブランチ = 「作業用の枝」

main（本番）から枝分かれして作業し、完成したら戻す
```

> 💡 **例えると：レポートの下書き**
>
> 提出用レポート（main）を直接編集すると、失敗したときに大変。
> だから「下書きコピー」を作って、そこで作業。
> 完成したら提出用にコピーする。ブランチはこの「下書き」のこと！

### なぜブランチを使う？

```
❌ ブランチを使わない場合:
- 開発中のコードがmainに入り、他のメンバーに影響
- バグを入れてしまうとチーム全員が止まる
（※ 提出用レポートに直接落書きするようなもの）

✅ ブランチを使う場合:
- 自分専用の作業スペースで開発
- 完成してからmainに統合
- 他のメンバーに影響しない
（※ 下書きで作業して、完成したら清書するようなもの）
```

### ブランチの流れ（図解）

```
main ─────●─────────────────●─────────────── 本番
           \               /
            \─────●─────●─/ feature/login   機能開発用ブランチ
              作業   作業  マージ
```

### ブランチ名のルール

```
feature/機能名    → 新機能開発
  例: feature/login
  例: feature/land-register

fix/修正内容      → バグ修正
  例: fix/login-error

hotfix/緊急修正   → 本番の緊急修正
  例: hotfix/security-fix
```

### ブランチを使った開発の流れ

#### Step 1: 新しいブランチを作成

```bash
# mainブランチを最新に
git checkout main
git pull

# 新しいブランチを作成して移動
git checkout -b feature/login
```

#### Step 2: 開発作業

```bash
# ファイルを編集...

# 変更を確認
git status

# 変更をコミット
git add .
git commit -m "ログイン機能を実装"
```

#### Step 3: GitHubにプッシュ

```bash
git push -u origin feature/login
```

#### Step 4: プルリクエスト作成

1. GitHubでリポジトリを開く
2. 「Pull requests」タブをクリック
3. 「New pull request」をクリック
4. base: main ← compare: feature/login を選択
5. 「Create pull request」をクリック
6. 変更内容を説明して送信

#### Step 5: レビュー・マージ

1. チームメンバーがコードを確認
2. 問題なければ「Merge pull request」
3. mainにマージされる

#### Step 6: ブランチ削除・更新

```bash
# mainに戻る
git checkout main

# 最新を取得
git pull

# 使い終わったブランチを削除
git branch -d feature/login
```

---

## 📖 Docker（ドッカー）とは

### Dockerが解決する問題

```
❌ Dockerがない場合:
- 「私のPCでは動くのに、他の人のPCでは動かない」
- PHP、MySQL、Apacheなどを個別にインストールする必要がある
- バージョンの違いでエラーになる
- 設定が複雑で時間がかかる

✅ Dockerがある場合:
- 全員が同じ環境で開発できる
- コマンド一発で環境構築完了
- 「動かない」問題がほぼ発生しない
- PCを汚さない（アンインストールも簡単）
```

### Dockerの仕組み（図解）

```
┌─────────────────────────────────────────────────────────────┐
│                    あなたのPC（Windows）                     │
│  ┌───────────────────────────────────────────────────────┐  │
│  │                      Docker                           │  │
│  │  ┌───────────────┐ ┌───────────────┐ ┌─────────────┐ │  │
│  │  │  PHP + Laravel │ │    MySQL     │ │ phpMyAdmin  │ │  │
│  │  │  （Webサーバー）│ │（データベース）│ │（DB管理）   │ │  │
│  │  │   ポート:80     │ │  ポート:3306  │ │ポート:8080  │ │  │
│  │  └───────────────┘ └───────────────┘ └─────────────┘ │  │
│  │         ↑                ↑                ↑          │  │
│  │         └────────────────┼────────────────┘          │  │
│  │                          │                            │  │
│  │                   docker-compose.yml                  │  │
│  │                   （設計図）                           │  │
│  └───────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘

アクセス方法:
・http://localhost      → Webサイト（Laravel）
・http://localhost:8080 → phpMyAdmin
```

### Dockerの基本用語

| 用語                         | 意味                                         | 🎯 例えると                            |
| ---------------------------- | -------------------------------------------- | -------------------------------------- |
| **イメージ**           | 環境の「設計図」                             | 料理のレシピ                           |
| **コンテナ**           | イメージから作られた「実行中の環境」         | レシピから作った料理                   |
| **docker-compose.yml** | 複数のコンテナをまとめて管理する設定ファイル | フルコースのメニュー表                 |
| **ボリューム**         | データを永続化する仕組み                     | 冷蔵庫（コンテナを消しても中身は残る） |

> 💡 **イメージで覚えよう**
>
> Dockerは「仮想のパソコンをパソコンの中に作る」ようなもの。
>
> レストランで例えると：
>
> - **イメージ** = レシピ集（何を作るか書いてある）
> - **コンテナ** = 実際のキッチンと料理人（レシピを元に料理を作る）
> - **docker-compose.yml** = 「前菜、メイン、デザートを同時に準備して」という指示書

### よく使うDockerコマンド

```bash
# コンテナを起動（バックグラウンドで実行）
docker compose up -d
# ↑ -d は "detached"（バックグラウンド）の意味

# コンテナを停止
docker compose down

# コンテナの状態を確認
docker compose ps
# ↑ 全て「Up」ならOK

# コンテナを再起動（設定変更後などに使う）
docker compose restart

# コンテナのログを見る（エラー調査用）
docker compose logs

# コンテナ内でコマンドを実行
docker compose exec app php artisan migrate
# ↑ app = コンテナ名
# ↑ php artisan migrate = コンテナ内で実行するコマンド
```

### コンテナの状態確認

```bash
# このコマンドを実行
docker compose ps

# 正常な場合の表示例:
NAME           STATUS          PORTS
app            Up 5 minutes    0.0.0.0:80->80/tcp
mysql          Up 5 minutes    0.0.0.0:3306->3306/tcp
phpmyadmin     Up 5 minutes    0.0.0.0:8080->80/tcp

# ↑ 全て「Up」になっていればOK
# 「Exit」や「Restarting」があればエラー
```

### Dockerで問題が起きたときの対処

```bash
# 1. まずはコンテナを再起動
docker compose down
docker compose up -d

# 2. それでもダメならコンテナを作り直す
docker compose down --volumes  # データも含めて削除
docker compose up -d

# 3. ログを確認
docker compose logs app        # PHPコンテナのログ
docker compose logs mysql      # MySQLコンテナのログ
```

---

## 🟠 メンバー別の担当画面

各メンバーが担当する画面の一覧です。
詳細な実装方法は `docs/` フォルダ内の各自の実装ガイドを参照してください。

### 担当者サマリー

| 担当 | 画面数 | 主な担当領域 |
|-----|-------|-------------|
| A 小島 | 4画面 | 問い合わせ、検索結果、土地詳細、レンタル確認 |
| B 楠山 | 4画面 | 会員登録、ログイン、土地登録 |
| C 志賀 | 6画面 | トップ画面、ユーザ画面、土地貸出 |
| D 我妻 | 4画面 | プロフィール、DM |
| E 三輪 | 5画面 | レンタル管理、レビュー、取引完了 |
| F 野村 | 4画面 | 管理者機能 |

### A 小島さん担当

| 画面名 | ブランチ名 |
|------|----------|
| 問い合わせ画面 | feature/kojima-contact |
| 検索結果一覧画面 | feature/kojima-search-result |
| 土地詳細画面 | feature/kojima-land-detail |
| レンタル確認画面 | feature/kojima-rental-confirm |

### B 楠山さん担当

| 画面名 | ブランチ名 |
|------|----------|
| 会員登録画面 | feature/kusuyama-register |
| ログイン画面 | feature/kusuyama-login |
| 土地登録画面 | feature/kusuyama-land-register |
| 土地登録確認画面 | feature/kusuyama-land-confirm |

### C 志賀さん担当

| 画面名 | ブランチ名 |
|------|----------|
| ユーザ画面(自アカウント) | feature/shiga-user-self |
| ユーザ画面(他アカウント) | feature/shiga-user-other |
| トップ画面 | ✅ リーダー実装済み |
| 自己保持土地一覧画面 | feature/shiga-my-lands |
| 土地貸出画面 | feature/shiga-rental-lend |
| 貸出中詳細画面 | feature/shiga-lending-detail |

### D 我妻さん担当

| 画面名 | ブランチ名 |
|------|----------|
| プロフィール編集画面 | feature/azuma-profile-edit |
| プロフィール確認画面 | feature/azuma-profile-confirm |
| DM一覧画面 | feature/azuma-dm-list |
| DM画面 | feature/azuma-dm-chat |

### E 三輪さん担当

| 画面名 | ブランチ名 |
|------|----------|
| レンタル中一覧画面 | feature/miwa-rental-list |
| レンタル中詳細画面 | feature/miwa-rental-detail |
| レビュー画面 | feature/miwa-review |
| 取引完了一覧画面 | feature/miwa-completed-list |
| 取引完了詳細画面 | feature/miwa-completed-detail |

### F 野村さん担当（管理者機能）

| 画面名 | ブランチ名 |
|------|----------|
| ユーザ一覧画面 | feature/nomura-user-list |
| ユーザ詳細画面 | feature/nomura-user-detail |
| 問い合わせ一覧画面 | feature/nomura-contact-list |
| 問い合わせ詳細画面 | feature/nomura-contact-detail |

---

## 📖 毎日の作業の流れ

> **重要**: この章を読む前に、「Git（ギット）とは」「Gitブランチの使い方」「Docker（ドッカー）とは」を理解しておいてください。

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
#    phpMyAdmin: http://localhost:8080
```

**チェックリスト:**

- [ ] `git pull` した
- [ ] `docker compose up -d` した
- [ ] ブラウザで動作確認した

### 作業中（こまめにコミット）

```bash
# 変更を保存（こまめに行う）
git add .
git commit -m "変更内容を書く"

# 例
git commit -m "土地一覧画面を作成"
git commit -m "バグ修正: ログインできない問題"
```

**コミットメッセージの書き方:**

| 良い例 ✅                  | 悪い例 ❌ |
| -------------------------- | --------- |
| 土地一覧画面を作成         | 更新      |
| ログイン機能を追加         | 修正      |
| バグ修正: 日付表示のエラー | あ        |

### 作業終了時（必ず実行）

```bash
# 1. 変更をコミット（まだしていなければ）
git add .
git commit -m "作業内容"

# 2. GitHubにプッシュ
git push

# 3. Dockerコンテナを停止
docker compose down

# 4. WSLをシャットダウン（重要！環境が壊れる可能性を防ぐ）
wsl --shutdown
```

> ⚠️ **WSLシャットダウンの重要性**
>
> WSLを正しくシャットダウンしないと、Dockerの環境が壊れる可能性があります。
> 必ず `wsl --shutdown` を実行してからPCをシャットダウンしてください。

**チェックリスト:**

- [ ] 全ての変更をコミットした
- [ ] `git push` した
- [ ] `docker compose down` した
- [ ] `wsl --shutdown` した（PowerShellで実行）

### 作業の流れ（図解）

```
┌─────────────────────────────────────────────────────────────┐
│                      作業開始                               │
│  git pull → docker compose up -d → ブラウザで確認          │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│                      コーディング                           │
│  コード編集 → git add . → git commit -m "内容"             │
│              ↑                    ↓                        │
│              └────── 繰り返す ────┘                        │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│                      作業終了                               │
│  git push → docker compose down → wsl --shutdown           │
└─────────────────────────────────────────────────────────────┘
```

---

## 🔴 必要なソフトのインストール【作業必要】

> **インストール順序**: WSL2 → Docker Desktop → Git → VS Code の順でインストールしてください

### 1. WSL2（ダブリューエスエル2）のインストール ★重要★

**WSL2とは？**

```
WSL2 = Windows Subsystem for Linux 2
Windowsの中でLinux（サーバーのOS）を動かす機能

なぜ必要？
- DockerがLinux上で動くため、WSL2があると高速に動作
- WSL2なし → 遅い、メモリ使用量多い
- WSL2あり → 高速、安定、本来の性能
```

**インストール手順**:

#### Step 1: PowerShellを管理者として開く

1. Windowsキーを押す
2. 「PowerShell」と入力
3. 「管理者として実行」をクリック

#### Step 2: WSL2をインストール

```powershell
wsl --install
```

#### Step 3: PCを再起動

インストール完了後、PCを再起動してください。

#### Step 4: Ubuntuの初期設定

再起動後、自動的にUbuntuのウィンドウが開きます。

1. ユーザー名を入力（半角英字、例: `myname`）
2. パスワードを入力（2回）
3. 設定完了！

**確認方法**:

```powershell
# PowerShellで実行
wsl --list --verbose

# こう表示されればOK:
  NAME                   STATE           VERSION
* Ubuntu                 Running         2
  docker-desktop         Running         2
```

---

### 2. Docker Desktop のインストール

1. https://www.docker.com/products/docker-desktop/ にアクセス
2. 「Download for Windows」をクリック
3. ダウンロードしたファイルを実行
4. **「Use WSL 2 instead of Hyper-V」にチェック ✓** ← 重要！
5. インストール完了後、**PCを再起動**

**WSL2統合の確認**:

1. Docker Desktopを開く
2. 右上の歯車アイコン（Settings）をクリック
3. 左メニュー「Resources」→「WSL Integration」
4. 「Ubuntu」がONになっていることを確認

---

### 3. Git のインストール

1. https://git-scm.com/download/win にアクセス
2. 「Download for Windows」をクリック
3. ダウンロードしたファイルを実行
4. 全てデフォルトのまま「Next」を押し続ける
5. 「Install」をクリック

**Gitインストール後の設定（重要！）**:

```bash
# PowerShellまたはコマンドプロンプトで実行

# 自分の名前を設定（GitHubに表示される名前）
git config --global user.name "あなたの名前"

# 例
git config --global user.name "田中太郎"

# メールアドレスを設定（GitHubアカウントと同じにする）
git config --global user.email "あなたのメール@example.com"

# 例
git config --global user.email "tanaka@example.com"

# 設定の確認
git config --list
```

**なぜこの設定が必要？**

```
Gitは「誰が」「いつ」「何を」変更したか記録します。
この設定で「誰が」の部分が設定されます。

設定しないと：
- コミットするとき「Author identity unknown」エラーになる
- チームメンバーに誰が変更したかわからない
```

---

### 4. VS Code のインストール（推奨）

**VS Codeとは？**

```
VS Code = Visual Studio Code
Microsoft製の無料テキストエディタ

なぜVS Codeを使う？
・無料なのに高機能
・コードの色分け
・入力補完（途中まで打つと候補が出る）
・エラー表示
・Git連携
・拡張機能で機能追加できる
```

**インストール手順**:

1. https://code.visualstudio.com/ にアクセス
2. 「Download for Windows」をクリック
3. ダウンロードしたファイルを実行
4. ライセンスに同意
5. **「エクスプローラーのファイルコンテキストメニューに[Codeで開く]を追加」にチェック ✓**
6. **「エクスプローラーのディレクトリコンテキストメニューに[Codeで開く]を追加」にチェック ✓**
7. 「Install」をクリック

**日本語化**:

1. VS Codeを起動
2. 左側の四角いアイコン（Extensions）をクリック
3. 検索欄に「Japanese」と入力
4. 「Japanese Language Pack for Visual Studio Code」をインストール
5. VS Codeを再起動

**必須の拡張機能をインストール**:

| 拡張機能名             | 説明                    | 検索キーワード       |
| ---------------------- | ----------------------- | -------------------- |
| PHP Intelephense       | PHPの補完・エラー検出   | `PHP Intelephense` |
| Laravel Blade Snippets | Bladeテンプレートの補完 | `Laravel Blade`    |
| GitLens                | Gitの変更履歴を表示     | `GitLens`          |
| Docker                 | Dockerの管理            | `Docker`           |

**インストール方法**:

```
1. 左側の四角いアイコン（Extensions）をクリック
2. 検索欄に拡張機能名を入力
3. 「Install」をクリック
```

**VS Codeでプロジェクトを開く方法**:

```
方法1: エクスプローラーから
1. sukimaparkフォルダを右クリック
2. 「Codeで開く」を選択

方法2: VS Codeから
1. VS Codeを起動
2. 「ファイル」→「フォルダーを開く」
3. sukimaparkフォルダを選択

方法3: コマンドから
cd sukimapark
code .
```

**VS Codeの画面説明**:

```
┌────────────────────────────────────────────────────────────────┐
│ ファイル  編集  選択  表示  移動  実行  ターミナル  ヘルプ        │
├────┬───────────────────────────────────────────────────────────┤
│    │                                                           │
│ 📁 │   編集エリア（ここでコードを書く）                          │
│    │                                                           │
│ 🔍 │   ┌───────────────────────────────────────────────────┐  │
│    │   │ <?php                                             │  │
│ 🔀 │   │                                                   │  │
│    │   │ class LandController extends Controller           │  │
│ 🐛 │   │ {                                                 │  │
│    │   │     public function index()                       │  │
│ 📦 │   │     {                                             │  │
│    │   │         $lands = Land::all();                     │  │
│    │   │         return view('lands.index', ['lands' =>    │  │
│    │   │     }                                             │  │
│    │   │ }                                                 │  │
│    │   └───────────────────────────────────────────────────┘  │
├────┴───────────────────────────────────────────────────────────┤
│ ターミナル（コマンドを入力できる）                               │
│ PS C:\Users\xx\sukimapark> docker compose up -d                │
└────────────────────────────────────────────────────────────────┘

左側のアイコン:
📁 = エクスプローラー（ファイル一覧）
🔍 = 検索
🔀 = ソース管理（Git）
🐛 = デバッグ
📦 = 拡張機能
```

**VS Codeの便利なショートカット**:

| ショートカット       | 機能                 | よく使う度 |
| -------------------- | -------------------- | ---------- |
| `Ctrl + S`         | 保存                 | ★★★★★ |
| `Ctrl + Z`         | 元に戻す             | ★★★★★ |
| `Ctrl + Shift + Z` | やり直し             | ★★★★   |
| `Ctrl + F`         | ファイル内検索       | ★★★★★ |
| `Ctrl + Shift + F` | 全ファイル検索       | ★★★★   |
| `Ctrl + P`         | ファイルを素早く開く | ★★★★   |
| `Ctrl + /`         | コメント化           | ★★★★   |
| `Ctrl + D`         | 同じ単語を選択       | ★★★     |
| `Alt + ↑/↓`      | 行を上下に移動       | ★★★     |
| `Ctrl + `` `       | ターミナルを開く     | ★★★★★ |

---

## 🔴 セットアップ手順【作業必要】

```bash
# 1. クローン
git clone https://github.com/naruru-1225/sukima_park.git
cd sukima_park

# 2. パッケージインストール（5〜10分）
docker run --rm -v "${PWD}:/app" -w /app composer install

# 3. 設定ファイル作成
copy .env.example .env

# 4. サーバー起動
docker compose up -d

# 5. 初期設定
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

http://localhost でLaravelのロゴが表示されれば完了！

---

## 📖 Laravelのフォルダ構成

```
sukima_park/
├── 📁 app/Http/Controllers/    ★ 処理を書く場所
├── 📁 app/Models/              ★ データベース操作
├── 📁 database/migrations/     ★ テーブル定義
├── 📁 resources/views/         ★ 画面のHTML
├── 📁 routes/web.php           ★ URLと処理の紐付け
└── 📁 public/                  公開ファイル（CSS/JS/画像）
```

---

## 🔷 PHP基礎文法

Laravelのコードを読む前に、PHPの基本を理解しましょう。

### 変数

```php
<?php
// 変数は $ で始まる
$name = "田中";           // 文字列
$age = 25;                // 数値
$price = 1500.50;         // 小数
$isActive = true;         // 真偽値（true/false）

// 配列
$colors = ["赤", "青", "緑"];
$colors[0];  // → "赤"

// 連想配列（キーと値のペア）
$user = [
    "name" => "田中",
    "age" => 25,
    "email" => "tanaka@example.com"
];
$user["name"];  // → "田中"
```

### 関数

```php
<?php
// 関数の定義
function greet($name) {
    return "こんにちは、" . $name . "さん";
}

// 関数の呼び出し
$message = greet("田中");  // → "こんにちは、田中さん"

// 引数のデフォルト値
function greet($name = "ゲスト") {
    return "こんにちは、" . $name . "さん";
}
greet();        // → "こんにちは、ゲストさん"
greet("佐藤");  // → "こんにちは、佐藤さん"
```

### 条件分岐

```php
<?php
$age = 20;

// if文
if ($age >= 20) {
    echo "成人です";
} elseif ($age >= 18) {
    echo "18歳以上です";
} else {
    echo "未成年です";
}

// 比較演算子
// ==  : 等しい
// === : 型も含めて等しい
// !=  : 等しくない
// >   : より大きい
// <   : より小さい
// >=  : 以上
// <=  : 以下
```

### ループ

```php
<?php
// foreach（配列のループ）★よく使う
$users = ["田中", "佐藤", "鈴木"];
foreach ($users as $user) {
    echo $user;  // 田中、佐藤、鈴木の順に表示
}

// キーと値を取得
$prices = ["りんご" => 100, "みかん" => 80];
foreach ($prices as $fruit => $price) {
    echo $fruit . "は" . $price . "円";
}

// for文
for ($i = 0; $i < 5; $i++) {
    echo $i;  // 0, 1, 2, 3, 4
}
```

### クラスとオブジェクト

```php
<?php
// クラス = 設計図
class User {
    // プロパティ（変数）
    public $name;
    public $email;

    // コンストラクタ（初期化処理）
    public function __construct($name, $email) {
        $this->name = $name;
        $this->email = $email;
    }

    // メソッド（関数）
    public function greet() {
        return "こんにちは、" . $this->name . "です";
    }
}

// オブジェクト = 設計図から作った実体
$user = new User("田中", "tanaka@example.com");
echo $user->name;     // → "田中"
echo $user->greet();  // → "こんにちは、田中です"
```

### アロー演算子と記法

```php
<?php
// -> : オブジェクトのプロパティやメソッドにアクセス
$user->name;
$user->greet();

// => : 連想配列のキーと値を結ぶ
$array = ["key" => "value"];

// :: : クラスの静的メソッドにアクセス
User::all();          // Laravelでよく使う
LandController::class; // クラス名を文字列で取得

// \ : 名前空間の区切り
App\Models\User       // Appフォルダ内のModelsフォルダ内のUser
```

---

## 🔷 Laravelの書き方（開発順序）

> 機能を作る時は以下の順番で進めます：
> **1. マイグレーション → 2. モデル → 3. コントローラ → 4. ルーティング → 5. ビュー**

### 1. マイグレーション（database/migrations/）★最初に作成

**テーブル定義**

```bash
# マイグレーション作成
docker compose exec app php artisan make:migration create_lands_table
```

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // ─────────────────────────────────────
    // up(): マイグレーション実行時の処理
    // ─────────────────────────────────────
    public function up(): void
    {
        Schema::create('lands', function (Blueprint $table) {
        //    ↑ テーブル名    ↑ Blueprintでカラムを定義

            // ─── 主キー ───
            $table->id();
            // ↑ id という名前の自動連番カラム（BIGINT UNSIGNED AUTO_INCREMENT）

            // ─── 外部キー ───
            $table->foreignId('owner_id')
                  ->constrained('members')
                  ->onDelete('cascade');
            // ↑ foreignId = 外部キー用のカラム
            // ↑ constrained('members') = membersテーブルのidを参照
            // ↑ onDelete('cascade') = 親が削除されたら子も削除

            // ─── 文字列 ───
            $table->string('name', 50);
            // ↑ VARCHAR(50)

            $table->string('location');
            // ↑ VARCHAR(255) - 長さ省略時は255

            // ─── テキスト ───
            $table->text('description')->nullable();
            // ↑ TEXT型（長い文章用）
            // ↑ nullable() = NULL許可

            // ─── 数値 ───
            $table->integer('capacity');     // 整数
            $table->decimal('area', 10, 2);  // 小数（全体10桁、小数点以下2桁）
            $table->decimal('price', 10, 0)->default(0);
            // ↑ default(0) = デフォルト値

            // ─── 列挙型 ───
            $table->enum('status', ['available', 'rented', 'inactive'])
                  ->default('available');
            // ↑ 3つの値のいずれか

            // ─── 日付・時刻 ───
            $table->date('available_date');        // 日付のみ
            $table->datetime('start_at');          // 日付と時刻
            $table->timestamps();
            // ↑ created_at と updated_at を自動生成
        });
    }

    // ─────────────────────────────────────
    // down(): ロールバック時の処理
    // ─────────────────────────────────────
    public function down(): void
    {
        Schema::dropIfExists('lands');
        // ↑ テーブルが存在すれば削除
    }
};
```

```bash
# マイグレーション実行
docker compose exec app php artisan migrate

# ロールバック（1つ戻す）
docker compose exec app php artisan migrate:rollback

# 全部消してやり直し
docker compose exec app php artisan migrate:fresh
```

### 2. モデル（app/Models/）

**データベースとの接続**

```bash
# モデル作成コマンド（マイグレーションも一緒に作成）
docker compose exec app php artisan make:model Land -m
# ↑ -m オプションでマイグレーションファイルも同時作成
```

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Land extends Model
{
    // ─────────────────────────────────────
    // テーブル名（省略可能）
    // ─────────────────────────────────────
    protected $table = 'lands';
    // ↑ 省略した場合、クラス名の複数形（Land → lands）が使われる

    // ─────────────────────────────────────
    // 一括代入を許可するカラム
    // ─────────────────────────────────────
    protected $fillable = [
        'name',
        'location',
        'area',
        'description',
        'owner_id',
    ];
    // ↑ Land::create(['name' => '...']) で保存できるカラム
    // ↑ セキュリティのため、許可したカラムのみ保存可能

    // ─────────────────────────────────────
    // リレーション: この土地の所有者
    // ─────────────────────────────────────
    public function owner()
    {
        return $this->belongsTo(Member::class, 'owner_id');
        // ↑ belongsTo = 「〜に属する」（多対1）
        // ↑ この土地は1人のMemberに属する
        // ↑ 'owner_id' = 外部キーのカラム名
    }

    // ─────────────────────────────────────
    // リレーション: この土地のレンタル記録
    // ─────────────────────────────────────
    public function rentals()
    {
        return $this->hasMany(RentalRecord::class);
        // ↑ hasMany = 「複数持つ」（1対多）
        // ↑ この土地には複数のレンタル記録がある
    }
}
```

**よく使うクエリ（データベース操作）**:

```php
<?php
// ─────────────────────────────────────────
// 取得（SELECT）
// ─────────────────────────────────────────
$lands = Land::all();              // 全件取得
$land = Land::find(1);             // ID=1を取得
$land = Land::findOrFail(1);       // ID=1を取得（なければ404）

// 条件付き取得
$lands = Land::where('area', '>', 100)->get();
// ↑ WHERE area > 100 と同じ
// ↑ get() で結果を取得

$land = Land::where('name', '駅前スペース')->first();
// ↑ first() は1件だけ取得

// 複数条件
$lands = Land::where('area', '>', 100)
             ->where('status', 'available')
             ->orderBy('created_at', 'desc')
             ->get();
// ↑ orderBy で並び替え（desc = 降順、asc = 昇順）

// ─────────────────────────────────────────
// 作成（INSERT）
// ─────────────────────────────────────────
$land = Land::create([
    'name' => '新しい土地',
    'location' => '東京都渋谷区',
    'area' => 50.5,
]);
// ↑ 作成されたレコードが $land に入る

// ─────────────────────────────────────────
// 更新（UPDATE）
// ─────────────────────────────────────────
$land = Land::find(1);
$land->name = '更新後の名前';
$land->save();
// または
$land->update(['name' => '更新後の名前']);

// ─────────────────────────────────────────
// 削除（DELETE）
// ─────────────────────────────────────────
$land = Land::find(1);
$land->delete();
```

### 3. コントローラ（app/Http/Controllers/）

**処理を書く場所**

```bash
# コントローラ作成コマンド
docker compose exec app php artisan make:controller LandController
```

```php
<?php
// ─────────────────────────────────────────
// namespace: このファイルの場所を宣言
// ─────────────────────────────────────────
namespace App\Http\Controllers;
// ↑ このファイルは app/Http/Controllers/ にあることを示す

// ─────────────────────────────────────────
// use文: 使用するクラスを宣言
// ─────────────────────────────────────────
use App\Models\Land;           // Landモデル
use Illuminate\Http\Request;   // HTTPリクエスト情報

// ─────────────────────────────────────────
// クラス定義
// ─────────────────────────────────────────
class LandController extends Controller
//    ↑ クラス名      ↑ Controllerを継承（機能を引き継ぐ）
{
    // ─────────────────────────────────────
    // 一覧表示メソッド
    // ─────────────────────────────────────
    public function index()
    // ↑ public = どこからでも呼び出せる
    // ↑ function = 関数（メソッド）の定義
    {
        // データベースから全件取得
        $lands = Land::all();
        // ↑ Land::all() = landsテーブルの全レコードを取得
        // ↑ 結果は $lands に配列のような形で格納される

        // ビューにデータを渡して表示
        return view('lands.index', ['lands' => $lands]);
        // ↑ view('フォルダ.ファイル名', ['変数名' => 値])
        // ↑ resources/views/lands/index.blade.php を表示
        // ↑ ビュー内で $lands として使える
    }

    // ─────────────────────────────────────
    // 詳細表示メソッド
    // ─────────────────────────────────────
    public function show($id)
    // ↑ $id は URL /lands/{id} の {id} 部分が入る
    {
        // IDで1件取得
        $land = Land::find($id);
        // ↑ find(ID) = 主キーで検索して1件取得
        // ↑ 見つからない場合は null が返る

        // または見つからない場合に404エラーを出す
        $land = Land::findOrFail($id);
        // ↑ 見つからない場合は自動で404ページを表示

        return view('lands.show', ['land' => $land]);
    }

    // ─────────────────────────────────────
    // 登録フォーム表示メソッド
    // ─────────────────────────────────────
    public function create()
    {
        // フォームのHTMLを表示するだけ
        return view('lands.create');
    }

    // ─────────────────────────────────────
    // 登録処理メソッド
    // ─────────────────────────────────────
    public function store(Request $request)
    // ↑ Request $request = フォームから送信されたデータが入る
    {
        // バリデーション（入力チェック）
        $validated = $request->validate([
            'name' => 'required|max:50',
            // ↑ 必須（required）、最大50文字（max:50）
            'location' => 'required',
            'area' => 'required|numeric|min:1',
            // ↑ 必須、数値、1以上
        ]);
        // ↑ バリデーション失敗時は自動でフォームに戻る

        // データベースに保存
        Land::create([
            'name' => $request->name,
            // ↑ $request->name = フォームの name="name" の値
            'location' => $request->location,
            'area' => $request->area,
            'owner_id' => auth()->id(),
            // ↑ auth()->id() = ログイン中のユーザーのID
        ]);

        // リダイレクト（別ページに移動）
        return redirect('/lands')->with('success', '登録しました');
        // ↑ redirect('URL') = 指定URLに移動
        // ↑ with('キー', '値') = 次のページでセッションメッセージを表示
    }
}
```

> 💡 **コントローラの役割を例えると**
>
> コントローラは「司令塔」や「交通整理」のようなもの。
>
> - ユーザーからのリクエスト（URLアクセス）を受け取る
> - 必要なデータをモデルから取得する
> - ビューにデータを渡して画面を表示する

---

### 4. ルーティング（routes/web.php）

**URLと処理を紐付ける設定ファイル**

```php
<?php
// ─────────────────────────────────────────
// use文: 他のファイルのクラスを使う宣言
// ─────────────────────────────────────────
use App\Http\Controllers\LandController;
// ↑ App/Http/Controllers/LandController.php を使う

// ─────────────────────────────────────────
// Route::get() - GETリクエストを処理
// ─────────────────────────────────────────
Route::get('/lands', [LandController::class, 'index']);
//         ↑ URL     ↑ コントローラ         ↑ メソッド名
// 
// 意味: /lands にアクセスしたら LandController の index メソッドを実行

// ─────────────────────────────────────────
// URLパラメータ: {変数名} で受け取る
// ─────────────────────────────────────────
Route::get('/lands/{id}', [LandController::class, 'show']);
// /lands/1 → $id = 1
// /lands/5 → $id = 5

// ─────────────────────────────────────────
// Route::post() - POSTリクエスト（フォーム送信）
// ─────────────────────────────────────────
Route::post('/lands', [LandController::class, 'store']);
// フォームの action="/lands" method="POST" で送信されたら実行

// ─────────────────────────────────────────
// リソースルート: CRUD操作を一括定義
// ─────────────────────────────────────────
Route::resource('lands', LandController::class);
// ↑ これ1行で以下の7つのルートが自動生成される:
//
// GET    /lands           → index()   一覧表示
// GET    /lands/create    → create()  登録フォーム表示
// POST   /lands           → store()   登録処理
// GET    /lands/{id}      → show()    詳細表示
// GET    /lands/{id}/edit → edit()    編集フォーム表示
// PUT    /lands/{id}      → update()  更新処理
// DELETE /lands/{id}      → destroy() 削除処理
```

> 💡 **ルーティングを例えると**
>
> ルーティングは「電話の内線番号表」のようなもの。
>
> - URL（電話番号）→ コントローラのメソッド（担当者）
> - `/lands` にかかってきた電話は `LandController` の `index` さんが対応

---

### 5. ビュー（resources/views/）

**画面のHTML（Bladeテンプレート）**

Bladeはlaravelの**テンプレートエンジン**です。HTMLの中にPHPを書きやすくします。

**resources/views/lands/index.blade.php**:

```html
<!DOCTYPE html>
<html>
<head>
    <title>土地一覧</title>
</head>
<body>
    <h1>土地一覧</h1>

    {{-- ─── コメント（HTMLに出力されない） ─── --}}

    {{-- ─── 変数の表示 ─── --}}
    {{ $変数名 }}
    {{-- ↑ HTMLエスケープ済み（XSS攻撃対策） --}}
    {{-- ↑ < は &lt; に変換される --}}

    {!! $html変数 !!}
    {{-- ↑ HTMLをそのまま出力（注意して使う） --}}

    {{-- ─── 条件分岐 ─── --}}
    @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    @if($lands->isEmpty())
        <p>土地が登録されていません</p>
    @else
        <p>{{ $lands->count() }}件の土地があります</p>
    @endif

    {{-- ─── ループ ─── --}}
    @foreach($lands as $land)
        <div class="land-card">
            <h2>{{ $land->name }}</h2>
            <p>場所: {{ $land->location }}</p>
            <p>面積: {{ $land->area }}㎡</p>
            <a href="/lands/{{ $land->id }}">詳細を見る</a>
        </div>
    @endforeach

    {{-- ─── 空の場合の処理 ─── --}}
    @forelse($lands as $land)
        <div>{{ $land->name }}</div>
    @empty
        <p>データがありません</p>
    @endforelse

    {{-- ─── レイアウト継承 ─── --}}
    {{-- layouts/app.blade.php を継承する場合 --}}
</body>
</html>
```

**レイアウトの継承**:

**resources/views/layouts/app.blade.php**（共通レイアウト）:

```html
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title') - スキマパーク</title>
    {{-- ↑ @yield = 子テンプレートから挿入される場所 --}}
</head>
<body>
    <header>
        <nav>ナビゲーション</nav>
    </header>

    <main>
        @yield('content')
        {{-- ↑ ここに各ページのコンテンツが入る --}}
    </main>

    <footer>フッター</footer>
</body>
</html>
```

**resources/views/lands/index.blade.php**（子テンプレート）:

```html
@extends('layouts.app')
{{-- ↑ layouts/app.blade.php を継承 --}}

@section('title', '土地一覧')
{{-- ↑ @yield('title') に '土地一覧' を挿入 --}}

@section('content')
{{-- ↑ @yield('content') に以下を挿入 --}}
    <h1>土地一覧</h1>
    @foreach($lands as $land)
        <div>{{ $land->name }}</div>
    @endforeach
@endsection
```

---

## 🔷 CRUDとは

**CRUD**は、データベース操作の4つの基本機能の頭文字です。

```
┌─────────────────────────────────────────────────────────────┐
│                         CRUD                                 │
├──────────┬──────────────────────────────────────────────────┤
│ C = Create │ 作成する（新しいデータを追加）                    │
│ R = Read   │ 読み取る（データを表示）                          │
│ U = Update │ 更新する（既存データを変更）                      │
│ D = Delete │ 削除する（データを消す）                          │
└──────────┴──────────────────────────────────────────────────┘
```

### CRUDとLaravelの対応表

| CRUD             | 意味 | HTTP      | URL例            | コントローラメソッド | SQL    |
| ---------------- | ---- | --------- | ---------------- | -------------------- | ------ |
| **C**reate | 作成 | POST      | /lands           | store()              | INSERT |
| **R**ead   | 読取 | GET       | /lands, /lands/1 | index(), show()      | SELECT |
| **U**pdate | 更新 | PUT/PATCH | /lands/1         | update()             | UPDATE |
| **D**elete | 削除 | DELETE    | /lands/1         | destroy()            | DELETE |

### 具体例：土地（Land）のCRUD

```
┌─────────────────────────────────────────────────────────────┐
│ 【Create】新しい土地を登録する                               │
│                                                             │
│  ユーザー → フォーム入力 → store() → データベースにINSERT    │
├─────────────────────────────────────────────────────────────┤
│ 【Read】土地の一覧・詳細を見る                               │
│                                                             │
│  ユーザー → URL /lands → index() → 一覧を表示               │
│  ユーザー → URL /lands/1 → show() → ID=1の詳細を表示        │
├─────────────────────────────────────────────────────────────┤
│ 【Update】土地の情報を更新する                               │
│                                                             │
│  ユーザー → 編集フォーム → update() → データベースをUPDATE   │
├─────────────────────────────────────────────────────────────┤
│ 【Delete】土地を削除する                                     │
│                                                             │
│  ユーザー → 削除ボタン → destroy() → データベースからDELETE  │
└─────────────────────────────────────────────────────────────┘
```

> 💡 **CRUDを例えると**
>
> Excelの表で考えると分かりやすい：
>
> - **Create** = 新しい行を追加
> - **Read** = 表を見る
> - **Update** = セルの値を書き換える
> - **Delete** = 行を削除

---

## 🟠 共通レイアウトの使い方

このプロジェクトでは、共通のヘッダー・フッター・CSSが用意されています。
新しい画面を作るときは、このレイアウトを使ってください。

### ファイル構成

```
resources/views/
├── layouts/
│   ├── app.blade.php      ← メインレイアウト
│   ├── header.blade.php   ← ヘッダー（ナビゲーション）
│   └── footer.blade.php   ← フッター
│
└── lands/                  ← 各機能のフォルダ
    ├── index.blade.php
    └── show.blade.php

public/css/
└── app.css                 ← 共通CSS
```

### 基本的な使い方

新しい画面を作るときは、以下のテンプレートを使います：

```html
{{-- 共通レイアウトを使う宣言 --}}
@extends('layouts.app')

{{-- ページタイトル（ブラウザのタブに表示） --}}
@section('title', '土地一覧')

{{-- メインコンテンツ --}}
@section('content')
    <h1>土地一覧</h1>
    
    {{-- ここに画面の内容を書く --}}
    <p>ようこそ！</p>
@endsection
```

### 実際の例：土地一覧画面

**resources/views/lands/index.blade.php**:

```html
@extends('layouts.app')

@section('title', '土地一覧')

@section('content')
    <h1>土地を探す</h1>
    
    {{-- 検索フォーム --}}
    <form action="{{ url('/lands') }}" method="GET" class="card">
        <div class="card-body">
            <div class="form-group">
                <label class="form-label">都道府県</label>
                <select name="prefecture" class="form-select">
                    <option value="">すべて</option>
                    <option value="12">東京都</option>
                    {{-- ... --}}
                </select>
            </div>
            <button type="submit" class="btn btn-primary">検索</button>
        </div>
    </form>
    
    {{-- 土地一覧 --}}
    @foreach ($lands as $land)
        <div class="card">
            <div class="card-body">
                <h2>{{ $land->CITY }}</h2>
                <p>面積: {{ $land->AREA }}㎡</p>
                <a href="{{ url('/lands/'.$land->LAND_ID) }}" class="btn btn-outline">
                    詳細を見る
                </a>
            </div>
        </div>
    @endforeach
@endsection
```

### UIコンポーネント一覧

このプロジェクトで使える共通UIコンポーネントです。
統一されたデザインのために、必ずこれらのクラスを使ってください。

---

#### 1. ボタン

**基本のボタン**:

```html
{{-- 緑のメインボタン（送信、登録など） --}}
<button class="btn btn-primary">登録する</button>

{{-- グレーのサブボタン（キャンセルなど） --}}
<button class="btn btn-secondary">キャンセル</button>

{{-- 枠線のみのボタン（詳細を見るなど） --}}
<a href="/lands/1" class="btn btn-outline">詳細を見る</a>
```

| クラス | 見た目 | 使いどころ |
|-------|-------|----------|
| `.btn .btn-primary` | 緑背景・白文字 | 送信、登録、確定 |
| `.btn .btn-secondary` | グレー背景 | キャンセル、戻る |
| `.btn .btn-outline` | 緑枠線・透明背景 | 詳細を見る、お気に入り |

---

#### 2. カード

**情報をまとめて表示するボックス**:

```html
<div class="card">
    {{-- カードのヘッダー（タイトル部分） --}}
    <div class="card-header">
        <h3>土地情報</h3>
    </div>
    
    {{-- カードの本体（メインコンテンツ） --}}
    <div class="card-body">
        <p>新宿区の土地です。</p>
        <p>面積: 100㎡</p>
    </div>
    
    {{-- カードのフッター（ボタンなど） --}}
    <div class="card-footer">
        <a href="/lands/1" class="btn btn-primary">詳細を見る</a>
    </div>
</div>
```

| クラス | 説明 |
|-------|------|
| `.card` | カード全体を囲む |
| `.card-header` | タイトル部分（任意） |
| `.card-body` | メインコンテンツ |
| `.card-footer` | 下部のボタン配置（任意） |

---

#### 3. フォーム

**入力フォームの作り方**:

```html
<form action="{{ url('/lands') }}" method="POST">
    @csrf  {{-- セキュリティ用。必ず入れる！ --}}
    
    {{-- テキスト入力 --}}
    <div class="form-group">
        <label class="form-label required">市区町村</label>
        <input type="text" name="city" class="form-input" required>
    </div>
    
    {{-- セレクトボックス --}}
    <div class="form-group">
        <label class="form-label">都道府県</label>
        <select name="prefecture" class="form-select">
            <option value="">選択してください</option>
            <option value="12">東京都</option>
            <option value="27">大阪府</option>
        </select>
    </div>
    
    {{-- テキストエリア --}}
    <div class="form-group">
        <label class="form-label">説明（任意）</label>
        <textarea name="description" class="form-textarea" placeholder="土地の説明"></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">登録する</button>
</form>
```

| クラス | 用途 |
|-------|------|
| `.form-group` | 入力項目をグループ化 |
| `.form-label` | ラベル |
| `.form-label.required` | 必須マーク（*）付きラベル |
| `.form-input` | テキスト入力欄 |
| `.form-select` | セレクトボックス |
| `.form-textarea` | 複数行テキスト入力 |

---

#### 4. アラート（メッセージ）

**成功・エラーメッセージの表示**:

```html
{{-- 成功メッセージ（緑） --}}
<div class="alert alert-success">
    登録が完了しました！
</div>

{{-- エラーメッセージ（赤） --}}
<div class="alert alert-error">
    入力内容に誤りがあります。
</div>
```

> 💡 **自動表示**: `app.blade.php` でセッションメッセージを自動表示しています。
> コントローラで `return redirect()->with('success', 'メッセージ');` を使えばOK。

---

#### 5. コンテナ

**ページのコンテンツを中央寄せ**:

```html
<div class="container">
    {{-- この中は最大幅1200pxで中央に配置される --}}
    <h1>ページタイトル</h1>
    <p>コンテンツ...</p>
</div>
```

---

### カラーパレット（CSS変数）

CSSで使える色の変数です。統一感のあるデザインのために活用してください。

| 変数名 | 色 | 用途 |
|-------|-----|------|
| `var(--primary)` | 緑 #2E7D32 | メインカラー |
| `var(--primary-dark)` | 濃緑 #1B5E20 | ホバー時など |
| `var(--text-dark)` | 黒 #212121 | 本文テキスト |
| `var(--text-gray)` | グレー #616161 | サブテキスト |
| `var(--bg-white)` | 白 #FFFFFF | 背景 |
| `var(--bg-light)` | 薄グレー #F5F5F5 | 背景（グレー） |
| `var(--border)` | グレー #E0E0E0 | 枠線 |
| `var(--error)` | 赤 #F44336 | エラー |
| `var(--success)` | 緑 #4CAF50 | 成功 |

> 💡 **ポイント**
>
> - `@extends('layouts.app')` で共通レイアウトを使う
> - `@section('content')` ～ `@endsection` の間にコンテンツを書く
> - フォームには必ず `@csrf` を入れる（セキュリティ対策）
> - 用意されたCSSクラスを使うと統一感が出る

---

## 🔷 認証機能の使い方

このプロジェクトでは、ログイン状態の確認機能が用意されています。
各画面でユーザーのログイン状態に応じた表示切り替えに使ってください。

### ビュー（Blade）での使い方

```html
{{-- ログイン済みかどうかで表示を切り替え --}}
@auth
    {{-- ログイン済みの場合のみ表示 --}}
    <p>こんにちは、{{ Auth::user()->USERNAME }}さん！</p>
    <a href="{{ url('/lands/create') }}">土地を登録する</a>
@endauth

@guest
    {{-- 未ログインの場合のみ表示 --}}
    <p>ログインしてください</p>
    <a href="{{ url('/login') }}">ログイン</a>
@endguest
```

### ログインユーザーの情報を取得

```php
// ビュー（Blade）で使う場合
{{ Auth::user()->USERNAME }}    // ユーザー名
{{ Auth::user()->EMAIL }}       // メールアドレス
{{ Auth::user()->USER_ID }}     // ユーザーID
{{ Auth::user()->ICON_IMAGE }}  // アイコン画像

// ログインしているかチェック
@if(Auth::check())
    ログイン中
@endif
```

### コントローラでの使い方

```php
use Illuminate\Support\Facades\Auth;

class LandController extends Controller
{
    public function store(Request $request)
    {
        // ログインユーザーのIDを取得
        $userId = Auth::id();
        
        // ログインユーザーの情報を取得
        $user = Auth::user();
        $username = $user->USERNAME;
        
        // ログインしているかチェック
        if (Auth::check()) {
            // ログイン済みの処理
        }
        
        // 土地を登録（ログインユーザーのIDを設定）
        Land::create([
            'USER_ID' => Auth::id(),
            'CITY' => $request->city,
            // ...
        ]);
    }
}
```

### ログイン必須のページを作る（ミドルウェア）

```php
// routes/web.php

// authミドルウェアを使うと、ログインしていないとアクセスできない
Route::middleware('auth')->group(function () {
    // この中のルートはログイン必須
    Route::get('/my-lands', [LandController::class, 'myLands']);
    Route::get('/lands/create', [LandController::class, 'create']);
    Route::post('/lands', [LandController::class, 'store']);
});

// 個別に設定する場合
Route::get('/profile', [ProfileController::class, 'show'])->middleware('auth');
```

### よく使うパターン

| やりたいこと | コード |
|-------------|-------|
| ログイン中か確認 | `Auth::check()` または `@auth` |
| ユーザーID取得 | `Auth::id()` |
| ユーザー名取得 | `Auth::user()->USERNAME` |
| ログイン必須ページ | `->middleware('auth')` |
| ログイン時のみ表示 | `@auth ... @endauth` |
| 未ログイン時のみ表示 | `@guest ... @endguest` |

> ⚠️ **注意**
>
> - `Auth::user()` は未ログイン時に `null` を返します
> - ビューで使う場合は `@auth` で囲むと安全です
> - コントローラでは `Auth::check()` でチェックするか、`middleware('auth')` を使ってください

---

## 🟠 データベーステーブル一覧

このプロジェクトで使用するテーブルの一覧です。

### テーブル構成図

```
┌─────────────────────────────────────────────────────────────────┐
│                      スキマパーク DB構成                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│   MEMBER_TABLE ←──┬──────────────────────────────────────┐     │
│   （会員）         │                                      │     │
│        │          │                                      │     │
│        ▼          │                                      │     │
│   LAND_TABLE      │                                      │     │
│   （土地）         │                                      │     │
│        │          │                                      │     │
│        ├──────────┼───→ RENTAL_RECORD_TABLE             │     │
│        │          │      （貸し出し記録）                  │     │
│        │          │            │                         │     │
│        │          │            ▼                         │     │
│        │          │      REVIEW_COMMENT_TABLE            │     │
│        │          │      （レビュー・コメント）            │     │
│        │          │                                      │     │
│        │          └───→ CONTACT_TABLE                   │     │
│        │                 （問い合わせ）                    │     │
│        │                       │                         │     │
│        │                       ▼                         │     │
│        │                 REPLY_TABLE                     │     │
│        │                 （返信）                         │     │
│        │                                                 │     │
│        └─────────────────→ CHAT_TABLE                   │     │
│                              （連絡）                     │     │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 1. 会員テーブル（MEMBER_TABLE）

| カラム名          | 型                   | 説明                                   |
| ----------------- | -------------------- | -------------------------------------- |
| USER_ID           | INT (AUTO_INCREMENT) | 会員ID（主キー）                       |
| EMAIL             | VARCHAR(1024)        | メールアドレス                         |
| PASSWORD          | VARCHAR(64)          | パスワード（英数混合8〜20文字）        |
| TEL               | VARCHAR(64)          | 電話番号（XXX-XXXX-XXXX）              |
| BIRTH             | DATE                 | 生年月日（YYYY/MM/DD）                 |
| SHOW_BIRTH        | BOOLEAN              | 生年月日の公開設定                     |
| GENDER            | INT                  | 性別（0:男性, 1:女性, 2:その他）       |
| SHOW_GENDER       | BOOLEAN              | 性別の公開設定                         |
| IDENTITY          | VARCHAR(1024)        | 本人確認書類（画像パス）               |
| USERNAME          | VARCHAR(128)         | ユーザ名（32文字以内）                 |
| SELF_INTRODUCTION | VARCHAR(512)         | 自己紹介（140字以内、NULL可）          |
| ICON_IMAGE        | VARCHAR(1024)        | アイコン画像パス                       |
| ACCOUNT_STATUS    | INT                  | アカウント状態（0:ユーザ, 1:凍結, 2:管理者） |

### 2. 土地テーブル（LAND_TABLE）

| カラム名          | 型                   | 説明                                     |
| ----------------- | -------------------- | ---------------------------------------- |
| LAND_ID           | INT (AUTO_INCREMENT) | 土地ID（主キー）                         |
| PEREFECTURES      | INT                  | 都道府県（0:北海道〜46:沖縄）            |
| CITY              | VARCHAR(256)         | 市区町村（50字制限）                     |
| STREET_ADDRESS    | VARCHAR(256)         | 番地（50字制限）                         |
| AREA              | DECIMAL(5,2)         | 面積                                     |
| IMAGE             | VARCHAR(2048)        | 写真（画像パス、NULL可）                 |
| TITLE_DEED        | VARCHAR(2048)        | 権利書（画像URL）                        |
| DESCRIPTION       | VARCHAR(4096)        | 説明（1200文字以下、NULL可）             |
| RENTAL_START_DATE | DATE                 | 貸し出し受付開始日（NULL可）             |
| RENTAL_END_DATE   | DATE                 | 貸し出し受付終了日（NULL可）             |
| RENTAL_START_TIME | TIME                 | 貸し出し受付開始時間（NULL可）           |
| RENTAL_END_TIME   | TIME                 | 貸し出し受付終了時間（NULL可）           |
| PRICE             | INT                  | 単価                                     |
| PRICE_UNIT        | INT                  | 単価単位（0:日, 1:時間, 2:15分）         |
| USER_ID           | INT                  | 所有者会員ID（外部キー）                 |
| STATUS            | BOOLEAN              | ステータス（0:非公開, 1:公開中）         |

### 3. 貸し出し記録テーブル（RENTAL_RECORD_TABLE）

| カラム名          | 型                   | 説明                             |
| ----------------- | -------------------- | -------------------------------- |
| RECORD_ID         | INT (AUTO_INCREMENT) | 記録ID（主キー）                 |
| PRICE             | INT                  | 単価                             |
| PRICE_UNIT        | INT                  | 単価単位（0:日, 1:時間, 2:15分） |
| RENTAL_START_DATE | DATE                 | 開始日                           |
| RENTAL_END_DATE   | DATE                 | 終了日                           |
| RENTAL_START_TIME | TIME                 | 開始時間                         |
| RENTAL_END_TIME   | TIME                 | 終了時間                         |
| LAND_ID           | INT                  | 土地ID（外部キー）               |
| USER_ID           | INT                  | 会員ID（外部キー）               |

### 4. レビュー・コメントテーブル（REVIEW_COMMENT_TABLE）

| カラム名          | 型                   | 説明                      |
| ----------------- | -------------------- | ------------------------- |
| REVIEW_COMMENT_ID | INT (AUTO_INCREMENT) | ID（主キー）              |
| LAND_REVIEW       | INT                  | 土地レビュー（星1〜5）    |
| LAND_COMMENT      | VARCHAR(512)         | 土地コメント（150文字）   |
| USER_REVIEW       | INT                  | ユーザレビュー（星1〜5）  |
| USER_COMMENT      | VARCHAR(512)         | ユーザコメント（150文字） |
| DATE              | DATE                 | 日付                      |
| USER_ID           | INT                  | 会員ID（外部キー）        |
| LAND_ID           | INT                  | 土地ID（外部キー）        |
| RECORD_ID         | INT                  | 記録ID（外部キー）        |

### 5. 問い合わせテーブル（CONTACT_TABLE）

| カラム名   | 型                   | 説明                                         |
| ---------- | -------------------- | -------------------------------------------- |
| CONTACT_ID | INT (AUTO_INCREMENT) | 問い合わせID（主キー）                       |
| TITLE      | VARCHAR(128)         | 主題（40字以下）                             |
| MESSAGE    | VARCHAR(1024)        | 本文（300字以下）                            |
| USER_ID    | INT                  | 会員ID（外部キー）                           |
| DATE       | DATE                 | 日付                                         |
| STATUS     | INT                  | ステータス（0:未対応, 1:対応中, 2:対応済み） |

### 6. 返信テーブル（REPLY_TABLE）

| カラム名   | 型                   | 説明                      |
| ---------- | -------------------- | ------------------------- |
| REPLY_ID   | INT (AUTO_INCREMENT) | 返信ID（主キー）          |
| CONTACT_ID | INT                  | 問い合わせID（外部キー）  |
| USER_ID    | INT                  | 会員ID（外部キー）        |
| MESSAGE    | VARCHAR(1024)        | メッセージ（最大300文字） |
| DATE       | DATE                 | 日付                      |

### 7. 連絡テーブル（CHAT_TABLE）

| カラム名     | 型                   | 説明                     |
| ------------ | -------------------- | ------------------------ |
| CHAT_ID      | INT (AUTO_INCREMENT) | 連絡ID（主キー）         |
| USER_ID_FROM | INT                  | 連絡元会員ID（外部キー） |
| USER_ID_TO   | INT                  | 連絡先会員ID（外部キー） |
| MESSAGE      | VARCHAR(512)         | メッセージ（120字以内）  |
| IMAGE        | VARCHAR(2048)        | 画像URL（任意）          |
| YEAR         | DATE                 | 西暦                     |
| DATE         | DATE                 | 日付                     |
| TIME         | TIME                 | 時間                     |

> ⚠️ **注意**: これらのテーブルは既にマイグレーションで定義済みです。
> `database/migrations/` フォルダ内のファイルで確認できます。



## 📖 phpMyAdminの使い方

phpMyAdminはブラウザでデータベースを管理できるツールです。

### アクセス方法

**URL**: http://localhost:8080

（自動ログイン設定済み。ユーザー: sail / パスワード: password）

### 主な機能

| 機能         | 説明                                 |
| ------------ | ------------------------------------ |
| テーブル一覧 | 左メニューでsukimaparkをクリック     |
| データ参照   | テーブル名をクリック → 「表示」タブ |
| データ挿入   | テーブル名をクリック → 「挿入」タブ |
| SQL実行      | 「SQL」タブでクエリを直接実行        |
| エクスポート | 「エクスポート」タブでバックアップ   |

### データベーステーブル一覧

| テーブル名      | 説明           | 主なカラム                                           |
| --------------- | -------------- | ---------------------------------------------------- |
| members         | 会員情報       | id, email, username, tel, birth, gender              |
| lands           | 土地情報       | id, prefectures, city, street_address, area, user_id |
| rental_records  | 貸し出し記録   | id, price, rental_start_date, land_id, user_id       |
| review_comments | レビュー       | id, land_review, user_review, record_id              |
| contacts        | 問い合わせ     | id, title, message, status, user_id                  |
| replies         | 問い合わせ返信 | id, contact_id, message, user_id                     |
| chats           | DM/チャット    | id, user_id_from, user_id_to, message                |

### テーブルの関係（ER図）

```
members (会員)
    │
    ├──< lands (土地)        ← user_id で紐付け
    │       │
    │       └──< rental_records (貸出記録) ← land_id で紐付け
    │               │
    │               └──< review_comments (レビュー) ← record_id で紐付け
    │
    ├──< contacts (問い合わせ) ← user_id で紐付け
    │       │
    │       └──< replies (返信) ← contact_id で紐付け
    │
    └──< chats (DM) ← user_id_from, user_id_to で紐付け
```

---

## 📖 モデルの使い方

### モデルとは？

**モデル** = **データベース操作を簡単にするツール**

```
❌ モデルなし（SQL直書き）
$results = DB::select('SELECT * FROM members WHERE gender = 0');

✅ モデルあり（シンプルで読みやすい）
$members = Member::where('gender', 0)->get();
```

このプロジェクトでは7つのモデルが用意されています。
コントローラで `use App\Models\モデル名;` を書くだけで使えます。

### 作成済みモデル一覧

| モデル名      | テーブル        | 説明       | 取得できる関連データ                                     |
| ------------- | --------------- | ---------- | -------------------------------------------------------- |
| Member        | members         | 会員情報   | lands, rentalRecords, contacts, sentChats, receivedChats |
| Land          | lands           | 土地情報   | owner, rentalRecords, reviews                            |
| RentalRecord  | rental_records  | 貸出記録   | land, renter, review                                     |
| ReviewComment | review_comments | レビュー   | reviewer, land, rentalRecord                             |
| Contact       | contacts        | 問い合わせ | sender, replies                                          |
| Reply         | replies         | 返信       | contact, sender                                          |
| Chat          | chats           | DM         | sender, receiver                                         |

### クエリメソッド一覧

モデルを使ってデータベースを操作するためのメソッドです。

#### データ取得メソッド

| メソッド           | 説明                          | 戻り値             |
| ------------------ | ----------------------------- | ------------------ |
| `all()`          | 全件取得                      | Collection（複数） |
| `find(ID)`       | IDで1件取得                   | Model or null      |
| `findOrFail(ID)` | IDで1件取得（なければエラー） | Model              |
| `first()`        | 最初の1件取得                 | Model or null      |
| `get()`          | 条件に合う全件取得            | Collection（複数） |
| `count()`        | 件数を取得                    | 数値               |

```php
<?php
use App\Models\Member;

// ─────────────────────────────────────
// all() - テーブルの全データを取得
// ─────────────────────────────────────
$members = Member::all();
// ↑ membersテーブルの全レコードを取得
// ↑ 戻り値: Collection（配列のようなもの）

foreach ($members as $member) {
    echo $member->username;  // 各会員の名前を表示
}

// ─────────────────────────────────────
// find(ID) - IDで1件だけ取得
// ─────────────────────────────────────
$member = Member::find(1);
// ↑ id=1 の会員を取得
// ↑ 見つからない場合: null が返る

if ($member) {
    echo $member->username;
} else {
    echo "見つかりません";
}

// ─────────────────────────────────────
// findOrFail(ID) - IDで取得（なければ404エラー）
// ─────────────────────────────────────
$member = Member::findOrFail(1);
// ↑ id=1 の会員を取得
// ↑ 見つからない場合: 自動で404エラーページを表示
// ↑ if文で確認する必要がない

// ─────────────────────────────────────
// first() - 最初の1件を取得
// ─────────────────────────────────────
$member = Member::first();
// ↑ 最初の1件だけ取得
// ↑ 条件と組み合わせて使うことが多い

$member = Member::where('gender', 0)->first();
// ↑ 男性の最初の1件を取得

// ─────────────────────────────────────
// get() - 条件に合う全件を取得
// ─────────────────────────────────────
$members = Member::where('gender', 0)->get();
// ↑ 男性の会員を全件取得
// ↑ where()の後に必ずget()をつける

// ─────────────────────────────────────
// count() - 件数を取得
// ─────────────────────────────────────
$total = Member::count();           // 全会員数
$males = Member::where('gender', 0)->count();  // 男性会員数
```

#### 条件指定メソッド

| メソッド                            | 説明             | 例                                |
| ----------------------------------- | ---------------- | --------------------------------- |
| `where('カラム', '値')`           | 等しい           | `where('gender', 0)`            |
| `where('カラム', '演算子', '値')` | 比較             | `where('area', '>', 50)`        |
| `orWhere()`                       | OR条件           | `orWhere('status', 1)`          |
| `whereIn()`                       | 複数値のいずれか | `whereIn('status', [0, 1])`     |
| `orderBy('カラム', '方向')`       | 並び替え         | `orderBy('created_at', 'desc')` |
| `limit(件数)`                     | 取得件数制限     | `limit(10)`                     |

```php
<?php
use App\Models\Land;

// ─────────────────────────────────────
// where() - 条件を指定
// ─────────────────────────────────────
// 基本形（等しい）
$lands = Land::where('prefectures', 13)->get();
// ↑ WHERE prefectures = 13 と同じ

// 比較演算子を使う
$lands = Land::where('area', '>', 50)->get();     // 50より大きい
$lands = Land::where('area', '>=', 50)->get();    // 50以上
$lands = Land::where('area', '<', 100)->get();    // 100未満
$lands = Land::where('area', '!=', 0)->get();     // 0以外

// 複数条件（AND）
$lands = Land::where('prefectures', 13)
             ->where('area', '>', 50)
             ->get();
// ↑ 東京都 AND 50㎡以上

// ─────────────────────────────────────
// orWhere() - OR条件
// ─────────────────────────────────────
$lands = Land::where('prefectures', 13)
             ->orWhere('prefectures', 14)
             ->get();
// ↑ 東京都 OR 神奈川県

// ─────────────────────────────────────
// whereIn() - 複数値のいずれかに一致
// ─────────────────────────────────────
$lands = Land::whereIn('prefectures', [13, 14, 11])->get();
// ↑ 東京都 OR 神奈川県 OR 埼玉県

// ─────────────────────────────────────
// orderBy() - 並び替え
// ─────────────────────────────────────
$lands = Land::orderBy('area', 'desc')->get();  // 面積大きい順
$lands = Land::orderBy('area', 'asc')->get();   // 面積小さい順
$lands = Land::orderBy('created_at', 'desc')->get();  // 新しい順

// 複数の並び替え
$lands = Land::orderBy('prefectures', 'asc')
             ->orderBy('area', 'desc')
             ->get();
// ↑ 都道府県順 → 同じ県内では面積大きい順

// ─────────────────────────────────────
// limit() - 件数制限
// ─────────────────────────────────────
$lands = Land::limit(10)->get();  // 最初の10件だけ
$lands = Land::orderBy('created_at', 'desc')
             ->limit(5)
             ->get();
// ↑ 新しい順で5件だけ
```

#### メソッドチェーンの書き方

```php
<?php
// メソッドを連続して書くことができる（メソッドチェーン）
$lands = Land::where('prefectures', 13)   // 東京都
             ->where('area', '>', 30)      // 30㎡以上
             ->orderBy('area', 'desc')     // 面積大きい順
             ->limit(10)                   // 10件まで
             ->get();                      // 実行

// 上と同じ意味（1行で書く場合）
$lands = Land::where('prefectures', 13)->where('area', '>', 30)->orderBy('area', 'desc')->limit(10)->get();
```

### 基本的なCRUD操作

```php
<?php
use App\Models\Member;
use App\Models\Land;

// ─────────────────────────────────────────
// 取得（SELECT）
// ─────────────────────────────────────────

// 全件取得
$members = Member::all();

// IDで1件取得
$member = Member::find(1);
$member = Member::findOrFail(1);  // なければ404エラー

// 条件検索
$members = Member::where('gender', 0)->get();       // 男性のみ
$members = Member::where('gender', '!=', 0)->get(); // 男性以外

// 複数条件
$lands = Land::where('prefectures', 13)  // 東京
             ->where('area', '>', 50)     // 50㎡以上
             ->orderBy('created_at', 'desc')
             ->get();

// 最初の1件
$land = Land::where('city', '渋谷区')->first();

// 件数取得
$count = Member::count();
$count = Land::where('prefectures', 13)->count();

// ─────────────────────────────────────────
// 作成（INSERT）
// ─────────────────────────────────────────

$member = Member::create([
    'email' => 'test@example.com',
    'password' => bcrypt('password123'),
    'tel' => '090-1234-5678',
    'birth' => '1990-01-15',
    'gender' => 0,
    'identity' => '/uploads/id_card.jpg',
    'username' => 'テストユーザー',
]);
// ↑ 作成されたレコードが $member に入る
// ↑ $member->id で新しいIDを取得できる

// ─────────────────────────────────────────
// 更新（UPDATE）
// ─────────────────────────────────────────

$member = Member::find(1);
$member->username = '新しい名前';
$member->save();

// または一括更新
$member->update([
    'username' => '新しい名前',
    'tel' => '080-9876-5432',
]);

// ─────────────────────────────────────────
// 削除（DELETE）
// ─────────────────────────────────────────

$member = Member::find(1);
$member->delete();

// 条件で一括削除
Land::where('user_id', 5)->delete();
```

### リレーション（関連データの取得）

```php
<?php
// ─────────────────────────────────────────
// 1対多（hasMany）: 親 → 子の取得
// ─────────────────────────────────────────

// 会員の所有する土地を全て取得
$member = Member::find(1);
$lands = $member->lands;  // この会員の全ての土地

// 会員の貸出記録を取得
$records = $member->rentalRecords;

// 土地のレビュー一覧を取得
$land = Land::find(1);
$reviews = $land->reviews;

// ─────────────────────────────────────────
// 多対1（belongsTo）: 子 → 親の取得
// ─────────────────────────────────────────

// 土地の所有者を取得
$land = Land::find(1);
$owner = $land->owner;  // この土地の所有者
echo $owner->username;

// 貸出記録の土地と借り手を取得
$record = RentalRecord::find(1);
$land = $record->land;      // 貸し出された土地
$renter = $record->renter;  // 借りた人

// レビューの関連情報
$review = ReviewComment::find(1);
$land = $review->land;           // レビュー対象の土地
$record = $review->rentalRecord; // 対応する貸出記録
$reviewer = $review->reviewer;   // レビューを書いた人

// ─────────────────────────────────────────
// Eager Loading（N+1問題の解決）
// ─────────────────────────────────────────

// ❌ 悪い例（N+1問題）
$lands = Land::all();
foreach ($lands as $land) {
    echo $land->owner->username;  // 土地ごとにクエリが発生
}

// ✅ 良い例（Eager Loading）
$lands = Land::with('owner')->get();  // 1回のクエリで全取得
foreach ($lands as $land) {
    echo $land->owner->username;  // 追加クエリなし
}

// 複数のリレーションを同時に取得
$lands = Land::with(['owner', 'rentalRecords', 'reviews'])->get();
```

### 各モデルの具体例

#### Memberモデル（会員）

```php
<?php
use App\Models\Member;

// 会員登録
$member = Member::create([
    'email' => 'tanaka@example.com',
    'password' => bcrypt('Password123'),
    'tel' => '090-1111-2222',
    'birth' => '1995-05-20',
    'show_birth' => false,
    'gender' => 0,  // 0:男性, 1:女性, 2:その他
    'show_gender' => true,
    'identity' => '/uploads/identity/tanaka.jpg',
    'username' => '田中太郎',
]);

// この会員が所有する土地
$lands = $member->lands;

// この会員が借りた土地（貸出記録から）
$rentals = $member->rentalRecords;

// この会員が送受信したチャット
$sentChats = $member->sentChats;
$receivedChats = $member->receivedChats;
```

#### Landモデル（土地）

```php
<?php
use App\Models\Land;

// 土地登録
$land = Land::create([
    'prefectures' => 13,  // 東京（0:北海道～）
    'city' => '渋谷区',
    'street_address' => '神南1-2-3',
    'area' => 25.50,  // 25.5㎡
    'user_id' => 1,   // 所有者のID
]);

// 土地の所有者を取得
$owner = $land->owner;

// この土地の貸出記録
$records = $land->rentalRecords;

// この土地のレビュー一覧
$reviews = $land->reviews;

// 東京の土地を面積順に取得
$lands = Land::where('prefectures', 13)
             ->orderBy('area', 'desc')
             ->get();
```

#### RentalRecordモデル（貸出記録）

```php
<?php
use App\Models\RentalRecord;

// 貸出記録作成
$record = RentalRecord::create([
    'price' => 1000,
    'price_unit' => 0,  // 0:日, 1:時間, 2:15分
    'rental_start_date' => '2025-01-15',
    'rental_end_date' => '2025-01-20',
    'rental_start_time' => '09:00:00',
    'rental_end_time' => '18:00:00',
    'land_id' => 1,
    'user_id' => 2,  // 借りた人
]);

// 関連情報取得
$land = $record->land;      // 貸し出された土地
$renter = $record->renter;  // 借りた人
$review = $record->review;  // この貸出に対するレビュー

// 特定の土地の貸出履歴
$records = RentalRecord::where('land_id', 1)
                        ->orderBy('rental_start_date', 'desc')
                        ->get();
```

#### Contactモデル（問い合わせ）

```php
<?php
use App\Models\Contact;

// 問い合わせ作成
$contact = Contact::create([
    'title' => 'サービスについて',
    'message' => 'キャンセル方法を教えてください',
    'user_id' => 1,
    'date' => now()->toDateString(),
    'status' => 0,  // 0:未対応, 1:対応中, 2:対応済み
]);

// 返信一覧
$replies = $contact->replies;

// 未対応の問い合わせ
$pending = Contact::where('status', 0)->get();
```

#### Chatモデル（DM）

```php
<?php
use App\Models\Chat;

// メッセージ送信
$chat = Chat::create([
    'user_id_from' => 1,  // 送信者
    'user_id_to' => 2,    // 受信者
    'message' => 'こんにちは！土地について質問があります。',
    'image' => null,
    'sent_date' => now()->toDateString(),
    'sent_time' => now()->toTimeString(),
]);

// 2人のやり取りを取得
$conversation = Chat::where(function($q) {
    $q->where('user_id_from', 1)->where('user_id_to', 2);
})->orWhere(function($q) {
    $q->where('user_id_from', 2)->where('user_id_to', 1);
})->orderBy('sent_date')->orderBy('sent_time')->get();
```

---

## 📖 よく使うコマンド

### Git

```bash
git pull                    # 最新を取得
git checkout -b ブランチ名   # 新しいブランチ作成
git add .                   # 変更をステージング
git commit -m "メッセージ"   # コミット
git push                    # プッシュ
git checkout main           # mainに戻る
```

### Docker/Laravel

```bash
docker compose up -d        # サーバー起動
docker compose down         # サーバー停止
docker compose exec app php artisan migrate              # マイグレーション
docker compose exec app php artisan make:controller 名前  # コントローラ作成
docker compose exec app php artisan make:model 名前 -m    # モデル+マイグレーション作成
```

---

## 📖 トラブルシューティング

> **困ったときは、まずここを見てください！**

### 🚨 よくあるエラーと解決方法

---

### 「port is already allocated」（ポートが使用中）

**どういうエラー？**

```
Error response from daemon: driver failed programming external connectivity:
Bind for 0.0.0.0:80: port is already allocated
```

**原因**: 別のソフト（Skype、Apache、他のDockerなど）が同じポートを使っている

**解決方法**:

```bash
# Step 1: .envファイルを開く（プロジェクトフォルダ内）

# Step 2: 以下の行を追加
APP_PORT=8081

# Step 3: 再起動
docker compose down
docker compose up -d

# Step 4: http://localhost:8081 でアクセス
```

**図解**:

```
❌ ポート80が使用中
┌────────────┐     ┌────────────┐
│  Skype     │────▶│  ポート80   │
└────────────┘     └────────────┘
                         ↑
┌────────────┐           │ 衝突！
│  Docker    │───────────┘
└────────────┘

✅ ポートを変更して解決
┌────────────┐     ┌────────────┐
│  Skype     │────▶│  ポート80   │
└────────────┘     └────────────┘

┌────────────┐     ┌────────────┐
│  Docker    │────▶│ ポート8081  │ ← 別のポートを使う！
└────────────┘     └────────────┘
```

---

### 「変更が反映されない」

**どういうエラー？**
コードを変更したのに、ブラウザに反映されない

**原因**: Laravelがキャッシュ（一時保存データ）を使っている

**解決方法**:

```bash
# 全てのキャッシュをクリア
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan view:clear
docker compose exec app php artisan route:clear

# ブラウザも強制リロード
# Windows: Ctrl + Shift + R
# Mac: Cmd + Shift + R
```

**それでもダメなら**:

```bash
# Dockerを完全に再起動
docker compose down
docker compose up -d --build
```

---

### 「git pushできない」

**どういうエラー？**

```
! [rejected]        main -> main (fetch first)
error: failed to push some refs to 'github.com:...'
```

**原因**: 他のメンバーが先にpushしていて、あなたのローカルが古い

**解決方法**:

```bash
# Step 1: 最新を取得
git pull

# Step 2: 再度push
git push
```

**図解**:

```
あなた                 GitHub                他のメンバー
  │                      │                      │
  │                      │◀──── push ──────────│
  │                      │  (最新版)             │
  │                      │                      │
  │───── push ─────────▶│ ❌ 拒否！            │
  │  (古い版をpush)      │  「先にpullして」      │
  │                      │                      │
  │◀──── pull ──────────│                      │
  │  (最新を取得)        │                      │
  │                      │                      │
  │───── push ─────────▶│ ✅ OK!               │
  │  (マージ後push)      │                      │
```

---

### 「SQLSTATE[HY000] [2002] Connection refused」

**どういうエラー？**
データベースに接続できないエラー

**原因**: MySQLコンテナが起動していない、または起動中

**解決方法**:

```bash
# Step 1: コンテナの状態を確認
docker compose ps

# 全てのサービスが「Up」になっているか確認：
# NAME           STATUS
# app            Up 5 minutes
# mysql          Up 5 minutes   ← これがUpになっていること
# phpmyadmin     Up 5 minutes

# Step 2: mysqlがUpじゃない場合、再起動
docker compose down
docker compose up -d

# Step 3: MySQLの起動を待つ（30秒くらい）
# その後、再度アクセス
```

---

### 「Class 'App\Models\Land' not found」

**どういうエラー？**
モデルが見つからないエラー

**原因**: use文を書き忘れている

**解決方法**:

```php
<?php
// ❌ ダメな例（use文がない）
class LandController extends Controller
{
    public function index()
    {
        $lands = Land::all();  // エラー！Landが見つからない
    }
}

// ✅ 正しい例（use文を追加）
use App\Models\Land;  // ← これを追加！

class LandController extends Controller
{
    public function index()
    {
        $lands = Land::all();  // OK!
    }
}
```

**図解**:

```
use文 = 他のファイルのクラスを使う宣言

┌─────────────────────────────┐
│ app/Models/Land.php         │
│  class Land { ... }         │◀───┐
└─────────────────────────────┘    │
                                    │ use App\Models\Land;
┌─────────────────────────────┐    │ ↑ この宣言で
│ app/Http/Controllers/       │    │   Landを使えるようになる
│ LandController.php          │────┘
└─────────────────────────────┘
```

---

### 「Undefined variable $lands」

**どういうエラー？**
変数が定義されていないエラー

**原因**: コントローラからビューに変数を渡していない

**解決方法**:

```php
<?php
// ❌ ダメな例（変数を渡していない）
public function index()
{
    $lands = Land::all();
    return view('lands.index');  // $landsを渡していない！
}

// ✅ 正しい例（変数を渡す）
public function index()
{
    $lands = Land::all();
    return view('lands.index', ['lands' => $lands]);
    //                         ↑ これで渡す！
  
    // または compact() を使う書き方
    return view('lands.index', compact('lands'));
}
```

---

### 「404 Not Found」

**どういうエラー？**
ページが見つからないエラー

**原因**: ルーティングが設定されていない

**解決方法**:

```php
<?php
// routes/web.php を確認

// ❌ ルートがない状態
// /lands にアクセスしても404

// ✅ ルートを追加
use App\Http\Controllers\LandController;

Route::get('/lands', [LandController::class, 'index']);
// ↑ これで /lands にアクセスすると LandController の index が呼ばれる
```

**ルート確認コマンド**:

```bash
# 設定されている全ルートを表示
docker compose exec app php artisan route:list
```

---

### 「Add [name] to fillable property」

**どういうエラー？**

```
Add [name] to fillable property to allow mass assignment on [App\Models\Land].
```

**原因**: モデルの `$fillable` に保存したいカラムが登録されていない

**解決方法**:

```php
<?php
// app/Models/Land.php

class Land extends Model
{
    // ❌ fillableに name がない
    protected $fillable = [
        'location',
        'area',
    ];
  
    // ✅ name を追加
    protected $fillable = [
        'name',      // ← 追加！
        'location',
        'area',
    ];
}
```

**なぜfillableが必要？**

```
セキュリティのため！

悪意のあるユーザーが勝手にデータを送信しても、
$fillableに書いてあるカラムしか保存できない。

例: admin = true を送っても、
    $fillableに admin がなければ無視される。
```

---

### 「Method [show] does not exist on [App\Http\Controllers\LandController]」

**どういうエラー？**
メソッドが存在しないエラー

**原因**: コントローラにそのメソッドを作っていない

**解決方法**:

```php
<?php
// app/Http/Controllers/LandController.php

class LandController extends Controller
{
    // ❌ showメソッドがない

    // ✅ showメソッドを追加
    public function show($id)
    {
        $land = Land::findOrFail($id);
        return view('lands.show', ['land' => $land]);
    }
}
```

---

## 🔧 初心者がよくやる間違いTOP10

### 1. セミコロン（;）の付け忘れ

```php
// ❌
$name = "田中"

// ✅
$name = "田中";
```

### 2. 変数の$を忘れる

```php
// ❌
name = "田中";

// ✅
$name = "田中";
```

### 3. ""と''の違いを理解していない

```php
$name = "田中";

// ❌ シングルクォートは変数展開されない
echo 'こんにちは、$nameさん';  // → こんにちは、$nameさん

// ✅ ダブルクォートは変数展開される
echo "こんにちは、{$name}さん";  // → こんにちは、田中さん
```

### 4. =と==と===の違い

```php
$a = 5;     // 代入（$aに5を入れる）
$a == 5;    // 比較（$aが5と等しいか？）
$a === 5;   // 厳密比較（型も含めて等しいか？）

// 例
$a = "5";           // 文字列の"5"
$a == 5;            // true（値が同じ）
$a === 5;           // false（型が違う：文字列 vs 数値）
```

### 5. ->と=>の違い

```php
// -> はオブジェクトのプロパティ/メソッドにアクセス
$user->name;
$user->greet();

// => は配列のキーと値を結ぶ
$array = ["key" => "value"];
```

### 6. ファイル名の大文字小文字

```php
// ❌ ファイル名が違う
// ファイル: land.php
use App\Models\Land;  // エラー

// ✅ ファイル名とクラス名を一致させる
// ファイル: Land.php
use App\Models\Land;  // OK
```

### 7. ルートの順番

```php
// ❌ 順番が悪い
Route::get('/lands/{id}', [LandController::class, 'show']);
Route::get('/lands/create', [LandController::class, 'create']);
// → /lands/create が /lands/{id} にマッチしてしまう！

// ✅ 固定パスを先に書く
Route::get('/lands/create', [LandController::class, 'create']);
Route::get('/lands/{id}', [LandController::class, 'show']);
```

### 8. foreachで変数名の一致

```php
// ❌ コントローラとビューで変数名が違う
// コントローラ
$landList = Land::all();
return view('lands.index', ['landList' => $landList]);

// ビュー
@foreach($lands as $land)  // エラー！$landsは存在しない

// ✅ 変数名を一致させる
@foreach($landList as $land)  // OK
```

### 9. マイグレーション後のモデル更新忘れ

```
マイグレーションでカラムを追加したら、
モデルの$fillableにも追加することを忘れずに！

1. マイグレーション作成・実行
2. モデルの$fillable更新  ← これを忘れがち！
```

### 10. Bladeのエスケープ

```html
{{-- ❌ HTMLタグがそのまま表示される --}}
{{ $html }}  <!-- <b>太字</b> -->

{{-- ✅ HTMLを解釈して表示 --}}
{!! $html !!}  <!-- <b>太字</b> -->

{{-- 注意: ユーザー入力には {!! !!} を使わない！（XSS攻撃の危険） --}}
```

---

## 🎓 理解度チェッククイズ

自分の理解度を確認してみましょう！

### Q1: この変数の値は？

```php
$a = 10;
$b = $a + 5;
$a = 20;
echo $b;
```

<details>
<summary>答えを見る</summary>

**答え: 15**

$bに代入した時点の$aの値（10）が使われる。
その後$aを20に変えても、$bには影響しない。

</details>

### Q2: このコードのエラーは？

```php
public function index()
{
    $lands = Land::all()
    return view('lands.index');
}
```

<details>
<summary>答えを見る</summary>

**答え: セミコロンがない**

正しくは:

```php
$lands = Land::all();  // ← セミコロン追加
```

</details>

### Q3: MVCで「M」は何？

<details>
<summary>答えを見る</summary>

**答え: Model（モデル）**

- M = Model（データベース操作）
- V = View（画面表示）
- C = Controller（処理の制御）

</details>

### Q4: このルートはどのURLにマッチする？

```php
Route::get('/lands/{id}', [LandController::class, 'show']);
```

<details>
<summary>答えを見る</summary>

**答え: /lands/1, /lands/2, /lands/abc など**

{id} 部分は何でもマッチする。
その値はshowメソッドの引数$idに入る。

</details>

### Q5: belongsToとhasManyの違いは？

<details>
<summary>答えを見る</summary>

**答え:**

- **belongsTo**: 「〜に属する」（多対1）

  - 例: 土地は所有者（1人）に属する
  - Land → belongsTo → Member
- **hasMany**: 「複数持つ」（1対多）

  - 例: 所有者は土地（複数）を持つ
  - Member → hasMany → Land

</details>

---

## 📚 さらに学ぶために

### おすすめの学習リソース

| リソース                  | URL                           | 説明                       |
| ------------------------- | ----------------------------- | -------------------------- |
| Laravel公式ドキュメント   | https://laravel.com/docs      | 公式（英語）               |
| Laravel日本語ドキュメント | https://readouble.com/laravel | 日本語訳                   |
| Laracasts                 | https://laracasts.com         | 動画チュートリアル（英語） |
| ドットインストール        | https://dotinstall.com        | 日本語の動画レッスン       |

### 困ったときの検索方法

```
検索のコツ:
1. エラーメッセージをそのままコピペして検索
2. 「Laravel [やりたいこと]」で検索
3. 「PHP [わからないこと]」で検索

例:
- 「Laravel ログイン機能 作り方」
- 「PHP 配列 ループ」
- 「SQLSTATE[HY000] [2002]」
```

---

## 💡 開発のヒント

### VSCodeおすすめ拡張機能

| 拡張機能               | 説明                     |
| ---------------------- | ------------------------ |
| PHP Intelephense       | PHPの補完・エラー検出    |
| Laravel Blade Snippets | Bladeテンプレートの補完  |
| Laravel Artisan        | コマンドをVSCodeから実行 |
| GitLens                | Gitの履歴を可視化        |
| Docker                 | Dockerの管理             |

### コードを見やすくするコツ

```php
// ❌ 読みにくい
$lands=Land::where('prefectures',13)->where('area','>',50)->orderBy('area','desc')->limit(10)->get();

// ✅ 読みやすい（改行とインデントを使う）
$lands = Land::where('prefectures', 13)
             ->where('area', '>', 50)
             ->orderBy('area', 'desc')
             ->limit(10)
             ->get();
```

---

*最終更新: 2025-12-16*
