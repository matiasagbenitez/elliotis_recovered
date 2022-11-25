<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TrunkLot;
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
                'consumed_quantity' => rand(20, $trunkLot->actual_quantity),
            ]);

        });

        // Update actual quantity of the trunkLots
        $task->trunkLots->each(function ($trunkLot) {

            $trunkLot->actual_quantity = $trunkLot->actual_quantity - $trunkLot->pivot->consumed_quantity;
            $trunkLot->available = $trunkLot->actual_quantity ? true : false;
            $trunkLot->save();

        });
    }
}
