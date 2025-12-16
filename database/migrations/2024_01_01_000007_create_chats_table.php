<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id_from');
            $table->unsignedBigInteger('user_id_to');
            $table->string('message', 512);
            $table->string('image', 2048)->nullable();
            $table->date('sent_date');
            $table->time('sent_time');
            $table->foreign('user_id_from')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('user_id_to')->references('id')->on('members')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
