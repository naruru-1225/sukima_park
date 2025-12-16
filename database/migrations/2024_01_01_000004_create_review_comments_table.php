<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review_comments', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('land_review');
            $table->string('land_comment', 512)->nullable();
            $table->tinyInteger('user_review');
            $table->string('user_comment', 512)->nullable();
            $table->date('date');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('land_id');
            $table->unsignedBigInteger('record_id');
            $table->foreign('user_id')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('land_id')->references('id')->on('lands')->onDelete('cascade');
            $table->foreign('record_id')->references('id')->on('rental_records')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_comments');
    }
};
