<?php

use Illuminate\Database\Seeder;

class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('admins')->insert([
            'name' => 'テスト',
            'name_kana' => 'テスト',
            'password' => bcrypt('password'),
            'email' => 'test@example.com',
            'created_at' => '2020-07-01 00:00:00',
            'updated_at' => '2020-07-01 00:00:00',
        ]);
    }
}
