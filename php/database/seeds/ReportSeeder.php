<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reservation = DB::table('reservations')
            ->where('student_id', '1')
            ->first();
        $student = DB::table('students')
            ->where('name', 'テスト生徒')
            ->first();
        DB::table('reports')->insert([
            [
                'reservation_id' => $reservation ? $reservation->id : '1',
                'student_id' => $student ? $student->id : '1',
                'personal_evaluation' => '3',
                'worked' => 'HTML基礎',
                'note' => '自由入力自由入力自由入力自由入力自由入力自由入力',
                'created_at' => '2020-01-01 00:00:00',
                'updated_at' => '2020-01-01 00:00:00'
            ]
        ]);
    }
}
