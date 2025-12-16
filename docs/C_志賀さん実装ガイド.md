# C 志賀さん 実装ガイド

## 担当画面

| 画面名 | ブランチ名 |
|------|----------|
| ユーザ画面(自アカウント) | feature/shiga-user-self |
| ユーザ画面(他アカウント) | feature/shiga-user-other |
| トップ画面 | ✅ リーダー実装済み |
| 自己保持土地一覧画面 | feature/shiga-my-lands |
| 土地貸出画面 | feature/shiga-rental-lend |
| 貸出中詳細画面 | feature/shiga-lending-detail |

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

### ユーザ画面（自アカウント / 他アカウント）

**何をする画面？**
ユーザーのプロフィールを表示します。自分と他人で表示内容が少し違います。

**参考にするモック**
- `context/画面レイアウト/my_profile_screen.html`（自分）
- `context/画面レイアウト/user_profile_screen.html`（他人）

**作成の流れ**
1. ブランチを作成する
2. UserControllerを作成する
3. プロフィール表示用のメソッドを作る
4. URLにあるユーザーIDでMEMBER_TABLEから情報を取得
5. ログインユーザーと表示ユーザーが同じかどうかを判定
6. 自分の場合：「編集」ボタンを表示
7. 他人の場合：「DMを送る」ボタンを表示
8. 同じビューファイルで条件分岐させてもOK

---

### 自己保持土地一覧画面

**何をする画面？**
ログインユーザーが所有している土地の一覧を表示します。

**参考にするモック**
`context/画面レイアウト/my_lands_list_screen.html` を確認してください。

**作成の流れ**
1. ブランチを作成する（`git checkout -b feature/shiga-my-lands`）
2. LandControllerに自分の土地一覧用のメソッドを追加
3. この画面はログイン必須
4. ログインユーザーのIDでLAND_TABLEを絞り込む
5. 各土地に「編集」「削除」ボタンを表示
6. 土地がない場合は「土地を登録しましょう」メッセージを表示

---

### 土地貸出画面

**何をする画面？**
自分の土地へのレンタル申請一覧を表示し、承認または拒否します。

**参考にするモック**
`context/画面レイアウト/listed_lands_screen.html` を確認してください。

**作成の流れ**
1. ブランチを作成する（`git checkout -b feature/shiga-rental-lend`）
2. RentalControllerに貸出管理用のメソッドを追加
3. 自分の土地に対する申請（STATUS=申請中）を取得
4. 申請者情報、希望期間などを表示
5. 「承認」ボタンでSTATUSを承認済みに更新
6. 「拒否」ボタンでSTATUSを拒否に更新

---

### 貸出中詳細画面

**何をする画面？**
現在貸し出し中の土地の詳細情報を表示します。

**参考にするモック**
`context/画面レイアウト/my_land_detail_screen.html` を確認してください。

**作成の流れ**
1. ブランチを作成する（`git checkout -b feature/shiga-lending-detail`）
2. RentalControllerに貸出中詳細用のメソッドを追加
3. RENTAL_RECORD_TABLEから該当レコードを取得
4. 表示内容：土地情報、借り手情報、レンタル期間、ステータス
5. 「取引完了」ボタンを設置（任意）

---

## 困ったときは

- **TEAM_SETUP.md** を読み返す
- **トップ画面の作成フロー.md** を参考にする
- リーダーに聞く
- GitHubのコードを参考にする

---

## 作業完了後

各画面の実装が終わったら、GitHubでプルリクエストを作成してください。
