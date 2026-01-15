<?php

/**
 * ============================================================
 * 会員テーブルシーダー (MemberTableSeeder.php)
 * ============================================================
 * MEMBER_TABLE を初期化して、ユーザA/Bを投入するシーダー。
 * パスワードは指定の平文をハッシュ化して保存する。
 *
 * 【使い方】
 * docker compose exec laravel.test php artisan db:seed --class=MemberTableSeeder
 *
 * 【注意】
 * - 本番環境では実行しないでください
 * - 既存データは truncate されます
 * ============================================================
 */

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MemberTableSeeder extends Seeder
{
    public function run(): void
    {
        // 既存データをクリア
        DB::table('MEMBER_TABLE')->truncate();

        Member::create([
            'EMAIL' => 'userA@example.com',
            'PASSWORD' => Hash::make('password123A'),
            'TEL' => '090-0000-0001',
            'BIRTH' => '1990-01-15',
            'SHOW_BIRTH' => true,
            'GENDER' => 0,
            'SHOW_GENDER' => true,
            'IDENTITY' => 'id_card_a.png',
            'USERNAME' => 'User A',
            'SELF_INTRODUCTION' => 'Seeder user A',
            'ICON_IMAGE' => 'default_icon.png',
            'ACCOUNT_STATUS' => 0,
        ]);

        Member::create([
            'EMAIL' => 'userB@example.com',
            'PASSWORD' => Hash::make('password123B'),
            'TEL' => '090-0000-0002',
            'BIRTH' => '1992-05-20',
            'SHOW_BIRTH' => true,
            'GENDER' => 1,
            'SHOW_GENDER' => true,
            'IDENTITY' => 'id_card_b.png',
            'USERNAME' => 'User B',
            'SELF_INTRODUCTION' => 'Seeder user B',
            'ICON_IMAGE' => 'default_icon.png',
            'ACCOUNT_STATUS' => 0,
        ]);
    }
}
