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
        Schema::create('differences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('law_id');
            $table->foreign('law_id')->references('id')->on('laws');
            $table->string('type');
            $table->longText('old');
            $table->longText('new');
            $table->unsignedBigInteger('editor');
            $table->foreign('editor')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('differences');
    }
};
