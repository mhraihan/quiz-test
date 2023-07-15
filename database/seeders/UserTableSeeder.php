<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\UserEnum;
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
        $superAdmin->syncRoles(UserEnum::SUPER_ADMIN->value);
        $superAdmin->removeRole(UserEnum::STUDENT->value);

        $admin = User::factory()->create([
            'first_name' => 'MH',
            'last_name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'email_verified_at' => now()
        ]);
        $admin->syncRoles(UserEnum::ADMIN->value);
        $superAdmin->removeRole(UserEnum::STUDENT->value);


        $teacher = User::factory()->create([
            'first_name' => 'Teacher',
            'last_name' => 'Raihan',
            'email' => 'teacher@teacher.com',
            'password' => Hash::make('teacher'),
            'email_verified_at' => now(),
        ]);
        $teacher->syncRoles(UserEnum::TEACHER->value);
        $superAdmin->removeRole(UserEnum::STUDENT->value);

        $student = User::factory()->create([
            'first_name' => 'Student',
            'last_name' => 'Raihan',
            'email' => 'student@student.com',
            'password' => Hash::make('student'),
            'email_verified_at' => now(),
        ]);
        $student->syncRoles(UserEnum::STUDENT->value);

        User::factory(50)->create();
        // assign all the users with a teacher
        $teacher_id = 3;
        User::query()->limit(50)->get()->each(function ($user) use ($teacher_id) {
            if ($user) {
                $user?->teachers()->sync($teacher_id);
            }
        });
    }
}
