<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * MEMBER_TABLEにremember_tokenカラムを追加
     * 「ログイン状態を保持する」機能に必要
     */
    public function up(): void
    {
        Schema::table('MEMBER_TABLE', function (Blueprint $table) {
            $table->rememberToken()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('MEMBER_TABLE', function (Blueprint $table) {
            $table->dropRememberToken();
        });
    }
};
