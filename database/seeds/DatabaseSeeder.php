<?php

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
        DB::table('users')->truncate();
        DB::table('posts')->truncate();

        factory(App\User::class, 3)->create();
        factory(App\Post::class, 50)->create();
    }
}
