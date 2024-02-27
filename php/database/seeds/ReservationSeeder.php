<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservationSeeder extends Seeder
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
        $schedule = DB::table('schedules')
            ->where('name', 'テストスケジュール')
            ->first();
        DB::table('reservations')->insert([
            [
                'student_id' => $student ? $student->id : '1',
                'schedule_id' => $schedule ? $schedule->id : '1',
                'start_time' => '2020-01-01 00:00:00',
                'end_time' => '2020-01-01 00:00:00',
                'reason' => '早退遅刻理由',
                'teacher_evaluation' => '4',
                'created_at' => '2020-01-01 00:00:00',
                'updated_at' => '2020-01-01 00:00:00'
            ]
        ]);
    }
}
