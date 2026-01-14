# マイページ画面（user_my.blade.php）実装メモ

## 📝 未実装・今後の対応が必要な項目

### 1. ユーザーアイコンの初期値対応

**現在の実装**
```blade
@if(Auth::user()->ICON_IMAGE)
    <img src="{{ asset('storage/' . Auth::user()->ICON_IMAGE) }}" ...>
@else
    {{ mb_substr(Auth::user()->USERNAME ?? 'U', 0, 1) }}
@endif
```

**問題点**
- アイコン画像がない場合、ユーザー名の頭文字を表示する仮実装
- 本来は初期アイコン（デフォルトアイコン）を表示する予定

**対応方針**
- [ ] デフォルトアイコン画像を用意（例: `public/images/default-avatar.png`）
- [ ] ICON_IMAGEがnullまたは空の場合、デフォルトアイコンを表示するように修正

**修正例**
```blade
@if(Auth::user()->ICON_IMAGE && Auth::user()->ICON_IMAGE !== 'default_icon.png')
    <img src="{{ asset('storage/' . Auth::user()->ICON_IMAGE) }}" ...>
@else
    <img src="{{ asset('images/default-avatar.png') }}" ...>
@endif
```

---

### 2. アイコンURLの確定

**現在の実装**
```blade
{{ asset('storage/' . Auth::user()->ICON_IMAGE) }}
```

**未確定事項**
- [ ] ユーザーアイコンの保存先ディレクトリ
  - 現在: `storage/`（仮）
  - 候補: `storage/avatars/` または `storage/user_icons/`
- [ ] ファイル命名規則
  - 例: `{USER_ID}.jpg` または `{USER_ID}_{timestamp}.jpg`

**確認が必要な点**
1. アイコン画像のアップロード先
2. ファイルの保存形式（拡張子: jpg, png, webp など）
3. 画像サイズの制限
4. サムネイル生成の要否

---

## 🔧 要修正項目

### ボタンの画面遷移URL設定

**対象ボタン一覧**

| ボタン名 | 現在のURL | 遷移先画面 | 必要な情報 |
|---------|----------|----------|----------|
| プロフィール編集 | `#` | prof_custom.php | ルート名またはURL |
| 自己保持土地一覧 | `#` | my_land_list.php | ルート名またはURL |
| レンタル中一覧 | `#` | rental_list.php | ルート名またはURL |
| 取引完了一覧 | `#` | trade_fin_list.php | ルート名またはURL |
| 一覧を見る | `#` | （土地一覧？） | ルート名またはURL |

**修正に必要な情報**
1. 各画面のルート名（例: `route('profile.edit')`）
2. または各画面のURL（例: `/profile/edit`）
3. パラメータが必要な場合、そのパラメータ名

**例: ルート名がわかっている場合の修正**
```blade
<a href="{{ route('profile.edit') }}" class="btn btn-secondary">プロフィール編集</a>
<a href="{{ route('lands.my') }}" class="btn btn-secondary">自己保持土地一覧</a>
<a href="{{ route('rentals.index') }}" class="nav-card">レンタル中一覧</a>
<a href="{{ route('trades.completed') }}" class="nav-card">取引完了一覧</a>
```

---

## 📋 実装状況チェックリスト

### 完了済み
- [x] 基本レイアウトの実装
- [x] ユーザー情報の表示（USERNAME, SELF_INTRODUCTION）
- [x] 公開土地数カウントの表示
- [x] 公開中の土地カード一覧の表示
- [x] 土地カードの詳細情報表示（住所、料金、面積）
- [x] 都道府県コードの変換ロジック
- [x] レスポンシブ対応
- [x] 詳細コメントの追加

### 未対応
- [ ] ユーザーアイコンの初期値対応
- [ ] アイコンURLの確定
- [ ] 各ボタンの画面遷移URL設定
- [ ] 土地カードのリンク先設定（土地詳細画面へ）
- [ ] ♡ボタン（いいね機能）の実装
- [ ] エラーハンドリング（ユーザー情報が取得できない場合など）

---

## 🎨 UI改善提案

### 将来的な改善案
1. **プロフィール統計の拡充**
   - 現在: 公開土地数のみ
   - 追加候補: 貸出中の土地数、フォロワー数、レビュー数など

2. **土地カードへのフィルター・ソート機能**
   - 料金順、面積順、新着順など

3. **ページネーション**
   - 土地が多数になった場合の対応

4. **画像の遅延読み込み（Lazy Loading）**
   - パフォーマンス向上のため

---

## 🔗 関連ファイル

| ファイル | 役割 |
|---------|------|
| `app/Http/Controllers/UserController.php` | マイページのロジック |
| `resources/views/user_my.blade.php` | マイページのビュー |
| `routes/web.php` | ルート定義 |
| `app/Models/Member.php` | 会員モデル |
| `app/Models/Land.php` | 土地モデル |
| `context/user_my_review.md` | レビューメモ |

---

## 📝 実装時の注意点

1. **セキュリティ**
   - ユーザーアイコンのアップロード時にファイルタイプをチェック
   - XSS対策（Bladeの `{{ }}` は自動エスケープされる）

2. **パフォーマンス**
   - N+1問題に注意（土地データ取得時）
   - 画像のサイズ最適化

3. **アクセシビリティ**
   - alt属性の適切な設定
   - セマンティックHTML

---

**作成日**: 2025-12-29  
**最終更新**: 2025-12-29
