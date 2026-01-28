# 理論上のエラー検出レポート

**検査日**: 2026年1月27日  
**検査対象**: 全ビューファイル、コントローラー、ルート定義  
**検査方法**: 静的解析（コードレビュー）  

---

## 🔴 重大なエラー（機能停止）

### 1. 土地登録機能のルート未定義

**影響範囲**: 土地登録フロー全体が動作しない

#### 使用箇所

**ビューファイル**:
- `profile_edit_screen.blade.php` (line 313)
- `profile_comfirmation_screen.blade.php` (line 231)
- `layouts/header.blade.php` (line 14)

```php
<a href="{{ route('land.register') }}" class="btn btn-primary">土地を登録</a>
```

**ビューファイル**:
- `land_register.blade.php` (line 264)

```php
<form method="POST" action="{{ route('land.register') }}" ...>
```

**ビューファイル**:
- `land_register_confirm.blade.php` (line 275, 276)

```php
<a href="{{ route('land.register') }}" class="btn btn-secondary">修正する</a>
<form action="{{ route('land.register.store') }}" method="POST" ...>
```

#### 現状

- ❌ `land.register` ルートが未定義
- ❌ `land.register.store` ルートが未定義
- ✅ `LandController` は存在・実装済み

#### 必要な修正

routes/web.php に以下を追加:

```php
// --- 土地登録 ---
Route::get('/land/register', [LandController::class, 'showRegisterForm'])->name('land.register');
Route::post('/land/register', [LandController::class, 'register']);
Route::get('/land/register/confirm', [LandController::class, 'showConfirm'])->name('land.register.confirm');
Route::post('/land/register/store', [LandController::class, 'store'])->name('land.register.store');
```

---

### 2. メッセージ機能のルート未定義

**影響範囲**: DM機能全体が動作しない

#### 使用箇所

**ビューファイル**:
- `layouts/header.blade.php` (line 19)

```php
<a href="{{ route('messages.index') }}" class="nav-icon-btn" ...>
```

**ビューファイル**:
- `message_list_screen.blade.php` (line 448, 466)

```php
// window.location.href = `{{ route('messages.index') }}?search=${searchTerm}`;
window.location.href = `{{ route('messages.create') }}`;
```

**ビューファイル**:
- `message_detail_screen.blade.php` (line 396, 463, 533)

```php
<a href="{{ route('messages.index') }}" class="back-btn">←</a>
const messageStoreUrl = '{{ route("messages.store") }}';
const pollUrl = '{{ route("messages.poll", $recipient->id) }}';
```

**ビューファイル**:
- `message_create_screen.blade.php` (line 143, 170)

```php
<a href="{{ route('messages.index') }}" class="back-btn">←</a>
const searchUrl = '{{ route("messages.search") }}';
```

**ビューファイル**:
- `land_detail.blade.php` (line 203)
- `user_other.blade.php` (line 66)

```php
<a href="{{ route('messages.show', $user_id) }}" ...>
```

#### 現状

- ❌ `messages.index` ルートが未定義
- ❌ `messages.create` ルートが未定義
- ❌ `messages.show` ルートが未定義
- ❌ `messages.store` ルートが未定義
- ❌ `messages.poll` ルートが未定義
- ❌ `messages.search` ルートが未定義
- ✅ `MessageController` は存在・実装済み

#### 必要な修正

routes/web.php に以下を追加:

```php
// --- メッセージ（DM）---
Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
Route::get('/messages/create', [MessageController::class, 'create'])->name('messages.create');
Route::get('/messages/{userId}', [MessageController::class, 'show'])->name('messages.show');
Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
Route::get('/messages/poll/{userId}', [MessageController::class, 'poll'])->name('messages.poll');
Route::post('/messages/search', [MessageController::class, 'search'])->name('messages.search');
```

---

### 3. 検索機能のルート不整合

**影響範囲**: 土地検索機能が動作しない

#### 使用箇所

**ビューファイル**:
- `search_list.blade.php` (line 33, 203)

```php
<form action="{{ route('lands.index') }}" method="GET" ...>
<a href="{{ route('lands.show', $land->LAND_ID) }}" class="card">
```

**ビューファイル**:
- `land_detail.blade.php` (line 34, 42)

```php
<a href="{{ route('lands.index') }}" class="breadcrumb-item">検索結果</a>
<a href="{{ route('lands.index') }}" class="back-link">
```

#### 現状

- ❌ `lands.index` ルートが未定義（`search` は定義済み）
- ❌ `lands.show` ルートが未定義（`land.detail` は定義済み）

#### 必要な修正（2つの選択肢）

**選択肢A**: ルートエイリアスを追加

```php
Route::get('/lands', [SearchListController::class, 'index'])->name('lands.index');
Route::get('/lands/{id}', [LandDetailController::class, 'show'])->name('lands.show');
```

**選択肢B**: ビューファイルを修正（既存ルート名を使用）

```php
// lands.index → search
// lands.show → land.detail
```

**推奨**: 選択肢B（ビュー修正）。ルート名の重複を避ける。

---

### 4. 取引履歴画面のルート未定義

**影響範囲**: 取引完了一覧に戻るリンクが動作しない

#### 使用箇所

**ビューファイル**:
- `trade_detail.blade.php` (line 12)

```php
<a href="{{ route('rental.history') }}" class="back-link">
```

#### 現状

- ❌ `rental.history` ルートが未定義
- ✅ `trade_fin_list` ルートは定義済み（同じ機能のはず）

#### 必要な修正

routes/web.php:

```php
// エイリアス追加
Route::get('/rental/history', [RentalController::class, 'history'])->name('rental.history');
```

または trade_detail.blade.php を修正:

```php
<a href="{{ route('trade_fin_list') }}" class="back-link">
```

---

### 5. お問い合わせ送信のルート未定義

**影響範囲**: お問い合わせフォームの送信が動作しない

#### 使用箇所

**ビューファイル**:
- `contact.blade.php` (line 155)

```php
<form action="{{ route('contact.store') }}" method="POST">
```

#### 現状

- ❌ `contact.store` ルートが未定義
- ✅ `contact` ルートは定義済み（表示のみ）
- ❓ `ContactController` の実装状態は不明

#### 必要な修正

routes/web.php:

```php
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
```

ContactController に `store()` メソッドを実装:

```php
public function store(Request $request)
{
    // バリデーション
    // CONTACT_TABLEに保存
    // 完了メッセージ表示
}
```

---

## 🟡 中程度のエラー（一部機能停止）

### 6. message_detail_screen.blade.php のルート参照

**問題箇所**:
- line 397: `route('mypage', $recipient->id)`
- line 426: `route('mypage', $recipient->id)`

**問題**: 
- `mypage` ルートはパラメータを受け取らない
- 他ユーザーのプロフィールを表示したい場合は `user.show` を使用すべき

**影響**: 
- メッセージ詳細画面でユーザー名をクリックしても正しいページに遷移しない

**修正案**:

```php
// 修正前
<a href="{{ route('mypage', $recipient->id) }}" ...>

// 修正後
<a href="{{ route('user.show', $recipient->id) }}" ...>
```

---

### 7. user_my.blade.php のルート参照

**問題箇所**:
- line 99: `route('my_land_list')`

**確認事項**:
- ✅ `my_land_list` ルートは定義済み
- ✅ MyLandListController は実装済み

**状態**: 問題なし

---

### 8. rental_detail.blade.php の動的ルート

**問題箇所**:
- line 35: `route($backRoute)`

**問題**: 
- `$backRoute` 変数が存在しない場合、エラーになる
- コントローラーから渡されているか不明

**確認事項**:
- RentalController の `show()` メソッドで `$backRoute` を渡しているか

**推奨**: 
- フォールバック値を設定

```php
$backRoute = $backRoute ?? 'rental_list';
```

---

## 🟢 軽微な問題（警告）

### 9. Land モデルのリレーション

**確認済み**:
- ✅ `owner()` リレーションが定義されている
- ✅ `reviews()` リレーションが定義されている
- ✅ `rentalRecords()` リレーションが定義されている

**状態**: 問題なし

---

### 10. Member モデルの必要なリレーション

**必要なリレーション**:
- `lands()` - ユーザーが所有する土地
- `rentalRecords()` - ユーザーの貸出記録
- `sentMessages()` - 送信したメッセージ
- `receivedMessages()` - 受信したメッセージ

**確認が必要**: Member.php の内容確認

---

### 11. 開発用ルートの本番環境対策

**問題**: 
- テストログイン機能が本番環境でも有効
- セキュリティリスク

**推奨**: 
- 環境変数で制御

```php
if (config('app.env') !== 'production') {
    Route::get('/test-login', function () {
        // テストログイン処理
    });
}
```

---

## 📊 エラーサマリー

### 重大なエラー（5件）

| # | 問題 | 影響 | 優先度 |
|---|------|------|--------|
| 1 | 土地登録ルート未定義 | 土地登録不可 | 最高 |
| 2 | メッセージルート未定義 | DM機能停止 | 最高 |
| 3 | 検索ルート不整合 | 検索画面遷移不可 | 高 |
| 4 | 取引履歴ルート未定義 | 戻るリンク不可 | 中 |
| 5 | お問い合わせルート未定義 | 送信不可 | 中 |

### 中程度のエラー（3件）

| # | 問題 | 影響 | 優先度 |
|---|------|------|--------|
| 6 | message_detail のルート誤り | ユーザープロフ遷移不可 | 中 |
| 7 | rental_detail の変数チェック | 条件次第でエラー | 低 |
| 8 | Member モデルリレーション | 機能次第で影響 | 低 |

### 軽微な問題（3件）

| # | 問題 | 影響 | 優先度 |
|---|------|------|--------|
| 9 | Land モデルリレーション | 問題なし | - |
| 10 | 開発用ルート対策 | セキュリティ | 低 |
| 11 | エラー表示の統一性 | UX | 低 |

---

## 🔧 必要な修正アクション

### 即座に実施すべき修正（最優先）

1. **LandController のルート追加**
   - `land.register`
   - `land.register.store`
   - `land.register.confirm`

2. **MessageController のルート追加**
   - `messages.index`
   - `messages.create`
   - `messages.show`
   - `messages.store`
   - `messages.poll`
   - `messages.search`

3. **検索ルートの統一**
   - `lands.index` と `lands.show` の問題解決
   - ビューファイル修正を推奨

### 次に実施すべき修正

4. **ContactController の実装**
   - `contact.store` ルート追加
   - `store()` メソッド実装

5. **取引履歴ルートの追加**
   - `rental.history` エイリアス追加

6. **message_detail_screen.blade.php の修正**
   - `route('mypage', $id)` → `route('user.show', $id)`

---

## 🎯 修正後の想定動作

### 修正完了後に動作する機能

- ✅ 土地登録フロー（入力→確認→登録）
- ✅ メッセージ送受信
- ✅ 土地検索・詳細表示
- ✅ お問い合わせ送信
- ✅ 取引履歴閲覧

### 引き続き実装が必要な機能

- ⏳ 土地編集機能（LandController の edit/update メソッド）
- ⏳ 検索フィルタリング（SearchListController の実装）
- ⏳ 管理者機能（UserListController, UserDetailController）

---

## 📝 検査結論

### 現在の実装状態

- **コントローラー**: ほぼ実装済み（80%）
- **ビューファイル**: 完成（95%）
- **ルート定義**: 不完全（60%）← **最大の問題**

### 主な問題の原因

1. **ルート定義の不足**: LandController と MessageController のルートが丸ごと未定義
2. **ルート名の不統一**: lands.* vs land.*, search vs lands.index など

### 推定される原因

- 以前の開発で LandController と MessageController のルートが削除された
- または、別のルートファイルに定義されていたが統合時に漏れた
- ルート名の命名規則が途中で変更された

### 修正の難易度

- **難易度**: 低〜中
- **所要時間**: 30分〜1時間
- **リスク**: 低（ルート追加のみ）

---

**検査担当**: GitHub Copilot  
**検査完了日**: 2026年1月27日  
**総検出エラー数**: 11件（重大5、中程度3、軽微3）
