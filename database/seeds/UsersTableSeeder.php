<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Models\User::insert([
            'username' => 'yeshm',
            'email' => 'yeshm@example.com',
            'password' => bcrypt('123456'),
            'type' => 1
        ]);
    }
}
