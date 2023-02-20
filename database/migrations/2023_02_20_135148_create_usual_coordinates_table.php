<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('usual_coordinates', function (Blueprint $table) {
            $table->id();

            $table->string('city');
            $table->string('lat');
            $table->string('lon');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('usual_coordinates');
    }
};
