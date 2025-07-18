<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Helper\QrCodeHelper;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create storage directory if it doesn't exist
        if (!Storage::disk('public')->exists('qr_codes')) {
            Storage::disk('public')->makeDirectory('qr_codes');
        }

        // Generate QR codes using QrCodeHelper
        $adminQrUrl = QrCodeHelper::generateAndSave('admin123', 'admin', 'png');
        $superadminQrUrl = QrCodeHelper::generateAndSave('superadmin123', 'superadmin', 'png');

        $admin = [
            [
                'name' => 'Admin tamvan',
                'email' => 'admin@gmail.com',
                'phone' => '0123456789',
                'password' => bcrypt('admin123'),
                'qr_code' => 'admin123',
                'qr_image_url' => $adminQrUrl,
                'role' => 'admin',
                'points' => 0,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Superadmin tamvan',
                'email' => 'superadmin@gmail.com',
                'phone' => '0123456789',
                'password' => bcrypt('superadmin123'),
                'qr_code' => 'superadmin123',
                'qr_image_url' => $superadminQrUrl,
                'role' => 'superadmin',
                'points' => 0,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($admin);
    }
}