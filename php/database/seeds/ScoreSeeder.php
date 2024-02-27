<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $student = DB::table('students')
            ->where('name', 'テスト生徒')
            ->first();
        $test = DB::table('tests')
            ->where('name', 'テスト名1')
            ->first();
        DB::table('scores')->insert([
            [
                'student_id' => $student ? $student->id : '1',
                'test_id' => $test ? $test->id : '1',
                'score' => '60',
                'choices' => '2,2,1',
                'created_at' => '2020-01-01 00:00:00',
                'updated_at' => '2020-01-01 00:00:00'
            ]
        ]);
    }
}
