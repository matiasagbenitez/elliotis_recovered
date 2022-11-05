<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();

            $table->string('business_name')->required()->unique();
            $table->string('slug')->required()->unique();

            $table->integer('total_purchases')->default(0);

            $table->unsignedBigInteger('iva_condition_id')->nullable();
            $table->foreign('iva_condition_id')->references('id')->on('iva_conditions');

            $table->string('cuit')->required();
            $table->string('last_name')->required();
            $table->string('first_name')->required();

            $table->string('adress')->required();

            $table->unsignedBigInteger('locality_id')->required();
            $table->foreign('locality_id')->references('id')->on('localities');

            $table->string('phone')->required();
            $table->string('email')->required();

            $table->boolean('active')->default(true);

            // Observations
            $table->text('observations')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
};
