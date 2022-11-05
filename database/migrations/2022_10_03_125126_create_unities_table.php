<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('unities', function (Blueprint $table) {
            $table->id();

            $table->string('name')->required()->unique();
            $table->integer('unities')->required()->unique();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('unities');
    }
};
