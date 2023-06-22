<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Result;
use App\Models\School;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //only for productions
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
        ]);

        School::factory()->count(10)->create();
        $this->call(UserTableSeeder::class);
        $this->call(QuestionSeeder::class);

        Result::factory()->count(600)->create();
    }
}
