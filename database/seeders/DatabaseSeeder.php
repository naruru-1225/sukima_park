<?php

/**
 * ============================================================
 * アプリ全体シーダー (DatabaseSeeder.php)
 * ============================================================
 * 個別テーブルのシーダーを順序通りに呼び出すエントリーポイント。
 * 外部キー制約を一時的に無効化して truncate の順序依存を回避します。
 *
 * 【使い方】
 * docker compose exec laravel.test php artisan db:seed
 *
 * 【注意】
 * - 本番環境では実行しないでください
 * - 既存データは各シーダーで truncate されます
 * ============================================================
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // 基本テーブルを順序通りに投入
        $this->call([
            MemberTableSeeder::class,
            LandTableSeeder::class,
            ContactTableSeeder::class,
            ReplyTableSeeder::class,
            RentalRecordTableSeeder::class,
            ReviewCommentTableSeeder::class,
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
