<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'branch_office_id' => 1,
            'name' => 'John Doe',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $courier = User::create([
            'branch_office_id' => 1,
            'name' => 'Asep',
            'email' => 'courier@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $admin = User::create([
            'branch_office_id' => 1,
            'name' => 'John Doe',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $superadmin = User::create([
            'branch_office_id' => 1,
            'name' => 'John Doe',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('password'),
        ]);

        Role::create(['name' => 'user']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'superadmin']);
        Role::create(['name' => 'courier']);

        $user->assignRole('user');
        $admin->assignRole('admin');
        $superadmin->assignRole('superadmin');
        $courier->assignRole('courier');
    }
}
