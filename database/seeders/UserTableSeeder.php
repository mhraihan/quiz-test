<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = User::factory()->create([
            'first_name' => 'MH',
            'last_name' => 'Raihan',
            'email' => 'me@mhraihan.com',
            'password' => bcrypt('mhraihan'),
            'email_verified_at' => now()
        ]);
        $superAdmin->assignRole('super-admin');
        $superAdmin->save();

        $admin = User::factory()->create([
            'first_name' => 'MH',
            'last_name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'email_verified_at' => now()
        ]);
        $admin->assignRole('admin');

        $teacher = User::factory()->create([
            'first_name' => 'Teacher',
            'last_name' => 'Raihan',
            'email' => 'teacher@teacher.com',
            'password' => Hash::make('teacher'),
            'email_verified_at' => now(),
        ]);
        $teacher->assignRole('teacher');

        $student = User::factory()->create([
            'first_name' => 'Student',
            'last_name' => 'Raihan',
            'email' => 'student@student.com',
            'password' => Hash::make('student'),
            'email_verified_at' => now(),
        ]);
        $student->assignRole('student');

        User::factory(100)->create();
    }
}
