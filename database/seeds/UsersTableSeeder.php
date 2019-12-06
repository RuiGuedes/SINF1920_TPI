<?php

use App\User;
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
        User::insertUser([
            'username' => "admin",
            'email' => "admin@tpi.com",
            'email_verified_at' => now(),
            'manager' => true,
            'password' => bcrypt('admin'),
            'remember_token' => Str::random(10)
        ]);

        // Create workers
        User::insertUser([
            'username' => "clerk",
            'email' => "clerk@tpi.com",
            'email_verified_at' => now(),
            'manager' => false,
            'password' => bcrypt('clerk'),
            'remember_token' => Str::random(10)
        ]);
        factory(App\User::class, 9)->create();

        $this->command->info('Database users table seeded!');
    }
}
