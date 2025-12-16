# A 小島さん 実装ガイド

## 担当画面

| 画面名 | ブランチ名 |
|------|----------|
| 問い合わせ画面 | feature/kojima-contact |
| 検索結果一覧画面 | feature/kojima-search-result |
| 土地詳細画面 | feature/kojima-land-detail |
| レンタル確認画面 | feature/kojima-rental-confirm |

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

### 問い合わせ画面

**何をする画面？**
ユーザーがサイトへの問い合わせを送信するフォームです。

**参考にするモック**
`context/画面レイアウト/contact_form_screen.html` を開いて、デザインを確認してください。

**作成の流れ**
1. ブランチを作成する（`git checkout -b feature/kojima-contact`）
2. ContactControllerを作成する（artisanコマンドを使う）
3. フォーム表示用のメソッドと、送信処理用のメソッドを作る
4. ルートを設定する（URLとコントローラを紐付ける）
5. ビューを作成する（HTMLを書く）
6. フォームにはタイトルと内容の入力欄を設置
7. 送信ボタンを押すとCONTACT_TABLEに保存される

---

### 検索結果一覧画面

**何をする画面？**
トップ画面の検索フォームから送られた条件で土地を検索し、結果を一覧表示します。

**参考にするモック**
`context/画面レイアウト/search_results_screen.html` を確認してください。

**作成の流れ**
1. ブランチを作成する（`git checkout -b feature/kojima-search-result`）
2. LandControllerを作成する
3. 検索処理用のメソッドを作る
4. 検索条件（都道府県、市区町村、料金、面積）でLAND_TABLEを絞り込む
5. 結果をページネーション（12件ずつなど）で表示
6. 各土地をカード形式で表示し、クリックで詳細画面へ

---

### 土地詳細画面

**何をする画面？**
選んだ土地の詳細情報を表示します。

**参考にするモック**
`context/画面レイアウト/land_detail_screen.html` を確認してください。

**作成の流れ**
1. ブランチを作成する（`git checkout -b feature/kojima-land-detail`）
2. LandControllerに詳細表示用のメソッドを追加
3. URLにある土地IDを使ってLAND_TABLEから情報を取得
4. 土地の住所、面積、料金、説明を表示
5. 所有者情報を表示
6. 過去のレビュー一覧を表示
7. ログインユーザーには「レンタル申請」ボタンを表示

---

### レンタル確認画面

**何をする画面？**
レンタル申請する前の最終確認画面です。

**参考にするモック**
`context/画面レイアウト/booking_confirmation_screen.html` を確認してください。

**作成の流れ**
1. ブランチを作成する（`git checkout -b feature/kojima-rental-confirm`）
2. RentalControllerを作成する
3. 確認画面表示用と申請処理用のメソッドを作る
4. この画面はログイン必須（ミドルウェアで制限）
5. 申請内容（土地情報、期間など）を表示
6. 「申請する」ボタンでRENTAL_RECORD_TABLEに保存

---

## 困ったときは

- **TEAM_SETUP.md** を読み返す
- **トップ画面の作成フロー.md** を参考にする
- リーダーに聞く
- GitHubのコードを参考にする

---

## 作業完了後

各画面の実装が終わったら、GitHubでプルリクエストを作成してください。
