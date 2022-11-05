<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('iva_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('discriminate');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('iva_conditions');
    }
};
