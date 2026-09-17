<?php

namespace Database\Seeders;

use App\Models\AssetStatus;
use Illuminate\Database\Seeder;

class AssetStatusTableSeeder extends Seeder
{
    public function run()
    {
        $assetStatuses = [
            [
                'id'         => 1,
                'name'       => 'Available',
                'created_at' => '2025-01-25 11:32:55',
                'updated_at' => '2025-01-25 11:32:55',
            ],
            [
                'id'         => 2,
                'name'       => 'Not Available',
                'created_at' => '2025-01-25 11:32:55',
                'updated_at' => '2025-01-25 11:32:55',
            ],
            [
                'id'         => 3,
                'name'       => 'Broken',
                'created_at' => '2025-01-25 11:32:55',
                'updated_at' => '2025-01-25 11:32:55',
            ],
            [
                'id'         => 4,
                'name'       => 'Out for Repair',
                'created_at' => '2025-01-25 11:32:55',
                'updated_at' => '2025-01-25 11:32:55',
            ],
        ];

        foreach ($assetStatuses as $assetStatus) {
            AssetStatus::firstOrCreate(['name' => $assetStatus['name']], $assetStatus);
        }
    }
}
