<?php

namespace App\Providers;

use App\Models\Admin\Repository\AdminRepository;
use App\Models\Admin\Repository\AdminRepositoryInterface;
use App\Models\Company\Repository\CompanyRepository;
use App\Models\Company\Repository\CompanyRepositoryInterface;
use App\Models\Course\Repository\CourseRepository;
use App\Models\Course\Repository\CourseRepositoryInterface;
use App\Models\CourseCurriculum\Repository\CourseCurriculumRepository;
use App\Models\CourseCurriculum\Repository\CourseCurriculumRepositoryInterface;
use App\Models\Curriculum\Repository\CurriculumRepository;
use App\Models\Curriculum\Repository\CurriculumRepositoryInterface;
use App\Models\Progress\Repository\ProgressRepository;
use App\Models\Progress\Repository\ProgressRepositoryInterface;
use App\Models\Question\Repository\QuestionRepository;
use App\Models\Question\Repository\QuestionRepositoryInterface;
use App\Models\Reservation\Repository\ReservationRepository;
use App\Models\Reservation\Repository\ReservationRepositoryInterface;
use App\Models\Step\Repository\StepRepository;
use App\Models\Step\Repository\StepRepositoryInterface;
use App\Models\Student\Repository\StudentRepository;
use App\Models\Student\Repository\StudentRepositoryInterface;
use App\Models\Test\Repository\TestRepository;
use App\Models\Test\Repository\TestRepositoryInterface;
use App\Models\User\Repository\UserRepository;
use App\Models\User\Repository\UserRepositoryInterface;
use App\Models\Schedule\Repository\ScheduleRepository;
use App\Models\Schedule\Repository\ScheduleRepositoryInterface;
use App\Models\TeacherSchedule\Repository\TeacherScheduleRepository;
use App\Models\TeacherSchedule\Repository\TeacherScheduleRepositoryInterface;
use App\Models\Report\Repository\ReportRepository;
use App\Models\Report\Repository\ReportRepositoryInterface;
use App\Models\Score\Repository\ScoreRepository;
use App\Models\Score\Repository\ScoreRepositoryInterface;
use App\Models\InterviewHistory\Repository\InterviewHistoryRepository;
use App\Models\InterviewHistory\Repository\InterviewHistoryRepositoryInterface;
use App\Models\SlackChannel\Repository\SlackChannelRepository;
use App\Models\SlackChannel\Repository\SlackChannelRepositoryInterface;
use App\Models\MissingEvaluationItem\Repository\MissingEvaluationItemRepository;
use App\Models\MissingEvaluationItem\Repository\MissingEvaluationItemRepositoryInterface;
use App\Models\CourseGroup\Repository\CourseGroupRepository;
use App\Models\CourseGroup\Repository\CourseGroupRepositoryInterface;
use App\Providers\Auth\UserAuthProvider;
use App\Providers\Auth\AdminAuthProvider;
use App\Providers\Auth\StudentAuthProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(CompanyRepositoryInterface::class, CompanyRepository::class);
        $this->app->bind(TestRepositoryInterface::class, TestRepository::class);
        $this->app->bind(QuestionRepositoryInterface::class, QuestionRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(CurriculumRepositoryInterface::class, CurriculumRepository::class);
        $this->app->bind(ProgressRepositoryInterface::class, ProgressRepository::class);
        $this->app->bind(ScheduleRepositoryInterface::class, ScheduleRepository::class);
        $this->app->bind(TeacherScheduleRepositoryInterface::class, TeacherScheduleRepository::class);
        $this->app->bind(ReportRepositoryInterface::class, ReportRepository::class);
        $this->app->bind(ScoreRepositoryInterface::class, ScoreRepository::class);
        $this->app->bind(ReservationRepositoryInterface::class, ReservationRepository::class);
        $this->app->bind(StepRepositoryInterface::class, StepRepository::class);
        $this->app->bind(InterviewHistoryRepositoryInterface::class, InterviewHistoryRepository::class);
        $this->app->bind(CourseCurriculumRepositoryInterface::class, CourseCurriculumRepository::class);
        $this->app->bind(StepRepositoryInterface::class, StepRepository::class);
        $this->app->bind(SlackChannelRepositoryInterface::class, SlackChannelRepository::class);
        $this->app->bind(MissingEvaluationItemRepositoryInterface::class, MissingEvaluationItemRepository::class);
        $this->app->bind(CourseGroupRepositoryInterface::class, CourseGroupRepository::class);

        // 認証プロバイダ
        \Auth::provider('user_auth', function ($app, array $config) {
            return new UserAuthProvider($app['hash'], $config['model']);
        });
        \Auth::provider('admin_auth', function ($app, array $config) {
            return new AdminAuthProvider($app['hash'], $config['model']);
        });
        \Auth::provider('student_auth', function ($app, array $config) {
            return new StudentAuthProvider($app['hash'], $config['model']);
        });

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
