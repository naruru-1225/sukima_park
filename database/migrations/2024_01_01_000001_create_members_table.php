<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 会員テーブル (members)
     * Laravel標準のusersテーブルに追加フィールドを持つ
     */
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('tel', 64);
            $table->date('birth');
            $table->boolean('show_birth')->default(false);
            $table->tinyInteger('gender');
            $table->boolean('show_gender')->default(false);
            $table->string('identity', 1024);
            $table->string('username', 128);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
