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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('for_eq_in_case')->default(0)->comment('for check device is for equipment in case');
            $table->timestamps();
        });
        $query="insert into devices (name,for_eq_in_case) values
                               ('Case',0),
                               ('Motherboard',1),
                               ('Monitor',0),
                               ('CPU',1),
                               ('RAM',1),
                               ('Graphic Card',1),
                               ('HDD/SSD/M.2',1),
                               ('Copy Machine',0),
                               ('Scanner',0),
                               ('LAN/WLAN Card',1),
                               ('Network Card',1),
                               ('ODD',1),
                               ('Power',1),
                               ('VOIP',0),
                               ('Printer',0),
                               ('Headphone',0),
                               ('Laptop',0),
                               ('Mobile',0),
                               ('Modem',0),
                               ('Recorder',0),
                               ('Router',0),
                               ('Speaker',0),
                               ('Switch',0),
                               ('Tablet',0),
                               ('Video Projector',0),
                               ('Video Projector Curtain',0),
                               ('Webcam',0)
                               ";
        DB::statement($query);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
