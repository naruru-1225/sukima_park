# スキマパーク チームセットアップガイド

このガイドは、**プログラミング初心者**でも迷わず環境構築ができるように詳しく書かれています。

---

## 目次
1. [はじめに読んでほしいこと](#はじめに読んでほしいこと)
2. [Git（ギット）とは](#gitギットとは)
3. [Gitブランチの使い方](#gitブランチの使い方)
4. [必要なソフトのインストール](#必要なソフトのインストール)
5. [セットアップ手順](#セットアップ手順)
6. [Laravelのフォルダ構成](#laravelのフォルダ構成)
7. [Laravelの書き方](#laravelの書き方)
8. [よく使うコマンド](#よく使うコマンド)
9. [トラブルシューティング](#トラブルシューティング)

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
└── どれが最新か分からない...

✅ Gitがある場合:
├── 全ての変更履歴が記録される
├── 誰がいつ何を変更したか分かる
├── 間違えても前の状態に戻せる
└── チーム全員が同じコードで作業できる
```

### Gitの基本用語

| 用語 | 意味 |
|-----|------|
| **リポジトリ** | プロジェクトのフォルダ（変更履歴込み） |
| **クローン** | GitHubからコピーを作ること |
| **コミット** | 変更を記録すること（セーブポイント） |
| **プッシュ** | 自分の変更をGitHubにアップロード |
| **プル** | 他の人の変更をダウンロード |
| **ブランチ** | 作業用の「枝」を作って安全に開発 |
| **マージ** | ブランチの変更を統合する |
| **プルリクエスト** | 変更をレビューしてもらう依頼 |

---

## Gitブランチの使い方

### ブランチとは？

```
ブランチ = 「作業用の枝」

main（本番）から枝分かれして作業し、完成したら戻す
```

### なぜブランチを使う？

```
❌ ブランチを使わない場合:
- 開発中のコードがmainに入り、他のメンバーに影響
- バグを入れてしまうとチーム全員が止まる

✅ ブランチを使う場合:
- 自分専用の作業スペースで開発
- 完成してからmainに統合
- 他のメンバーに影響しない
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

## 必要なソフトのインストール

### 1. Git のインストール
https://git-scm.com/download/win からダウンロード、全てデフォルトでOK

### 2. Docker Desktop のインストール
https://www.docker.com/products/docker-desktop/ からダウンロード、インストール後PC再起動

### 3. VS Code のインストール（推奨）
https://code.visualstudio.com/ からダウンロード

---

## セットアップ手順

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

## Laravelのフォルダ構成

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

## Laravelの書き方

### 1. ルーティング（routes/web.php）

**URLと処理を紐付ける設定ファイル**

```php
<?php
use App\Http\Controllers\LandController;

// 基本形: URLにアクセス → コントローラのメソッドを実行
Route::get('/lands', [LandController::class, 'index']);

// パラメータ付き: /lands/1 のようなURL
Route::get('/lands/{id}', [LandController::class, 'show']);

// フォーム送信（POST）
Route::post('/lands', [LandController::class, 'store']);

// リソースルート: 一括定義（便利）
Route::resource('lands', LandController::class);
// ↑ これだけで index, create, store, show, edit, update, destroy を定義
```

### 2. コントローラ（app/Http/Controllers/）

**処理を書く場所**

```bash
# コントローラ作成コマンド
docker compose exec app php artisan make:controller LandController
```

```php
<?php
namespace App\Http\Controllers;

use App\Models\Land;
use Illuminate\Http\Request;

class LandController extends Controller
{
    // 一覧表示: GET /lands
    public function index()
    {
        // 全ての土地を取得
        $lands = Land::all();
        
        // ビューにデータを渡す
        return view('lands.index', ['lands' => $lands]);
    }

    // 詳細表示: GET /lands/1
    public function show($id)
    {
        // IDで1件取得
        $land = Land::find($id);
        
        return view('lands.show', ['land' => $land]);
    }

    // 新規登録フォーム: GET /lands/create
    public function create()
    {
        return view('lands.create');
    }

    // 保存処理: POST /lands
    public function store(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'name' => 'required|max:50',
            'location' => 'required',
            'area' => 'required|numeric',
        ]);

        // 保存
        Land::create($validated);

        // リダイレクト
        return redirect('/lands')->with('success', '登録しました');
    }
}
```

### 3. モデル（app/Models/）

**データベースとの接続**

```bash
# モデル作成コマンド（マイグレーションも一緒に作成）
docker compose exec app php artisan make:model Land -m
```

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Land extends Model
{
    // 一括代入を許可するカラム
    protected $fillable = [
        'name',
        'location',
        'area',
        'description',
        'owner_id',
    ];

    // リレーション: この土地の所有者
    public function owner()
    {
        return $this->belongsTo(Member::class, 'owner_id');
    }
}
```

**よく使うクエリ**:
```php
// 全件取得
$lands = Land::all();

// 条件付き取得
$lands = Land::where('area', '>', 100)->get();

// 1件取得
$land = Land::find(1);
$land = Land::where('name', '駅前スペース')->first();

// 作成
Land::create(['name' => '新しい土地', 'location' => '東京都']);

// 更新
$land->update(['name' => '更新後の名前']);

// 削除
$land->delete();
```

### 4. マイグレーション（database/migrations/）

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
    public function up(): void
    {
        Schema::create('lands', function (Blueprint $table) {
            $table->id();                              // ID（自動連番）
            $table->foreignId('owner_id')              // 外部キー
                  ->constrained('members')
                  ->onDelete('cascade');
            $table->string('name', 50);                // 文字列
            $table->string('location');                // 所在地
            $table->decimal('area', 10, 2);            // 面積（小数）
            $table->text('description')->nullable();   // 説明（NULL可）
            $table->enum('status', ['available', 'rented', 'inactive'])
                  ->default('available');              // 状態
            $table->timestamps();                      // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lands');
    }
};
```

```bash
# マイグレーション実行
docker compose exec app php artisan migrate
```

### 5. ビュー（resources/views/）

**画面のHTML（Bladeテンプレート）**

**resources/views/lands/index.blade.php**:
```html
<!DOCTYPE html>
<html>
<head>
    <title>土地一覧</title>
</head>
<body>
    <h1>土地一覧</h1>

    {{-- 成功メッセージ --}}
    @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    {{-- ループ --}}
    @foreach($lands as $land)
        <div>
            <h2>{{ $land->name }}</h2>
            <p>場所: {{ $land->location }}</p>
            <p>面積: {{ $land->area }}㎡</p>
            <a href="/lands/{{ $land->id }}">詳細を見る</a>
        </div>
    @endforeach

    {{-- データがない場合 --}}
    @if($lands->isEmpty())
        <p>土地が登録されていません</p>
    @endif
</body>
</html>
```

**Bladeの書き方**:
```html
{{ $variable }}        → 変数を表示（HTMLエスケープ済み）
{!! $html !!}          → HTMLをそのまま表示
@if / @else / @endif   → 条件分岐
@foreach / @endforeach → ループ
@include('部品名')      → 共通部品を読み込み
```

---

## よく使うコマンド

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

## トラブルシューティング

### 「port is already allocated」
`.env`に`APP_PORT=8080`を追加、再起動

### 「変更が反映されない」
```bash
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
```

### 「git pushできない」
`git pull`してから再度push

---

*最終更新: 2025-12-16*
