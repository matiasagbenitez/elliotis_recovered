<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('weather_apis', function (Blueprint $table) {
            $table->id();

            $table->float('temp', 8, 2);
            $table->float('rain_prob', 8, 2);
            $table->float('rain_mm', 8, 2);
            $table->float('humidity', 8, 2);
            $table->float('wind_speed', 8, 2);
            $table->integer('days_in_row')->min(1)->max(5);
            $table->integer('max_conditions')->min(1)->max(5);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('weather_apis');
    }
};
