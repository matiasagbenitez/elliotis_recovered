<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            // Date format is d/m/Y
            $table->timestamp('date');

            $table->unsignedBigInteger('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('suppliers');

            $table->integer('supplier_order_id')->nullable();

            $table->float('subtotal', 10, 2)->required();
            $table->float('iva', 10, 2)->required();
            $table->float('total', 10, 2)->required();

            $table->enum('type_of_purchase', [1, 2])->default(1);

            $table->float('total_weight', 10, 2);

            $table->unsignedBigInteger('payment_condition_id')->required();
            $table->foreign('payment_condition_id')->references('id')->on('payment_conditions');

            $table->unsignedBigInteger('payment_method_id')->required();
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');

            $table->unsignedBigInteger('voucher_type_id')->required();
            $table->foreign('voucher_type_id')->references('id')->on('voucher_types');
            $table->integer('voucher_number')->required()->unique();

            $table->string('weight_voucher')->nullable();

            $table->text('observations')->nullable();

            $table->boolean('is_confirmed')->default(false);
            $table->integer('confirmed_by')->nullable();
            $table->date('confirmed_at')->nullable();

            $table->boolean('is_active')->default(true);
            $table->integer('cancelled_by')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->text('cancel_reason')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchases');
    }
};
