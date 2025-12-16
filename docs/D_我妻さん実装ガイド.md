# D 我妻さん 実装ガイド

このファイルは我妻さんが担当する機能の実装手順です。

---

## 担当機能一覧

| 機能 | ブランチ名 |
|------|----------|
| プロフィール編集画面 | feature/azuma-profile-edit |
| プロフィール確認画面 | feature/azuma-profile-show |
| DM一覧画面 | feature/azuma-dm-list |
| DM画面 | feature/azuma-dm-chat |

---

## 1. プロフィール編集画面

### 概要
ログインユーザーが自分のプロフィールを編集

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout main
   git pull
   git checkout -b feature/azuma-profile-edit
   ```

2. **コントローラ作成**
   ```bash
   docker compose exec app php artisan make:controller ProfileController
   ```

3. **メソッド追加**
   ```php
   public function edit()
   {
       $user = Auth::user();
       return view('profile.edit', compact('user'));
   }
   
   public function update(Request $request)
   {
       $user = Auth::user();
       
       $request->validate([
           'USERNAME' => 'required|max:255',
           'SELF_INTRODUCTION' => 'nullable|max:1000',
       ]);
       
       $user->update([
           'USERNAME' => $request->USERNAME,
           'TEL' => $request->TEL,
           'SELF_INTRODUCTION' => $request->SELF_INTRODUCTION,
           'SHOW_BIRTH' => $request->has('SHOW_BIRTH'),
           'SHOW_GENDER' => $request->has('SHOW_GENDER'),
       ]);
       
       return redirect('/profile')->with('success', '更新しました');
   }
   ```

4. **ルート追加**
   ```php
   Route::middleware('auth')->group(function () {
       Route::get('/profile/edit', [ProfileController::class, 'edit']);
       Route::put('/profile', [ProfileController::class, 'update']);
   });
   ```

---

## 2. プロフィール確認画面

### 概要
自分のプロフィールを確認

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout -b feature/azuma-profile-show
   ```

2. **メソッド追加**
   ```php
   public function show()
   {
       $user = Auth::user();
       return view('profile.show', compact('user'));
   }
   ```

3. **ルート追加**
   ```php
   Route::get('/profile', [ProfileController::class, 'show'])->middleware('auth');
   ```

---

## 3. DM一覧画面

### 概要
ダイレクトメッセージの相手一覧を表示

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout -b feature/azuma-dm-list
   ```

2. **コントローラ作成**
   ```bash
   docker compose exec app php artisan make:controller ChatController
   ```

3. **メソッド追加**
   ```php
   public function index()
   {
       $userId = Auth::id();
       
       // 自分が送信または受信したチャットの相手一覧
       $chats = Chat::where('USER_ID_FROM', $userId)
           ->orWhere('USER_ID_TO', $userId)
           ->orderBy('SEND_DATE', 'desc')
           ->get()
           ->groupBy(function($chat) use ($userId) {
               return $chat->USER_ID_FROM == $userId 
                   ? $chat->USER_ID_TO 
                   : $chat->USER_ID_FROM;
           });
       
       return view('chat.index', compact('chats'));
   }
   ```

4. **ルート追加**
   ```php
   Route::middleware('auth')->group(function () {
       Route::get('/chats', [ChatController::class, 'index']);
   });
   ```

---

## 4. DM画面

### 概要
特定ユーザーとのチャット画面

### 実装手順

1. **ブランチ作成**
   ```bash
   git checkout -b feature/azuma-dm-chat
   ```

2. **メソッド追加**
   ```php
   public function show($userId)
   {
       $myId = Auth::id();
       $partner = Member::findOrFail($userId);
       
       $messages = Chat::where(function($q) use ($myId, $userId) {
           $q->where('USER_ID_FROM', $myId)->where('USER_ID_TO', $userId);
       })->orWhere(function($q) use ($myId, $userId) {
           $q->where('USER_ID_FROM', $userId)->where('USER_ID_TO', $myId);
       })->orderBy('SEND_DATE')->get();
       
       return view('chat.show', compact('partner', 'messages'));
   }
   
   public function store(Request $request, $userId)
   {
       Chat::create([
           'USER_ID_FROM' => Auth::id(),
           'USER_ID_TO' => $userId,
           'CONTENT' => $request->CONTENT,
           'SEND_DATE' => now(),
       ]);
       
       return back();
   }
   ```

3. **ルート追加**
   ```php
   Route::middleware('auth')->group(function () {
       Route::get('/chats/{user}', [ChatController::class, 'show']);
       Route::post('/chats/{user}', [ChatController::class, 'store']);
   });
   ```

---

## 作業完了後

```bash
git add .
git commit -m "プロフィール編集画面を実装"
git push origin feature/azuma-profile-edit
```

GitHubでプルリクエストを作成してください。
