<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('best_offers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tendering_id');
            $table->foreign('tendering_id')->references('id')->on('tenderings');

            $table->unsignedBigInteger('offer_id');
            $table->foreign('offer_id')->references('id')->on('offers');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('best_offers');
    }
};
