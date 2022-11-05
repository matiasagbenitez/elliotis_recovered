<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hashes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tendering_id');
            $table->foreign('tendering_id')->references('id')->on('tenderings');

            $table->unsignedBigInteger('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('suppliers');

            $table->string('hash')->unique();
            $table->boolean('is_active')->default(true);

            $table->datetime('sent_at')->nullable();

            $table->boolean('seen')->default(false);
            $table->datetime('seen_at')->nullable();

            $table->boolean('answered')->default(false);
            $table->datetime('answered_at')->nullable();

            $table->boolean('confirmation_sent')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hashes');
    }
};
