<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trunk_purchases', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('purchase_id');
            $table->foreign('purchase_id')->references('id')->on('purchases');

            $table->string('code')->unique();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trunk_purchases');
    }
};
