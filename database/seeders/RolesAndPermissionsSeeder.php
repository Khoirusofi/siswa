<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $teacherRole = Role::create(['name' => 'teacher']);
        $studentRole = Role::create(['name' => 'student']);

        $userAdmin = User::create([
            'name' => 'Sof',
            'email' => 'admin@sof.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'birth_date' => '1990-01-01',
            'gender' => 'male',
            'address' => 'Jl. Mawar No. 10, Jakarta',
            'phone_number' => '081234567890',
            'remember_token' => Str::random(10),
        ]);
        $userAdmin->assignRole($adminRole);

        $userTeacher = User::create([
            'name' => 'Sof',
            'email' => 'teacher@sof.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'birth_date' => '1990-01-01',
            'gender' => 'male',
            'address' => 'Jl. Mawar No. 10, Jakarta',
            'phone_number' => '081234567890',
            'remember_token' => Str::random(10),
        ]);
        $userTeacher->assignRole($teacherRole);

        $userStudent = User::create([
            'name' => 'Sof',
            'email' => 'student@sof.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'birth_date' => '1990-01-01',
            'gender' => 'male',
            'address' => 'Jl. Mawar No. 10, Jakarta',
            'phone_number' => '081234567890',
            'remember_token' => Str::random(10),
        ]);
        $userStudent->assignRole($studentRole);
    }
}
