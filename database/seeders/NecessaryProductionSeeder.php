<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Http\Services\NecessaryProductionService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NecessaryProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NecessaryProductionService::calculate(null, true);
    }
}
