# E 三輪さん 実装ガイド

このファイルは三輪さんが担当する機能の実装手順です。

---

## 担当機能一覧

| 機能 | ブランチ名 |
|------|----------|
| レンタル中一覧画面 | feature/miwa-rental-list |
| レンタル中詳細画面 | feature/miwa-rental-detail |
| 取引完了一覧画面 | feature/miwa-completed-list |
| 取引完了詳細画面 | feature/miwa-completed-detail |
| レビュー画面 | feature/miwa-review |

---

## 1. レンタル中一覧画面

### 概要
自分がレンタル中の土地一覧を表示

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout main
   git pull
   git checkout -b feature/miwa-rental-list
   ```

2. **コントローラ作成（存在しない場合）**
   ```bash
   docker compose exec app php artisan make:controller RentalController
   ```

3. **メソッド追加**
   ```php
   public function index()
   {
       // 自分がレンタル中の記録（STATUS=1: 承認済み）
       $rentals = RentalRecord::where('USER_ID', Auth::id())
           ->where('STATUS', 1)
           ->with('land')
           ->get();
       
       return view('rental.index', compact('rentals'));
   }
   ```

4. **ルート追加**
   ```php
   Route::middleware('auth')->group(function () {
       Route::get('/my-rentals', [RentalController::class, 'index']);
   });
   ```

5. **ビュー作成**
   - ファイル: `resources/views/rental/index.blade.php`
   - レンタル中の土地をカード形式で一覧表示

---

## 2. レンタル中詳細画面

### 概要
レンタル中の土地の詳細情報

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout -b feature/miwa-rental-detail
   ```

2. **メソッド追加**
   ```php
   public function show($id)
   {
       $rental = RentalRecord::with(['land', 'land.member'])
           ->where('USER_ID', Auth::id())
           ->findOrFail($id);
       
       return view('rental.show', compact('rental'));
   }
   ```

3. **ルート追加**
   ```php
   Route::get('/my-rentals/{id}', [RentalController::class, 'show'])->middleware('auth');
   ```

---

## 3. 取引完了一覧画面

### 概要
完了した取引（レンタル終了）の一覧

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout -b feature/miwa-completed-list
   ```

2. **メソッド追加**
   ```php
   public function completed()
   {
       // 完了した取引（STATUS=3: 完了）
       $rentals = RentalRecord::where('USER_ID', Auth::id())
           ->where('STATUS', 3)
           ->with('land')
           ->get();
       
       return view('rental.completed', compact('rentals'));
   }
   ```

3. **ルート追加**
   ```php
   Route::get('/completed-rentals', [RentalController::class, 'completed'])->middleware('auth');
   ```

---

## 4. 取引完了詳細画面

### 概要
完了した取引の詳細

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout -b feature/miwa-completed-detail
   ```

2. **メソッド追加**
   ```php
   public function completedShow($id)
   {
       $rental = RentalRecord::with(['land', 'land.member', 'reviewComment'])
           ->where('USER_ID', Auth::id())
           ->findOrFail($id);
       
       return view('rental.completed-show', compact('rental'));
   }
   ```

---

## 5. レビュー画面

### 概要
取引完了後にレビューを投稿

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout -b feature/miwa-review
   ```

2. **コントローラ作成**
   ```bash
   docker compose exec app php artisan make:controller ReviewController
   ```

3. **メソッド追加**
   ```php
   public function create($recordId)
   {
       $rental = RentalRecord::with('land')->findOrFail($recordId);
       return view('review.create', compact('rental'));
   }
   
   public function store(Request $request, $recordId)
   {
       $request->validate([
           'POINT' => 'required|integer|min:1|max:5',
           'COMMENT' => 'required|max:1000',
       ]);
       
       $rental = RentalRecord::findOrFail($recordId);
       
       ReviewComment::create([
           'RECORD_ID' => $recordId,
           'LAND_ID' => $rental->LAND_ID,
           'USER_ID' => Auth::id(),
           'POINT' => $request->POINT,
           'COMMENT' => $request->COMMENT,
       ]);
       
       return redirect('/completed-rentals')->with('success', 'レビューを投稿しました');
   }
   ```

4. **ルート追加**
   ```php
   Route::middleware('auth')->group(function () {
       Route::get('/reviews/create/{record}', [ReviewController::class, 'create']);
       Route::post('/reviews/{record}', [ReviewController::class, 'store']);
   });
   ```

5. **ビュー作成**
   - ファイル: `resources/views/review/create.blade.php`
   - 評価（1-5の星）
   - コメント入力欄

---

## 作業完了後

```bash
git add .
git commit -m "レンタル中一覧画面を実装"
git push origin feature/miwa-rental-list
```

GitHubでプルリクエストを作成してください。
