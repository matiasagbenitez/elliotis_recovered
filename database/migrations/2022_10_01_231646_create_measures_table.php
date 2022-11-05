<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('measures', function (Blueprint $table) {
            $table->id();

            $table->string('name')->required()->unique();

            $table->boolean('favorite')->default(false);

            $table->boolean('is_trunk')->default(false);

            $table->unsignedBigInteger('height')->nullable();
            $table->foreign('height')->references('id')->on('inches');

            $table->unsignedBigInteger('width')->nullable();
            $table->foreign('width')->references('id')->on('inches');

            $table->unsignedBigInteger('length')->required();
            $table->foreign('length')->references('id')->on('feets');

            $table->float('m2')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('measures');
    }
};
