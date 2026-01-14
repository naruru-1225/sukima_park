<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * テストユーザーを作成
     */
    public function run(): void
    {
        // init.sqlのMEMBER_TABLEに存在するカラムのみ使用
        Member::create([
            'EMAIL' => 'test@example.com',
            'PASSWORD' => Hash::make('password123'),
            'TEL' => '090-1234-5678',
            'BIRTH' => '1990-01-01',
            'SHOW_BIRTH' => false,
            'GENDER' => 0,
            'SHOW_GENDER' => false,
            'IDENTITY' => 'test.jpg',
            'USERNAME' => 'テストユーザー',
        ]);
    }
}

