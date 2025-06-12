<?php

namespace Database\Seeders;

use App\Models\Batch;
use Illuminate\Database\Seeder;

class BatchSeeder extends Seeder
{
    public function run()
    {
        $batches = [
            ['name' => 'A', 'description' => 'Batch A'],
            ['name' => 'B', 'description' => 'Batch B'],
            ['name' => 'C', 'description' => 'Batch C'],
        ];

        foreach ($batches as $batch) {
            Batch::create($batch);
        }
    }
} 