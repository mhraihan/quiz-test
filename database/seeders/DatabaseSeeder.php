<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Result;
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


        $this->call(UserTableSeeder::class);
        $this->call(QuestionSeeder::class);

        Result::factory()->count(600)->create();
    }
}
