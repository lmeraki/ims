<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        User::create([
        	'name' => 'superuser',
        	'email' => 'admin@ims.com',
        	'password'=>  bcrypt('123123'),
        	'user_type' => 'admin'
        ]);
    }
}
