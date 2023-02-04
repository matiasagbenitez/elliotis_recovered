<?php

namespace Database\Seeders;

use App\Models\TypeOfMovement;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
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

        $this->call(AreaSeeder::class);

        $this->call(WoodTypeSeeder::class);
        $this->call(PhaseSeeder::class);
        $this->call(ProductSeeder::class);

        for ($i = 0; $i < 2; $i++) {
            $this->call(Purchase1Seeder::class);
            $this->call(Purchase2Seeder::class);
            $this->call(Purchase3Seeder::class);
            $this->call(Purchase4Seeder::class);
        }

        $this->call(TrunkLotSeeder::class);

        $this->call(SaleSeeder::class);

        $this->call(PreviousProductSeeder::class);

        $this->call(SaleOrderSeeder::class);

        // $this->call(PurchaseOrder1Seeder::class);
        // $this->call(PurchaseOrder2Seeder::class);
        // $this->call(PurchaseOrder3Seeder::class);
        // $this->call(PurchaseOrder4Seeder::class);

        // $this->call(TenderingSeeder::class);

        // Producción
        $this->call(TaskStatusSeeder::class);
        $this->call(TypeOfTaskSeeder::class);
        $this->call(FollowingProductSeeder::class);

        // Tasks Seeders
        for ($i = 0; $i < 2; $i++) {
            $this->call(Task1Seeder::class);
            $this->call(Task1Seeder::class);
            $this->call(Task1Seeder::class);
            $this->call(Task2Seeder::class);
            // $this->call(Task3Seeder::class);
            // $this->call(Task4Seeder::class);
            // $this->call(Task5Seeder::class);
            // $this->call(Task6Seeder::class);
            // $this->call(Task7Seeder::class);
            // $this->call(Task8Seeder::class);
        }

        // for ($i = 0; $i < 5; $i++) {
        //     $this->call(Task9Seeder::class);
        //     $this->call(Task10Seeder::class);
        // }

        // $this->call(NecessaryProductionSeeder::class);
    }
}
