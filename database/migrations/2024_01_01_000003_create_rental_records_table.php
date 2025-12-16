<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_records', function (Blueprint $table) {
            $table->id();
            $table->integer('price');
            $table->tinyInteger('price_unit');
            $table->date('rental_start_date');
            $table->date('rental_end_date');
            $table->time('rental_start_time');
            $table->time('rental_end_time');
            $table->unsignedBigInteger('land_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('land_id')->references('id')->on('lands')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('members')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_records');
    }
};
