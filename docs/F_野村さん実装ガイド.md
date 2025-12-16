# F 野村さん 実装ガイド（管理者機能）

このファイルは野村さんが担当する管理者機能の実装手順です。

---

## 担当機能一覧

| 機能 | ブランチ名 |
|------|----------|
| ユーザー一覧画面 | feature/nomura-user-list |
| ユーザー詳細画面 | feature/nomura-user-detail |
| 問い合わせ一覧画面 | feature/nomura-contact-list |
| 問い合わせ詳細画面 | feature/nomura-contact-detail |

---

## ⚠️ 管理者機能について

管理者機能は `Admin` 名前空間で作成します。
ミドルウェアで管理者のみアクセス可能にする必要があります。

### 管理者判定の例（簡易版）
```php
// Member.phpに追加
public function isAdmin()
{
    return $this->ACCOUNT_STATUS == 9; // 9を管理者とする
}
```

---

## 1. ユーザー一覧画面

### 概要
全ユーザーの一覧表示（管理者用）

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout main
   git pull
   git checkout -b feature/nomura-user-list
   ```

2. **コントローラ作成**
   ```bash
   docker compose exec app php artisan make:controller Admin/UserController
   ```

3. **メソッド追加**
   ```php
   // app/Http/Controllers/Admin/UserController.php
   namespace App\Http\Controllers\Admin;

   use App\Http\Controllers\Controller;
   use App\Models\Member;

   class UserController extends Controller
   {
       public function index()
       {
           $users = Member::paginate(20);
           return view('admin.user.index', compact('users'));
       }
   }
   ```

4. **ルート追加**
   ```php
   // routes/web.php
   Route::prefix('admin')->middleware('auth')->group(function () {
       Route::get('/users', [Admin\UserController::class, 'index']);
   });
   ```

5. **ビュー作成**
   - ファイル: `resources/views/admin/user/index.blade.php`
   - ユーザー一覧テーブル（ID、名前、メール、ステータス）
   - 検索・フィルター機能

---

## 2. ユーザー詳細画面

### 概要
ユーザーの詳細情報と管理操作

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout -b feature/nomura-user-detail
   ```

2. **メソッド追加**
   ```php
   public function show($id)
   {
       $user = Member::with(['lands', 'rentalRecords'])->findOrFail($id);
       return view('admin.user.show', compact('user'));
   }
   
   // アカウント凍結
   public function suspend($id)
   {
       $user = Member::findOrFail($id);
       $user->update(['ACCOUNT_STATUS' => 1]); // 凍結
       return back()->with('success', 'アカウントを凍結しました');
   }
   
   // 凍結解除
   public function unsuspend($id)
   {
       $user = Member::findOrFail($id);
       $user->update(['ACCOUNT_STATUS' => 0]); // 通常
       return back()->with('success', '凍結を解除しました');
   }
   ```

3. **ルート追加**
   ```php
   Route::prefix('admin')->middleware('auth')->group(function () {
       Route::get('/users/{id}', [Admin\UserController::class, 'show']);
       Route::post('/users/{id}/suspend', [Admin\UserController::class, 'suspend']);
       Route::post('/users/{id}/unsuspend', [Admin\UserController::class, 'unsuspend']);
   });
   ```

---

## 3. 問い合わせ一覧画面

### 概要
ユーザーからの問い合わせ一覧

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout -b feature/nomura-contact-list
   ```

2. **コントローラ作成**
   ```bash
   docker compose exec app php artisan make:controller Admin/ContactController
   ```

3. **メソッド追加**
   ```php
   namespace App\Http\Controllers\Admin;

   use App\Http\Controllers\Controller;
   use App\Models\Contact;

   class ContactController extends Controller
   {
       public function index()
       {
           $contacts = Contact::with('member')
               ->orderBy('created_at', 'desc')
               ->paginate(20);
           
           return view('admin.contact.index', compact('contacts'));
       }
   }
   ```

4. **ルート追加**
   ```php
   Route::prefix('admin')->middleware('auth')->group(function () {
       Route::get('/contacts', [Admin\ContactController::class, 'index']);
   });
   ```

---

## 4. 問い合わせ詳細画面

### 概要
問い合わせの詳細と返信機能

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout -b feature/nomura-contact-detail
   ```

2. **メソッド追加**
   ```php
   public function show($id)
   {
       $contact = Contact::with(['member', 'replies'])->findOrFail($id);
       return view('admin.contact.show', compact('contact'));
   }
   
   public function reply(Request $request, $id)
   {
       $request->validate([
           'CONTENT' => 'required',
       ]);
       
       Reply::create([
           'CONTACT_ID' => $id,
           'CONTENT' => $request->CONTENT,
       ]);
       
       return back()->with('success', '返信しました');
   }
   ```

3. **ルート追加**
   ```php
   Route::prefix('admin')->middleware('auth')->group(function () {
       Route::get('/contacts/{id}', [Admin\ContactController::class, 'show']);
       Route::post('/contacts/{id}/reply', [Admin\ContactController::class, 'reply']);
   });
   ```

---

## フォルダ構成

```
resources/views/admin/
├── user/
│   ├── index.blade.php   ← ユーザー一覧
│   └── show.blade.php    ← ユーザー詳細
└── contact/
    ├── index.blade.php   ← 問い合わせ一覧
    └── show.blade.php    ← 問い合わせ詳細
```

---

## 作業完了後

```bash
git add .
git commit -m "ユーザー一覧画面（管理者）を実装"
git push origin feature/nomura-user-list
```

GitHubでプルリクエストを作成してください。
