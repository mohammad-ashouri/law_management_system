<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('refers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('law_from');
            $table->foreign('law_from')->references('id')->on('laws');
            $table->unsignedBigInteger('law_to');
            $table->foreign('law_to')->references('id')->on('laws');
            $table->unsignedBigInteger('type');
            $table->foreign('type')->references('id')->on('refer_types');
            $table->unsignedBigInteger('adder');
            $table->foreign('adder')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refers');
    }
};
