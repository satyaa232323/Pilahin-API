<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DropPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dropPoint = [
            [

                'name' => 'Central Park Drop Point',
                'description' => 'A drop point located in Central Park for collecting recyclable materials.',
                'address' => '123 Central Park West, New York, NY 10023',
                'latitude' => 40.785091,
                'longitude' => -73.968285,
                'photo_url' => 'https://example.com/drop_points/central_park.jpg',
                'admin_id' => 8, // Assuming admin with ID 1 exists
            ]
        ];

        $dropPoint = DB::table('drop_points')->insert($dropPoint);
    }
}