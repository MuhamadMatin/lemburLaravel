<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Membuat peran
        $admin = Role::create(['name' => 'admin']);
        $manager = Role::create(['name' => 'manager']);
        $pegawai = Role::create(['name' => 'pegawai']);

        // Menghasilkan izin Shield
        Artisan::call('shield:generate', ['--all' => true]);

        // Memberikan semua izin kepada admin
        $admin->givePermissionTo(Permission::all());

        // Memberikan semua izin overwork kepada manager
        $manager->givePermissionTo(
            Permission::where('name', 'like', '%overtime%')->get()
        );

        // Memberikan izin view dan update overwork kepada pegawai
        $pegawai->givePermissionTo([
            'view_any_overtime',
            'view_overtime',
            'update_overtime'
        ]);
    }
}
