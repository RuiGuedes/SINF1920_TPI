<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create manager(s)
        DB::table('users')->insert([
            'username' => "admin",
            'email' => "admin@tpi.com",
            'email_verified_at' => now(),
            'manager' => true,
            'password' => bcrypt('admin'),
            'remember_token' => Str::random(10)
        ]);

        // Create workers
        factory(App\Users::class, 10)->create();
    }
}
