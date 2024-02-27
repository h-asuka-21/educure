<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = DB::table('companies')
            ->where('company_code', 'test')
            ->first();
        DB::table('students')->insert([
            [
                'name' => 'テスト生徒',
                'name_kana' => 'テスト',
                'company_id' => $company ? $company->id : '1',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
                'created_at' => '2020-01-01 00:00:00',
                'updated_at' => '2020-01-01 00:00:00'
            ]
        ]);
    }
}
