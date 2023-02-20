<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cuit')->unique();
            $table->string('slogan')->nullable();
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('address');

            $table->string('lat')->nullable();
            $table->string('lon')->nullable();

            $table->string('cp');
            $table->string('logo')->nullable()->default('/img/default.png');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }
};
