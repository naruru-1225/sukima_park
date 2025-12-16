<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lands', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('prefectures');
            $table->string('city', 256);
            $table->string('street_address', 256);
            $table->decimal('area', 5, 2);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('members')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lands');
    }
};
