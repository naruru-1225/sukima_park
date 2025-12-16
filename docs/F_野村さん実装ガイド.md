# F 野村さん 実装ガイド（管理者機能）

## 担当画面

| 画面名 | ブランチ名 |
|------|----------|
| ユーザ一覧画面 | feature/nomura-user-list |
| ユーザ詳細画面 | feature/nomura-user-detail |
| 問い合わせ一覧画面 | feature/nomura-contact-list |
| 問い合わせ詳細画面 | feature/nomura-contact-detail |

---

## 毎日の作業の流れ

### 作業を始める前に

1. **プロジェクトフォルダに移動する**
   - VSCodeでsukimaparkフォルダを開きます

2. **最新のコードを取得する**
   - ターミナルで `git pull` を実行します
   - 他のメンバーの変更を自分のPCに反映させます

3. **Dockerを起動する**
   - ターミナルで `docker compose up -d` を実行します
   - これでLaravelが動くようになります

4. **ブラウザで確認する**
   - `http://localhost` を開いて、サイトが表示されればOKです

### 作業中

- こまめにコミットしましょう
- `git add .` で変更をステージング
- `git commit -m "何を変更したか"` で保存

### 作業を終わるとき

1. コミットする（まだしていなければ）
2. `git push` でGitHubにアップロード
3. `docker compose down` でDockerを停止
4. `wsl --shutdown` でWSLをシャットダウン（重要！）

---

## 管理者機能について

管理者機能は一般ユーザーからは見えない、運営用の画面です。

**特徴**
- URLは `/admin/〜` で始まります
- コントローラは `Admin` フォルダの中に作ります
- ビューも `admin` フォルダの中に作ります
- ログイン必須の画面です

---

## 各画面の作り方

### ユーザ一覧画面

**何をする画面？**
登録されている全ユーザーの一覧を表示します。

**参考にするモック**
`context/画面レイアウト/admin_user_list_screen.html` を開いて、デザインを確認してください。

**作成の流れ**
1. ブランチを作成する（`git checkout -b feature/nomura-user-list`）
2. Adminフォルダ内にUserControllerを作成する
3. ユーザー一覧表示用のメソッドを作る
4. MEMBER_TABLEから全ユーザーを取得
5. テーブル形式で表示（ID、名前、メール、ステータス）
6. ページネーション（20件ずつなど）
7. 検索機能があると便利（任意）
8. 各ユーザーをクリックで詳細画面へ

---

### ユーザ詳細画面

**何をする画面？**
ユーザーの詳細情報と管理操作（アカウント凍結など）を行います。

**参考にするモック**
`context/画面レイアウト/admin_user_detail_screen.html` を確認してください。

**作成の流れ**
1. ブランチを作成する（`git checkout -b feature/nomura-user-detail`）
2. UserControllerにユーザー詳細用のメソッドを追加
3. 表示内容：
   - ユーザー情報（名前、メール、登録日など）
   - このユーザーが所有している土地一覧
   - このユーザーの取引履歴
4. 管理操作：
   - 「アカウント凍結」ボタン（ACCOUNT_STATUS=1に更新）
   - 「凍結解除」ボタン（ACCOUNT_STATUS=0に更新）

---

### 問い合わせ一覧画面

**何をする画面？**
ユーザーからの問い合わせ一覧を表示します。

**参考にするモック**
`context/画面レイアウト/admin_contact_list_screen.html` を確認してください。

**作成の流れ**
1. ブランチを作成する（`git checkout -b feature/nomura-contact-list`）
2. Adminフォルダ内にContactControllerを作成する
3. 問い合わせ一覧表示用のメソッドを作る
4. CONTACT_TABLEから全問い合わせを取得
5. 新しい順に表示
6. 未対応/対応済みのフィルター機能
7. 各問い合わせをクリックで詳細画面へ

---

### 問い合わせ詳細画面

**何をする画面？**
問い合わせの詳細を表示し、返信します。

**参考にするモック**
`context/画面レイアウト/admin_contact_detail_screen.html` を確認してください。

**作成の流れ**
1. ブランチを作成する（`git checkout -b feature/nomura-contact-detail`）
2. ContactControllerに詳細表示と返信用のメソッドを追加
3. 表示内容：
   - 問い合わせ内容（タイトル、本文、送信日時）
   - 送信者情報
   - 過去の返信履歴
4. 返信フォームを設置
5. 送信するとREPLY_TABLEに保存

---

## フォルダ構成

管理者機能のファイルは以下の場所に作ります：

**コントローラ**
```
app/Http/Controllers/Admin/
├── UserController.php
└── ContactController.php
```

**ビュー**
```
resources/views/admin/
├── user/
│   ├── index.blade.php （一覧）
│   └── show.blade.php  （詳細）
└── contact/
    ├── index.blade.php （一覧）
    └── show.blade.php  （詳細）
```

---

## 困ったときは

- **TEAM_SETUP.md** を読み返す
- **トップ画面の作成フロー.md** を参考にする
- リーダーに聞く
- GitHubのコードを参考にする

---

## 作業完了後

各画面の実装が終わったら、GitHubでプルリクエストを作成してください。
