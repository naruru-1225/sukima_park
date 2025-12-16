# C 志賀さん 実装ガイド

このファイルは志賀さんが担当する機能の実装手順です。

---

## 担当機能一覧

| 機能 | ブランチ名 |
|------|----------|
| トップページ | feature/shiga-home |
| ユーザ画面 | feature/shiga-user |
| 自己保持土地一覧 | feature/shiga-my-lands |
| 土地貸出承認画面 | feature/shiga-rental-approve |
| 貸出中詳細画面 | feature/shiga-rental-detail |

---

## 1. トップページ

### 概要
サイトのトップページ（ランディングページ）

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout main
   git pull
   git checkout -b feature/shiga-home
   ```

2. **コントローラ作成**
   ```bash
   docker compose exec app php artisan make:controller HomeController
   ```

3. **メソッド追加**
   ```php
   public function index()
   {
       // 新着土地を取得
       $latestLands = Land::latest()->take(6)->get();
       return view('home', compact('latestLands'));
   }
   ```

4. **ルート変更**
   ```php
   // routes/web.php
   Route::get('/', [HomeController::class, 'index']);
   ```

5. **ビュー作成**
   - ファイル: `resources/views/home.blade.php`
   - ヒーローセクション
   - 新着土地一覧
   - サービス説明

---

## 2. ユーザ画面

### 概要
他のユーザーのプロフィールを表示

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout -b feature/shiga-user
   ```

2. **コントローラ作成**
   ```bash
   docker compose exec app php artisan make:controller UserController
   ```

3. **メソッド追加**
   ```php
   public function show($id)
   {
       $user = Member::findOrFail($id);
       $lands = $user->lands()->where('STATUS', 1)->get();
       return view('user.show', compact('user', 'lands'));
   }
   ```

4. **ルート追加**
   ```php
   Route::get('/users/{id}', [UserController::class, 'show']);
   ```

---

## 3. 自己保持土地一覧

### 概要
ログインユーザーが所有する土地の一覧

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout -b feature/shiga-my-lands
   ```

2. **メソッド追加（LandController）**
   ```php
   public function myLands()
   {
       $lands = Land::where('USER_ID', Auth::id())->get();
       return view('land.my-lands', compact('lands'));
   }
   ```

3. **ルート追加**
   ```php
   Route::middleware('auth')->group(function () {
       Route::get('/my-lands', [LandController::class, 'myLands']);
   });
   ```

---

## 4. 土地貸出承認画面

### 概要
土地オーナーがレンタル申請を承認/拒否する画面

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout -b feature/shiga-rental-approve
   ```

2. **メソッド追加（RentalController）**
   ```php
   public function pendingRequests()
   {
       // 自分の土地への申請一覧
       $requests = RentalRecord::whereHas('land', function($q) {
           $q->where('USER_ID', Auth::id());
       })->where('STATUS', 0)->get();
       
       return view('rental.approve', compact('requests'));
   }
   
   public function approve($id)
   {
       $record = RentalRecord::findOrFail($id);
       $record->update(['STATUS' => 1]); // 承認
       return back()->with('success', '承認しました');
   }
   
   public function reject($id)
   {
       $record = RentalRecord::findOrFail($id);
       $record->update(['STATUS' => 2]); // 拒否
       return back()->with('success', '拒否しました');
   }
   ```

---

## 5. 貸出中詳細画面

### 概要
現在貸し出し中の土地の詳細

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout -b feature/shiga-rental-detail
   ```

2. **メソッド追加**
   ```php
   public function lendingShow($id)
   {
       $record = RentalRecord::with(['land', 'member'])->findOrFail($id);
       return view('rental.lending-show', compact('record'));
   }
   ```

---

## 作業完了後

```bash
git add .
git commit -m "トップページを実装"
git push origin feature/shiga-home
```

GitHubでプルリクエストを作成してください。
