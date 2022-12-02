<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Task;
use App\Models\TrunkLot;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $trunkLots = TrunkLot::all();

        $task = Task::create([
            'task_type_id' => 1,
            'task_status_id' => 3,
            'started_by' => 1,
            'started_at' => now()->subHour()->subMinutes(35),
            'finished_by' => 1,
            'finished_at' => now(),
        ]);

        // Attach random trunkLots to the task
        $trunkLots->random(3)->each(function ($trunkLot) use ($task) {

            $task->trunkLots()->attach($trunkLot->id, [
                'consumed_quantity' => rand(10, 20)
            ]);

        });

        // Update actual quantity of the trunkLots
        $task->trunkLots->each(function ($trunkLot) {

            $trunkLot->actual_quantity = $trunkLot->actual_quantity - $trunkLot->pivot->consumed_quantity;
            $trunkLot->available = $trunkLot->actual_quantity ? true : false;
            $trunkLot->save();

        });

        // Create a lot
        $task->lot()->create([
            'code' => 'LFH-' . rand(1000, 9999),
        ]);

        // Output products are those with phase_id = 2
        $outputProducts = Product::where('phase_id', 2)->get();

        // Attach random products to the lot
        foreach ($outputProducts as $product) {

            $q = rand(80, 170);
            $task->outputProducts()->attach($product->id, [
                'lot_id' => $task->lot->id,
                'produced_quantity' => $q
            ]);

            $task->lot->sublots()->create([
                'code' => 'SFH-' . rand(1000, 9999),
                'product_id' => $product->id,
                'initial_quantity' => $q,
                'actual_quantity' => $q,
            ]);

        }

        // $task->trunkLots->random(2)->each(function ($trunkLot) use ($task) {

        //     $task->lot->products()->attach($trunkLot->product->id, [
        //         'lot_id' => $task->lot->id,
        //         'produced_quantity' => $trunkLot->pivot->consumed_quantity * rand(8, 12),
        //     ]);

        //     $task->lot->sublots()->create([
        //         'code' => Str::random(6),
        //         'product_id' => $trunkLot->product->id,
        //         'initial_quantity' => $trunkLot->pivot->consumed_quantity * rand(8, 12),
        //     ]);

        // });
    }
}
