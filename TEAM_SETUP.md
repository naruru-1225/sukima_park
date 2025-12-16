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
https://git-scm.com/download/win からダウンロード、全てデフォルトでOK

### 4. VS Code のインストール（推奨）
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

## PHP基礎文法

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

## Laravelの書き方（開発順序）

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
```### 3. コントローラ（app/Http/Controllers/）

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

## phpMyAdminの使い方

phpMyAdminはブラウザでデータベースを管理できるツールです。

### アクセス方法
**URL**: http://localhost:8080

（自動ログイン設定済み。ユーザー: sail / パスワード: password）

### 主な機能

| 機能 | 説明 |
|-----|------|
| テーブル一覧 | 左メニューでsukimaparkをクリック |
| データ参照 | テーブル名をクリック → 「表示」タブ |
| データ挿入 | テーブル名をクリック → 「挿入」タブ |
| SQL実行 | 「SQL」タブでクエリを直接実行 |
| エクスポート | 「エクスポート」タブでバックアップ |

### データベーステーブル一覧

| テーブル名 | 説明 | 主なカラム |
|-----------|------|-----------|
| members | 会員情報 | id, email, username, tel, birth, gender |
| lands | 土地情報 | id, prefectures, city, street_address, area, user_id |
| rental_records | 貸し出し記録 | id, price, rental_start_date, land_id, user_id |
| review_comments | レビュー | id, land_review, user_review, record_id |
| contacts | 問い合わせ | id, title, message, status, user_id |
| replies | 問い合わせ返信 | id, contact_id, message, user_id |
| chats | DM/チャット | id, user_id_from, user_id_to, message |

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

## モデルの使い方

### モデルとは？

**モデル** = **データベースのテーブルをPHPで操作するためのクラス**

```
┌─────────────┐      ┌─────────────┐      ┌─────────────┐
│ データベース │ ←──→ │   モデル    │ ←──→ │コントローラ │
│ (members)   │      │ (Member.php)│      │             │
└─────────────┘      └─────────────┘      └─────────────┘
      ↑                    ↑
   テーブル           PHPクラス
```

### モデルファイルの構造（文法解説）

```php
<?php
// ─────────────────────────────────────────
// namespace（名前空間）
// ─────────────────────────────────────────
namespace App\Models;
// ↑ このファイルの場所を宣言
// ↑ app/Models/ フォルダにあることを示す

// ─────────────────────────────────────────
// use文（クラスのインポート）
// ─────────────────────────────────────────
use Illuminate\Database\Eloquent\Model;
// ↑ LaravelのModelクラスを使えるようにする
use Illuminate\Database\Eloquent\Factories\HasFactory;
// ↑ テストデータ作成用のトレイト

// ─────────────────────────────────────────
// クラス定義
// ─────────────────────────────────────────
class Land extends Model
//    ↑ クラス名（単数形・先頭大文字）
//         ↑ Modelを継承（データベース操作機能を引き継ぐ）
{
    // トレイト（機能を追加）
    use HasFactory;
    // ↑ Factory機能を使えるようにする

    // ─────────────────────────────────────
    // $fillable（許可するカラム）
    // ─────────────────────────────────────
    protected $fillable = [
        'prefectures',
        'city',
        'street_address',
        'area',
        'user_id',
    ];
    // ↑ protected = このクラスと子クラスからのみアクセス可能
    // ↑ $fillable = create()で保存できるカラムを指定
    // ↑ セキュリティ対策（不正なデータ挿入を防ぐ）

    // ─────────────────────────────────────
    // $casts（型変換）
    // ─────────────────────────────────────
    protected $casts = [
        'area' => 'decimal:2',     // 小数点2桁
        'birth' => 'date',          // 日付型
        'show_birth' => 'boolean',  // true/false
    ];
    // ↑ データベースから取得時に自動で型変換される

    // ─────────────────────────────────────
    // リレーション（関連）メソッド
    // ─────────────────────────────────────
    public function owner()
    // ↑ メソッド名は関連の意味を表す名前にする
    {
        return $this->belongsTo(Member::class, 'user_id');
        //     ↑ $this = このモデル（Land）自身
        //           ↑ belongsTo = 「〜に属する」の関係
        //                        ↑ 関連先のモデル
        //                                       ↑ 外部キーのカラム名
    }

    public function rentalRecords()
    {
        return $this->hasMany(RentalRecord::class, 'land_id');
        //           ↑ hasMany = 「複数を持つ」の関係
        //                       ↑ 関連先のモデル
        //                                          ↑ 相手側の外部キー
    }
}
```

### リレーションの種類

| 種類 | 意味 | 例 |
|-----|------|-----|
| `hasMany` | 1対多（親→子） | 会員は複数の土地を持つ |
| `belongsTo` | 多対1（子→親） | 土地は1人の会員に属する |
| `hasOne` | 1対1 | 貸出記録は1つのレビューを持つ |

```php
<?php
// hasMany: 1対多（親から子を取得）
// Member（親）から見てLand（子）が複数ある
class Member extends Model
{
    public function lands()  // 複数形
    {
        return $this->hasMany(Land::class, 'user_id');
        // ↑ landsテーブルのuser_idカラムで紐付け
    }
}

// belongsTo: 多対1（子から親を取得）
// Land（子）から見てMember（親）は1人
class Land extends Model
{
    public function owner()  // 単数形
    {
        return $this->belongsTo(Member::class, 'user_id');
        // ↑ このテーブルのuser_idカラムで紐付け
    }
}

// 使い方
$member = Member::find(1);
$lands = $member->lands;      // 配列（Collection）が返る

$land = Land::find(1);
$owner = $land->owner;        // 1件のMemberが返る
```

### モデル一覧

| モデル名 | ファイル | テーブル |
|---------|---------|---------|
| Member | app/Models/Member.php | members |
| Land | app/Models/Land.php | lands |
| RentalRecord | app/Models/RentalRecord.php | rental_records |
| ReviewComment | app/Models/ReviewComment.php | review_comments |
| Contact | app/Models/Contact.php | contacts |
| Reply | app/Models/Reply.php | replies |
| Chat | app/Models/Chat.php | chats |

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
