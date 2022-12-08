<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Result;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
        ]);

        $superAdmin = User::factory()->create([
            'name' => 'MH Raihan',
            'email' => 'me@mhraihan.com',
            'password' => bcrypt('raihan'),
            'email_verified_at' => now()
        ]);
        $superAdmin->assignRole('super-admin');
        $superAdmin->save();

        $admin = User::factory()->create([
            'name'              => 'MH Doe',
            'email'             => 'admin@admin.com',
            'password'          => Hash::make('admin'),
            'email_verified_at' => now()
        ]);
        $admin->assignRole('admin');

        $teacher = User::factory()->create([
            'name'              => 'Yuu Doe',
            'email'             => 'teacher@teacher.com',
            'password'          => Hash::make('teacher'),
            'email_verified_at' => now(),
        ]);
        $teacher->assignRole('teacher');

        $student = User::factory()->create([
            'name'              => 'Jane Doe',
            'email'             => 'student@student.com',
            'password'          => Hash::make('student'),
            'email_verified_at' => now(),
        ]);
        $student->assignRole('student');


        $this->call(QuestionSeeder::class);

        Result::factory()->count(1000)->create();
    }
}
