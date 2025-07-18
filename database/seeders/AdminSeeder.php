<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate QR codes and save as files
        $adminQrCode = QrCode::format('png')->size(200)->generate('admin123');
        $superadminQrCode = QrCode::format('png')->size(200)->generate('superadmin123');

        // Save QR codes to storage
        Storage::disk('public')->put('qr_codes/admin.png', $adminQrCode);
        Storage::disk('public')->put('qr_codes/superadmin.png', $superadminQrCode);

        $admin = [
            [
                'name' => 'Admin tamvan',
                'email' => 'admin@gmail.com',
                'phone' => '0123456789',
                'password' => bcrypt(value: 'admin123'),
                'qr_code' => 'admin123', // Store the original data instead of image
                'qr_image_url' => 'storage/qr_codes/admin.png',
                'role' => 'admin',
                'points' => 0,
            ],
            [
                'name' => 'Superadmin tamvan',
                'email' => 'superadmin@gmail.com',
                'phone' => '0123456789',
                'password' => bcrypt('superadmin123'),
                'qr_code' => 'superadmin123', // Store the original data instead of image
                'qr_image_url' => 'storage/qr_codes/superadmin.png',
                'role' => 'superadmin',
                'points' => 0,
            ],
        ];

        DB::table('users')->insert($admin);
    }
}