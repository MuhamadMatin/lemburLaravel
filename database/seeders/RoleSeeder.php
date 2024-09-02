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

        // Buat role
        $admin = Role::create(['name' => 'admin']);
        $manager = Role::create(['name' => 'manager']);
        $pegawai = Role::create(['name' => 'pegawai']);

        // generate izin filament-shield
        Artisan::call('shield:generate', ['--all' => true]);

        // berikan akses ke role admin
        $admin->givePermissionTo(Permission::all());

        // berikan akses ke role manager
        $manager->givePermissionTo(
            Permission::where('name', 'like', '%overtime%')->get()
        );

        // berikan akses ke role pegawai
        $pegawai->givePermissionTo([
            'view_any_overtime',
            'view_overtime',
            'update_overtime'
        ]);
    }
}
