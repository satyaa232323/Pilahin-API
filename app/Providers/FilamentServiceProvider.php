<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Middleware untuk mengecek superadmin
        //Filament::serving(function () {
            // Debugging (bisa diaktifkan jika diperlukan)
            // logger()->debug('Filament auth check', ['user' => optional(auth()->user())->only('id', 'name', 'role')]);
            
            //if (!Auth::check() || Auth::user()->role !== 'superadmin') {
                //abort(403, 'Hanya superadmin yang dapat mengakses panel ini');
          //  }
        //});

        // Registrasi navigation groups (dipisah dari serving callback)
        Filament::registerNavigationGroups([
            'Crowdfunding',
            'Drop Points',
            'Voucher',
        ]);

        // Anda bisa menambahkan konfigurasi Filament lainnya di sini
        // Contoh:
        // Filament::registerStyles([...]);
        // Filament::registerScripts([...]);
    }
}