<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
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
            CurriculumsSeeder::class,
            StepSeeder::class
        ]);
    }
}
