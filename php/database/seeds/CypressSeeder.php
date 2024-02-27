<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CypressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            CompaniesSeeder::class,
            AdminsSeeder::class,
            UsersSeeder::class,
            StudentSeeder::class,
            CourseSeeder::class,
            ScheduleSeeder::class,
            ReservationSeeder::class,
            ReportSeeder::class,
            TestSeeder::class,
            ScoreSeeder::class,
            InterviewHistorySeeder::class,
            StepSeeder::class
        ]);
    }
}
