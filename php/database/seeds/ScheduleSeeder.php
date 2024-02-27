<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Carbon;

class ScheduleSeeder extends Seeder
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
        DB::table('schedules')->insert([
            [
                'company_id' => $company ? $company->id : '1',
                'name' => 'テストスケジュール',
                'date' => Carbon::now()->addDay()->format('Y-m-d'),
                'start_time' => '10:00:00',
                'end_time' => '20:00:00',
                'available_limit' => '20',
                'created_at' => '2020-01-01 00:00:00',
                'updated_at' => '2020-01-01 00:00:00'
            ]
        ]);
        $latest = DB::table('schedules')->latest('id')->first();
        $latest_user = DB::table('users')->latest()->first();
        DB::table('teacher_schedules')->insert([
            'schedule_id' => $latest->id,
            'user_id' => $latest_user->id,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s',),
        ]);
    }
}
