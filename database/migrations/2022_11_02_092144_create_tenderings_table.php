<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tenderings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->dateTime('start_date')->required();
            $table->dateTime('end_date')->required();

            $table->float('subtotal', 10, 2)->required();
            $table->float('iva', 10, 2)->required();
            $table->float('total', 10, 2)->required();

            $table->float('total_weight', 10, 2);
            $table->enum('type_of_purchase', [1, 2])->default(2);

            $table->boolean('is_active')->default(true);

            $table->boolean('is_finished')->default(false);
            $table->datetime('finished_at')->nullable();
            $table->unsignedBigInteger('finished_by')->nullable();

            $table->boolean('is_cancelled')->default(false);
            $table->integer('cancelled_by')->nullable();
            $table->date('cancelled_at')->nullable();
            $table->text('cancel_reason')->nullable();

            $table->text('observations')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tenderings');
    }
};
