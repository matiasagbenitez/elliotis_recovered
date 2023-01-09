<?php

namespace Database\Seeders;

use App\Models\TypeOfMovement;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(IvaConditionSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(PaymentConditionsSeeder::class);
        $this->call(PaymentMethodsSeeder::class);
        $this->call(VoucherTypesSeeder::class);
        $this->call(IvaTypesSeeder::class);
        $this->call(InchSeeder::class);
        $this->call(FeetSeeder::class);
        $this->call(MeasureSeeder::class);
        $this->call(MachimbreMeasureSeeder::class);
        $this->call(TrunkMeasureSeeder::class);
        $this->call(UnitySeeder::class);
        $this->call(ProductNameSeeder::class);
        $this->call(ProductTypeSeeder::class);

        // $this->call(PhaseSeeder::class);
        $this->call(AreaSeeder::class);

        $this->call(WoodTypeSeeder::class);
        $this->call(PhaseSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(PurchaseSeeder::class);
        $this->call(SaleSeeder::class);

        $this->call(PreviousProductSeeder::class);

        $this->call(SaleOrderSeeder::class);
        $this->call(PurchaseOrderSeeder::class);
        $this->call(TenderingSeeder::class);

        // Producción
        $this->call(TaskStatusSeeder::class);
        $this->call(TypeOfTaskSeeder::class);
        $this->call(FollowingProductSeeder::class);

        // Tasks Seeders
        for ($i = 0; $i < 8; $i++) {
            $this->call(Task1Seeder::class);
        }

        for ($i = 0; $i < 7; $i++) {
            $this->call(Task2Seeder::class);
        }

        for ($i = 0; $i < 6; $i++) {
            $this->call(Task3Seeder::class);
        }

        for ($i = 0; $i < 6; $i++) {
            $this->call(Task4Seeder::class);
        }

        for ($i = 0; $i < 6; $i++) {
            $this->call(Task5Seeder::class);
        }

        for ($i = 0; $i < 6; $i++) {
            $this->call(Task6Seeder::class);
        }

        for ($i = 0; $i < 6; $i++) {
            $this->call(Task7Seeder::class);
        }

        for ($i = 0; $i < 6; $i++) {
            $this->call(Task8Seeder::class);
        }
    }
}
