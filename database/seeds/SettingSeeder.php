<?php

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Models\Setting::insert([
            ['key_name' => 'ip_allowed', 'value' => '0.0.0.0'],
            ['key_name' => 'maintain', 'value' => '0'],
        ]);
    }
}
