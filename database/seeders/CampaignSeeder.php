<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campaign = [
            [
                'title' => 'Clean the Ocean',
                'description' => 'A campaign to clean up plastic waste from the ocean.',
                'target_amount' => 50000,
                'current_amount' => 0,
                'photo_url' => 'https://example.com/campaigns/ocean_clean.jpg',
                'location_id' => 2,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        
        ];

        $campaign = DB::table('crowdfunding_campaigns')->insert($campaign);
    }
}