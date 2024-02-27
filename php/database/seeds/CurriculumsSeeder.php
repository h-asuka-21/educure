<?php

use Illuminate\Database\Seeder;

class CurriculumsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('curriculums')->insert([
            'name' => 'カリキュラム1',
            'test_id' => '1',
            'created_at' => '2020-07-01 00:00:00',
            'updated_at' => '2020-07-01 00:00:00',
        ]);
    }
}
