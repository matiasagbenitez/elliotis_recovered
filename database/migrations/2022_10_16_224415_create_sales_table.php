<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            // Date format is d/m/Y
            $table->timestamp('date');

            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients');

            $table->integer('client_order_id')->nullable();

            $table->unsignedBigInteger('payment_condition_id')->required();
            $table->foreign('payment_condition_id')->references('id')->on('payment_conditions');

            $table->unsignedBigInteger('payment_method_id')->required();
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');

            $table->unsignedBigInteger('voucher_type_id')->required();
            $table->foreign('voucher_type_id')->references('id')->on('voucher_types');

            $table->integer('voucher_number')->required()->unique();

            // bigFloat for subtotal, iva and total
            $table->float('subtotal', 10, 2)->required();
            $table->float('iva', 10, 2)->required();
            $table->float('total', 10, 2)->required();
            $table->text('observations')->nullable();

            $table->boolean('is_active')->default(true);
            $table->integer('cancelled_by')->nullable();
            $table->date('cancelled_at')->nullable();
            $table->text('cancel_reason')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
};
