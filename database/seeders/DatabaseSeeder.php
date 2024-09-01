<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Overtime;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(RoleSeeder::class);

        $users = [
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('admin'),
                'role' => 'admin',
            ],
            [
                'name' => 'manager',
                'email' => 'manager@gmail.com',
                'password' => bcrypt('manager'),
                'role' => 'pegawai',
            ],
            [
                'name' => 'pegawai',
                'email' => 'pegawai@gmail.com',
                'password' => bcrypt('pegawai'),
                'role' => 'pegawai',
            ],
            [
                'name' => 'pegawai2',
                'email' => 'pegawai2@gmail.com',
                'password' => bcrypt('pegawai2'),
                'role' => 'pegawai',
            ],
            [
                'name' => 'pegawai3',
                'email' => 'pegawai3@gmail.com',
                'password' => bcrypt('pegawai3'),
                'role' => 'pegawai',
            ],
            [
                'name' => 'pegawai4',
                'email' => 'pegawai4@gmail.com',
                'password' => bcrypt('pegawai4'),
                'role' => 'pegawai',
            ],
            [
                'name' => 'pegawai5',
                'email' => 'pegawai5@gmail.com',
                'password' => bcrypt('pegawai5'),
                'role' => 'pegawai',
            ],
        ];

        foreach ($users as $user) {
            User::factory()->create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => $user['password'],
            ])->assignRole($user['role']);
        }
    }
}
