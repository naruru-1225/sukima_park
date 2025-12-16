# E 三輪さん 実装ガイド

## 担当画面

| 画面名 | ブランチ名 |
|------|----------|
| レンタル中一覧画面 | feature/miwa-rental-list |
| レンタル中詳細画面 | feature/miwa-rental-detail |
| レビュー画面 | feature/miwa-review |
| 取引完了一覧画面 | feature/miwa-completed-list |
| 取引完了詳細画面 | feature/miwa-completed-detail |

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

## 各画面の作り方

### レンタル中一覧画面

**何をする画面？**
自分がレンタル中の土地一覧を表示します。

**参考にするモック**
`context/画面レイアウト/active_rental_list_screen.html` を開いて、デザインを確認してください。

**作成の流れ**
1. ブランチを作成する（`git checkout -b feature/miwa-rental-list`）
2. RentalControllerを作成する（または既存のものに追加）
3. レンタル一覧表示用のメソッドを作る
4. この画面はログイン必須
5. RENTAL_RECORD_TABLEから自分のレンタル記録（承認済み）を取得
6. 各土地の情報（名前、場所、期間）をカード形式で表示
7. クリックで詳細画面へ

---

### レンタル中詳細画面

**何をする画面？**
レンタル中の土地の詳細情報を表示します。

**参考にするモック**
`context/画面レイアウト/active_rental_detail_screen.html` を確認してください。

**作成の流れ**
1. ブランチを作成する（`git checkout -b feature/miwa-rental-detail`）
2. RentalControllerにレンタル詳細用のメソッドを追加
3. URLにあるレンタルIDでRENTAL_RECORD_TABLEから情報を取得
4. 表示内容：土地情報、オーナー情報、レンタル期間
5. 「返却する（取引完了）」ボタンを設置
6. オーナーへのDMボタンを設置

---

### レビュー画面

**何をする画面？**
取引完了後にレビューを投稿する画面です。

**参考にするモック**
`context/画面レイアウト/submit_review_screen.html` を確認してください。

**作成の流れ**
1. ブランチを作成する（`git checkout -b feature/miwa-review`）
2. ReviewControllerを作成する
3. レビューフォーム表示用と投稿処理用のメソッドを作る
4. この画面はログイン必須
5. フォームの入力項目：
   - 評価（1〜5の星）
   - コメント（テキストエリア）
6. 送信するとREVIEW_COMMENT_TABLEに保存

---

### 取引完了一覧画面

**何をする画面？**
完了した取引（レンタル終了）の一覧を表示します。

**参考にするモック**
`context/画面レイアウト/rental_history_list_screen.html` を確認してください。

**作成の流れ**
1. ブランチを作成する（`git checkout -b feature/miwa-completed-list`）
2. RentalControllerに取引完了一覧用のメソッドを追加
3. RENTAL_RECORD_TABLEから自分の完了記録（STATUS=完了）を取得
4. 各取引の情報（土地名、期間、金額）を一覧表示
5. クリックで詳細画面へ

---

### 取引完了詳細画面

**何をする画面？**
完了した取引の詳細情報を表示します。

**参考にするモック**
`context/画面レイアウト/rental_history_detail_screen.html` を確認してください。

**作成の流れ**
1. ブランチを作成する（`git checkout -b feature/miwa-completed-detail`）
2. RentalControllerに取引完了詳細用のメソッドを追加
3. 表示内容：土地情報、取引期間、支払い金額
4. レビュー投稿済みの場合：レビュー内容を表示
5. レビュー未投稿の場合：「レビューを書く」ボタンを表示

---

## 困ったときは

- **TEAM_SETUP.md** を読み返す
- **トップ画面の作成フロー.md** を参考にする
- リーダーに聞く
- GitHubのコードを参考にする

---

## 作業完了後

各画面の実装が終わったら、GitHubでプルリクエストを作成してください。
