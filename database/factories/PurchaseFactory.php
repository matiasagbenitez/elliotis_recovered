<?php

namespace Database\Factories;

use App\Models\PaymentConditions;
use App\Models\PaymentMethods;
use App\Models\Supplier;
use App\Models\User;
use App\Models\VoucherTypes;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class PurchaseFactory extends Factory
{
    public function definition()
    {
        $user_id = User::inRandomOrder()->first()->id;
        $date = $this->faker->dateTimeBetween('-2 week', 'now');
        $supplier_id = Supplier::inRandomOrder()->first()->id;
        $payment_condition_id = PaymentConditions::inRandomOrder()->first()->id;
        $payment_method_id = PaymentMethods::inRandomOrder()->first()->id;
        $voucher_type_id = VoucherTypes::inRandomOrder()->first()->id;
        $subtotal = $this->faker->numberBetween(40000, 75000);
        $iva = $subtotal * 0.21;
        $total = $subtotal + $iva;

        return [
            'user_id' => $user_id,
            'date' => $date,
            'supplier_id' => $supplier_id,
            // 'supplier_order_id' => $this->faker->unique()->numberBetween(1, 100),
            'payment_condition_id' => $payment_condition_id,
            'payment_method_id' => $payment_method_id,
            'voucher_type_id' => $voucher_type_id,
            'voucher_number' => $this->faker->numberBetween(1000, 9999),
            'subtotal' => $subtotal,
            'iva' => $iva,
            'total' => $total,
            'weight' => $this->faker->randomFloat(2, 30000, 50000),
            'weight_voucher' => null,
            'observations' => $this->faker->text(100),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
