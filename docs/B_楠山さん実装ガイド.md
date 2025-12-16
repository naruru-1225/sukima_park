# B 楠山さん 実装ガイド

このファイルは楠山さんが担当する機能の実装手順です。

---

## 担当機能一覧

| 機能 | ブランチ名 |
|------|----------|
| 会員登録画面 | feature/kusuyama-register |
| ログイン画面 | feature/kusuyama-login |
| 土地登録画面 | feature/kusuyama-land-register |
| 土地登録確認画面 | feature/kusuyama-land-confirm |

---

## 1. 会員登録画面

### 概要
新規ユーザーの会員登録フォーム

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout main
   git pull
   git checkout -b feature/kusuyama-register
   ```

2. **ビュー作成**
   - ファイル: `resources/views/auth/register.blade.php`
   - 入力項目: ユーザー名、メール、パスワード、電話番号、生年月日、性別

3. **コントローラ編集**
   ```php
   // app/Http/Controllers/AuthController.php（既存）
   public function showRegisterForm()
   {
       return view('auth.register');
   }
   
   // registerメソッドは既存のものを使用
   ```

4. **ビューの内容**
   ```html
   @extends('layouts.app')
   @section('title', '新規登録')
   @section('content')
   <div class="auth-container">
       <div class="card">
           <div class="card-header"><h1>新規登録</h1></div>
           <div class="card-body">
               <form action="{{ url('/register') }}" method="POST">
                   @csrf
                   <!-- フォーム項目 -->
               </form>
           </div>
       </div>
   </div>
   @endsection
   ```

### チェックポイント
- [ ] バリデーションが動作する
- [ ] 登録後にログイン状態になる
- [ ] エラーメッセージが表示される

---

## 2. ログイン画面

### 概要
既存ユーザーのログインフォーム

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout -b feature/kusuyama-login
   ```

2. **ビュー作成**
   - ファイル: `resources/views/auth/login.blade.php`
   - 入力項目: メール、パスワード、ログイン状態保持

3. **ビューの内容**
   ```html
   @extends('layouts.app')
   @section('title', 'ログイン')
   @section('content')
   <form action="{{ url('/login') }}" method="POST">
       @csrf
       <div class="form-group">
           <label class="form-label">メールアドレス</label>
           <input type="email" name="email" class="form-input" required>
       </div>
       <div class="form-group">
           <label class="form-label">パスワード</label>
           <input type="password" name="password" class="form-input" required>
       </div>
       <button type="submit" class="btn btn-primary">ログイン</button>
   </form>
   @endsection
   ```

---

## 3. 土地登録画面

### 概要
ログインユーザーが自分の土地を登録するフォーム

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout -b feature/kusuyama-land-register
   ```

2. **コントローラ作成**
   ```bash
   docker compose exec app php artisan make:controller LandController
   ```

3. **メソッド追加**
   ```php
   public function create()
   {
       return view('land.create');
   }
   
   public function store(Request $request)
   {
       $request->validate([
           'PREFECTURES' => 'required',
           'CITY' => 'required',
           'STREET_ADDRESS' => 'required',
           'AREA' => 'required|numeric',
       ]);
       
       Land::create([
           'USER_ID' => Auth::id(),
           'PREFECTURES' => $request->PREFECTURES,
           'CITY' => $request->CITY,
           'STREET_ADDRESS' => $request->STREET_ADDRESS,
           'AREA' => $request->AREA,
           'PRICE' => $request->PRICE,
           'DESCRIPTION' => $request->DESCRIPTION,
           'STATUS' => 0,
       ]);
       
       return redirect('/my-lands')->with('success', '土地を登録しました');
   }
   ```

4. **ルート追加**
   ```php
   Route::middleware('auth')->group(function () {
       Route::get('/lands/create', [LandController::class, 'create']);
       Route::post('/lands', [LandController::class, 'store']);
   });
   ```

---

## 4. 土地登録確認画面

### 概要
土地登録前の確認画面

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout -b feature/kusuyama-land-confirm
   ```

2. **メソッド追加**
   ```php
   public function confirm(Request $request)
   {
       $data = $request->all();
       return view('land.confirm', compact('data'));
   }
   ```

3. **ビュー作成**
   - 入力内容の確認表示
   - 「登録」「戻る」ボタン

---

## 作業完了後

```bash
git add .
git commit -m "会員登録画面を実装"
git push origin feature/kusuyama-register
```

GitHubでプルリクエストを作成してください。
