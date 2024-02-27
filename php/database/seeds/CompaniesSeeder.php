<?php

use Illuminate\Database\Seeder;

class CompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('companies')->insert([
            'company_code' => 'test',
            'name' => 'テスト企業',
            'created_at' => '2020-07-01 00:00:00',
            'updated_at' => '2020-07-01 00:00:00',
        ]);
    }
}
