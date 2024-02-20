<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('approvers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
        $query="insert into approvers (name) values ('شورای عالی حوزه'),('کمیسیون منابع انسانی'),('کمیسیون ساختار و تشکیلات'),('کمیته جذب و انتصابات'),('کمیته بودجه'),('مصوبات دولت'),('قانون مدیریت خدمات کشوری')";
        DB::statement($query);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvers');
    }
};
