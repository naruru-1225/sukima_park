<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 128);
            $table->string('message', 1024);
            $table->unsignedBigInteger('user_id');
            $table->date('date');
            $table->tinyInteger('status')->default(0);
            $table->foreign('user_id')->references('id')->on('members')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
