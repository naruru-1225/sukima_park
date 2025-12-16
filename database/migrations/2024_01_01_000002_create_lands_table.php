<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('LAND_TABLE', function (Blueprint $table) {
            $table->integer('LAND_ID')->autoIncrement();
            $table->integer('PEREFECTURES');
            $table->string('CITY', 256);
            $table->string('STREET_ADDRESS', 256);
            $table->decimal('AREA', 5, 2);
            $table->integer('USER_ID');
            $table->foreign('USER_ID')->references('USER_ID')->on('MEMBER_TABLE');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('LAND_TABLE');
    }
};
