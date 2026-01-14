# user_my.blade.php 画面遷移先ファイル情報

## 必要なファイルパス一覧

マイページ（user_my.blade.php）から遷移する各画面のファイル情報を以下に示します。

---

### 1. プロフィール編集画面

**画面定義CSV**: `context/画面一覧/prof_custom.csv`

**必要なファイル**:
```
resources/views/prof_custom.blade.php
app/Http/Controllers/ProfileController.php (想定)
```

**ルート例**:
```php
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
```

**推奨ルート名**: `profile.edit`

---

### 2. 自己保持土地一覧画面

**画面定義CSV**: `context/画面一覧/my_land_list.csv`

**必要なファイル**:
```
resources/views/my_land_list.blade.php
app/Http/Controllers/LandController.php (または UserLandController.php)
```

**ルート例**:
```php
Route::get('/my-lands', [LandController::class, 'myList'])->name('lands.my');
// または
Route::get('/lands/my', [UserLandController::class, 'index'])->name('user.lands');
```

**推奨ルート名**: `lands.my` または `user.lands`

---

### 3. レンタル中一覧画面

**画面定義CSV**: `context/画面一覧/rental_list.csv`

**必要なファイル**:
```
resources/views/rental_list.blade.php
app/Http/Controllers/RentalController.php
```

**ルート例**:
```php
Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
// または
Route::get('/my-rentals', [RentalController::class, 'index'])->name('rentals.my');
```

**推奨ルート名**: `rentals.index` または `rentals.my`

---

### 4. 取引完了一覧画面

**画面定義CSV**: `context/画面一覧/trade_fin_list.csv`

**必要なファイル**:
```
resources/views/trade_fin_list.blade.php
app/Http/Controllers/TradeController.php (または RentalController.php)
```

**ルート例**:
```php
Route::get('/trades/completed', [TradeController::class, 'completed'])->name('trades.completed');
// または
Route::get('/rentals/history', [RentalController::class, 'history'])->name('rentals.history');
```

**推奨ルート名**: `trades.completed` または `rentals.history`

---

### 5. 公開中の土地「一覧を見る」ボタン

**想定遷移先**: 検索結果画面またはMy土地一覧

**画面定義CSV**: 
- `context/画面一覧/my_land_list.csv` (自分の土地の場合)
- `context/画面一覧/search_list.csv` (全体検索の場合)

**必要なファイル** (my_land_listの場合):
```
resources/views/my_land_list.blade.php
app/Http/Controllers/LandController.php
```

**ルート例**:
```php
Route::get('/my-lands', [LandController::class, 'myList'])->name('lands.my');
```

**推奨ルート名**: `lands.my`

---

## Laravel命名規則に基づく推奨構成

### コントローラー配置

```
app/Http/Controllers/
├── ProfileController.php      # プロフィール関連
├── LandController.php          # 土地関連（登録・一覧など）
├── RentalController.php        # レンタル関連
└── TradeController.php         # 取引関連（completed含む）
```

### Bladeビュー配置

```
resources/views/
├── prof_custom.blade.php       # プロフィール編集
├── prof_check.blade.php        # プロフィール確認
├── my_land_list.blade.php      # 自己保持土地一覧
├── rental_list.blade.php       # レンタル中一覧
├── trade_fin_list.blade.php    # 取引完了一覧
└── user_my.blade.php           # マイページ（実装済み）
```

### ルート名の命名規則

Laravelの一般的な命名規則:
```
{リソース名}.{アクション}

例:
- profile.edit    (プロフィール編集画面)
- profile.update  (プロフィール更新処理)
- lands.my        (自分の土地一覧)
- rentals.index   (レンタル一覧)
- rentals.show    (レンタル詳細)
- trades.completed (取引完了一覧)
```

---

## user_my.blade.php での実装例

### 現在（未実装）
```blade
<a href="#" class="btn btn-secondary">プロフィール編集</a>
<a href="#" class="btn btn-secondary">自己保持土地一覧</a>
<a href="#" class="nav-card">レンタル中一覧</a>
<a href="#" class="nav-card">取引完了一覧</a>
```

### 実装後（ルート名使用）
```blade
<a href="{{ route('profile.edit') }}" class="btn btn-secondary">プロフィール編集</a>
<a href="{{ route('lands.my') }}" class="btn btn-secondary">自己保持土地一覧</a>
<a href="{{ route('rentals.index') }}" class="nav-card">レンタル中一覧</a>
<a href="{{ route('trades.completed') }}" class="nav-card">取引完了一覧</a>
```

---

## 確認が必要な事項

各画面のルート定義について、以下のどちらの方式を採用するか決定してください：

### 方式A: 画面定義のPHPファイル名をそのまま使用
```php
Route::get('/prof_custom', ...);
Route::get('/my_land_list', ...);
Route::get('/rental_list', ...);
Route::get('/trade_fin_list', ...);
```

### 方式B: Laravel命名規則に従う（推奨）
```php
Route::get('/profile/edit', ...);
Route::get('/my-lands', ...);
Route::get('/rentals', ...);
Route::get('/trades/completed', ...);
```

---

**作成日**: 2025-12-29  
**参照元**: context/画面一覧/*.csv
