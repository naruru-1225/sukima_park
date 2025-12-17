# Docker環境の変更について（重要）

> ⚠️ **2024年12月17日にDocker環境が変更されました**
>
> 全てのチームメンバーは、この手順に従って環境を更新してください。

---

## 📋 変更の概要

| 項目 | 以前 | 現在 |
|------|------|------|
| コンテナ名 | `app` | `laravel.test` |
| ベースイメージ | `php:8.4-apache` | Laravel Sail（Ubuntu + PHP） |
| Webサーバー | Apache | PHP内蔵サーバー |
| 問題点 | コンテナ再起動で設定が壊れる | なし（安定） |

---

## 🔧 何が変わったのか（技術的な説明）

### 以前の問題点

以前の `docker-compose.yml` では、コンテナ起動時に毎回以下のコマンドが実行されていました：

```yaml
# 以前のdocker-compose.yml（問題のあった設定）
command: >
    bash -c "docker-php-ext-install pdo pdo_mysql &&
    a2enmod rewrite &&
    sed -ri -e 's!/var/www/html!/var/www/html/public!g' ... &&
    apache2-foreground"
```

**問題**: `sed` コマンドが毎回実行されるため、コンテナを再起動すると設定が壊れました。

```
1回目の起動: /var/www/html → /var/www/html/public ✅ 正常
2回目の起動: /var/www/html/public → /var/www/html/public/public ❌ 壊れる
```

これが原因で「403 Forbidden」エラーが発生していました。

### 新しい構成

新しい `docker-compose.yml` では、Laravel Sail の標準イメージを使用しています：

```yaml
# 新しいdocker-compose.yml
services:
    laravel.test:
        build:
            context: './vendor/laravel/sail/runtimes/8.4'
            dockerfile: Dockerfile
        image: 'sail-8.4/app'
        ...
```

**改善点**:
- **イメージビルド時に設定完了**: sed コマンドの問題がない
- **何度再起動しても安定**: stop/start を繰り返しても壊れない
- **Laravel公式サポート**: Sail は Laravel 公式の開発環境ツール
- **Vite対応**: ポート5173でホットリロードが使える

### ファイルの変更点

変更されたファイルは `docker-compose.yml` のみです：

```diff
services:
-    app:
-        image: php:8.4-apache
-        command: "bash -c \"docker-php-ext-install pdo..."
+    laravel.test:
+        build:
+            context: './vendor/laravel/sail/runtimes/8.4'
+            dockerfile: Dockerfile
+        image: 'sail-8.4/app'
```

---

## 🔴 既存のコンテナがある方（更新手順）

### Step 1: 古いコンテナを削除

```powershell
# プロジェクトフォルダに移動
cd sukimapark

# コンテナを停止して削除
docker compose down

# 古いイメージも削除（推奨）
docker image rm php:8.4-apache
```

### Step 2: 最新のコードを取得

```powershell
git pull
```

### Step 3: 新しいコンテナをビルド＆起動

```powershell
# 初回はビルドが必要（5〜10分かかります）
docker compose up -d --build
```

### Step 4: 動作確認

- http://localhost → トップ画面が表示されればOK
- http://localhost:8080 → phpMyAdmin

---

## 🟢 新しい環境での起動方法

### 毎日の作業開始

```powershell
cd sukimapark
git pull
docker compose up -d
```

> 💡 **ポイント**: 2回目以降は `--build` は不要です

### 毎日の作業終了

```powershell
git add .
git commit -m "作業内容"
git push
docker compose down
wsl --shutdown
```

---

## 📝 コマンドの変更点

### artisanコマンド

```powershell
# 以前
docker compose exec app php artisan migrate

# 現在（方法1: docker compose）
docker compose exec laravel.test php artisan migrate

# 現在（方法2: Sailコマンド）★推奨
./vendor/bin/sail artisan migrate
```

### composerコマンド

```powershell
# 以前
docker run --rm -v "${PWD}:/app" -w /app composer install

# 現在
docker compose exec laravel.test composer install

# または
./vendor/bin/sail composer install
```

### その他のコマンド

| コマンド | 以前 | 現在（Sail推奨） |
|---------|------|-----------------|
| マイグレーション | `docker compose exec app php artisan migrate` | `./vendor/bin/sail artisan migrate` |
| キー生成 | `docker compose exec app php artisan key:generate` | `./vendor/bin/sail artisan key:generate` |
| Tinker | `docker compose exec app php artisan tinker` | `./vendor/bin/sail tinker` |
| NPM install | - | `./vendor/bin/sail npm install` |

---

## 🆘 トラブルシューティング

### 「コンテナが見つからない」エラー

```powershell
# 古いコンテナが残っている可能性があります
docker compose down
docker compose up -d --build
```

### 「ビルドに失敗する」

```powershell
# 1. Dockerキャッシュをクリア
docker builder prune -f

# 2. 再度ビルド
docker compose up -d --build
```

### 「ポートが使用中」エラー

```powershell
# 他のコンテナが動いている可能性
docker ps -a
docker stop $(docker ps -aq)
docker compose up -d
```

### データベースをリセットしたい

```powershell
# 注意: データが全て消えます
docker compose down --volumes
docker compose up -d --build
./vendor/bin/sail artisan migrate
```

---

## ❓ よくある質問

### Q: `./vendor/bin/sail` と `docker compose` どちらを使えばいい？

**A: どちらでもOKです。**

- `./vendor/bin/sail` は Docker Compose のラッパーなので、同じことができます
- `sail` の方がコマンドが短くて便利です

### Q: 以前のコンテナに戻せる？

**A: 推奨しません。**

- 以前のコンテナは再起動時に設定が壊れる問題がありました
- 新しいSail環境を使ってください

### Q: ビルドに時間がかかるのは1回だけ？

**A: はい。**

- 初回のみ5〜10分かかります
- 2回目以降は `docker compose up -d` だけで数秒で起動します
