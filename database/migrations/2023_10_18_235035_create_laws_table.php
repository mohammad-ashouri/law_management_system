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
        Schema::create('laws', function (Blueprint $table) {
            $table->id();
            $table->integer('law_code')->comment('شماره مصوبه');
            $table->integer('session_code')->nullable()->comment('کد جلسه');
            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')->references('id')->on('types');
            $table->unsignedBigInteger('group_id');
            $table->foreign('group_id')->references('id')->on('law_groups');
            $table->unsignedBigInteger('topic_id');
            $table->foreign('topic_id')->references('id')->on('topics');
            $table->string('title');
            $table->text('body');
            $table->string('approval_date')->comment('تاریخ تصویب');
            $table->string('issue_date')->nullable()->comment('تاریخ صدور');
            $table->string('promulgation_date')->nullable()->comment('تاریخ ابلاغ');
            $table->text('keywords')->comment('کلمات کلیدی');
            $table->string('file')->nullable();
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
        Schema::dropIfExists('laws');
    }
};
