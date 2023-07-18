<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Result;
use App\Models\School;
use App\Models\Topic;
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
        if (app()->environment() === "local") {
            /**
             * Seed the categories.
             */
            Topic::create(['title' => 'Science']);
            Topic::create(['title' => 'Mathematics']);
            Topic::create(['title' => 'Physics']);

            School::factory()->count(10)->create();
            $this->call(UserTableSeeder::class);
            $this->call(QuestionSeeder::class);

            Result::factory()->count(600)->create();

        }
    }
}
