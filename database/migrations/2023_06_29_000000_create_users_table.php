<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('family');
            $table->string('username')->unique();
            $table->string('password');
            $table->tinyInteger('type')->comment('
            1 => SuperAdmin
            ');
            $table->string('subject');
            $table->tinyInteger('active')->default(1);
            $table->tinyInteger('ntcp')->default(0)->comment('Needs To Change Password');
            $table->text('user_image')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        $password = bcrypt(12345678);
        $query = "INSERT INTO users (name,family, username, password, type, subject, active, ntcp) VALUES
                  ('محمد', 'عاشوری', 'ashouri','$password',1,'ادمین کل',1,0) ,
                  ('اسماعیل', 'براتی', 'barati','$password',1,'ادمین کل',1,0)
                ";

        DB::statement($query);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
