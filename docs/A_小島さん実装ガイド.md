# A 小島さん 実装ガイド

このファイルは小島さんが担当する機能の実装手順です。

---

## 担当機能一覧

| 機能 | ブランチ名 |
|------|----------|
| 土地検索画面 | feature/kojima-land-search |
| 土地詳細画面 | feature/kojima-land-detail |
| レンタル確認画面 | feature/kojima-rental-confirm |
| 問い合わせ画面 | feature/kojima-contact |

---

## 1. 土地検索画面

### 概要
ユーザーが土地を検索・一覧表示する画面

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout main
   git pull
   git checkout -b feature/kojima-land-search
   ```

2. **コントローラにメソッド追加**
   ```php
   // app/Http/Controllers/LandController.php
   public function index(Request $request)
   {
       $query = Land::query();
       
       // 都道府県で絞り込み
       if ($request->prefecture) {
           $query->where('PREFECTURES', $request->prefecture);
       }
       
       $lands = $query->paginate(10);
       return view('land.index', compact('lands'));
   }
   ```

3. **ルート追加**
   ```php
   // routes/web.php
   Route::get('/lands', [LandController::class, 'index'])->name('lands.index');
   ```

4. **ビュー作成**
   - ファイル: `resources/views/land/index.blade.php`
   - 検索フォーム（都道府県セレクトボックス）
   - 土地一覧（カード形式）

### チェックポイント
- [ ] 検索フォームが動作する
- [ ] 土地一覧が表示される
- [ ] ページネーションが機能する

---

## 2. 土地詳細画面

### 概要
土地の詳細情報とレンタル申請ボタンを表示

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout -b feature/kojima-land-detail
   ```

2. **コントローラにメソッド追加**
   ```php
   public function show($id)
   {
       $land = Land::with('member')->findOrFail($id);
       return view('land.show', compact('land'));
   }
   ```

3. **ルート追加**
   ```php
   Route::get('/lands/{id}', [LandController::class, 'show'])->name('lands.show');
   ```

4. **ビュー作成**
   - ファイル: `resources/views/land/show.blade.php`
   - 土地情報（住所、面積、価格、説明）
   - 所有者情報
   - レンタル申請ボタン（ログイン時のみ）

---

## 3. レンタル確認画面

### 概要
レンタル申請前の確認画面

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout -b feature/kojima-rental-confirm
   ```

2. **コントローラ作成**
   ```bash
   docker compose exec app php artisan make:controller RentalController
   ```

3. **メソッド追加**
   ```php
   public function confirm($landId)
   {
       $land = Land::findOrFail($landId);
       return view('rental.confirm', compact('land'));
   }
   
   public function store(Request $request)
   {
       RentalRecord::create([
           'LAND_ID' => $request->land_id,
           'USER_ID' => Auth::id(),
           'STATUS' => 0, // 申請中
       ]);
       return redirect('/my-rentals')->with('success', '申請しました');
   }
   ```

4. **ルート追加**
   ```php
   Route::middleware('auth')->group(function () {
       Route::get('/rentals/confirm/{land}', [RentalController::class, 'confirm']);
       Route::post('/rentals', [RentalController::class, 'store']);
   });
   ```

---

## 4. 問い合わせ画面

### 概要
サイトへの問い合わせフォーム

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout -b feature/kojima-contact
   ```

2. **コントローラ作成**
   ```bash
   docker compose exec app php artisan make:controller ContactController
   ```

3. **メソッド追加**
   ```php
   public function create()
   {
       return view('contact.create');
   }
   
   public function store(Request $request)
   {
       $request->validate([
           'TITLE' => 'required|max:255',
           'CONTENT' => 'required',
       ]);
       
       Contact::create([
           'USER_ID' => Auth::id(),
           'TITLE' => $request->TITLE,
           'CONTENT' => $request->CONTENT,
       ]);
       
       return redirect('/')->with('success', 'お問い合わせを送信しました');
   }
   ```

---

## 作業完了後

```bash
git add .
git commit -m "土地検索画面を実装"
git push origin feature/kojima-land-search
```

GitHubでプルリクエストを作成してください。
