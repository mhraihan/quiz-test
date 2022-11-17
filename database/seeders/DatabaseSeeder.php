<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Result;
use App\Models\User;
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
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'name' => 'MH Raihan',
            'email' => 'me@mhraihan.com',
            'password' => bcrypt('raihan')
        ]);

        $this->call(QuestionSeeder::class);

        Result::factory()->count(100)->create([
            'user_id' => 1,
            'complete' => (bool)random_int(0, 1),
        ]);
    }
}
