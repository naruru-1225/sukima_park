# B 楠山さん 実装ガイド

## 担当画面

| 画面名 | ブランチ名 |
|------|----------|
| 会員登録画面 | feature/kusuyama-register |
| ログイン画面 | feature/kusuyama-login |
| 土地登録画面 | feature/kusuyama-land-register |
| 土地登録確認画面 | feature/kusuyama-land-confirm |

---

## 各画面の実装方針

### 会員登録画面
新規ユーザーの登録フォームを作成します。AuthControllerのshowRegisterFormとregisterメソッドを使用します。入力項目：ユーザー名、メール、パスワード、電話番号、生年月日、性別。

### ログイン画面
ログインフォームを作成します。AuthControllerのshowLoginFormとloginメソッドを使用します。入力項目：メール、パスワード、ログイン状態保持チェックボックス。

### 土地登録画面
ログインユーザーが土地を登録するフォームです。LandControllerを作成し、create/storeメソッドを実装します。入力項目：都道府県、市区町村、住所、面積、料金、説明、画像。

### 土地登録確認画面
土地登録前の確認画面です。入力内容を表示し、確定ボタンでLAND_TABLEに保存します。

---

## 作業の流れ

1. mainブランチからfeatureブランチを作成
2. コントローラ作成・ルート設定
3. ビュー作成（context/画面レイアウトのHTMLモックを参考に）
4. コミット・プッシュしてPR作成
