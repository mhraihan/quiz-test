<?php

namespace Database\Seeders;

use App\Enums\UserEnum;
//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::firstOrCreate([
            'name' => UserEnum::SUPER_ADMIN->value,
        ]);
        Role::firstOrCreate([
            'name' => UserEnum::ADMIN->value,
        ]);
        Role::firstOrCreate([
            'name' => UserEnum::TEACHER->value,
        ]);
        Role::firstOrCreate([
            'name' => UserEnum::STUDENT->value,
        ]);
    }
}
