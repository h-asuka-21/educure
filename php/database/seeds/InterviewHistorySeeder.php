<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterviewHistorySeeder extends Seeder
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
        $user = DB::table('users')
            ->where('name', 'テストユーザー')
            ->first();
        DB::table('interview_histories')->insert([
            [
                'student_id' => $student ? $student->id : '1',
                'user_id' => $user ? $user->id : '1',
                'sales_evaluation' => '3',
                'evaluation_reason' => '評価コメント評価コメント評価コメント評価コメント評価コメント',
                'created_at' => '2020-01-01 00:00:00',
                'updated_at' => '2020-01-01 00:00:00'
            ]
        ]);
    }
}
