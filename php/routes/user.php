<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api', 'prefix' => 'user', 'namespace' => 'User', 'as' => 'user.'], function () {
    Route::get('/student/delay', 'StudentController@getDelayStudents');
    Route::get('/student/low_evaluation', 'StudentController@getLowEvaluationStudents');
    Route::get('/student/not_attended', 'StudentController@getNotAttendedStudents');
    Route::get('/student/progress_list/{id}', 'ProgressController@getProgressList');
    Route::get('/student/evaluation/{id}', 'StudentController@evaluation');
    Route::get('/student/teacher_evalution/{id}', 'StudentController@teacher_evalution');
    Route::get('/student/evaluation_ranking', 'StudentController@evaluationRanking');
    Route::get('/student/progress/{id}', 'ProgressController@getGraphData');
    Route::resource('student', 'StudentController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::get('/course/autocomplete', 'CourseController@autocomplete');
    Route::get('/course_group/autocomplete', 'CourseController@autocompleteWithCompanyId');
    Route::get('/schedule/calendar', 'ScheduleController@calendar');
    Route::post('/schedule/bulk', 'ScheduleController@bulk');
    Route::get('/schedule/student/{id}', 'ScheduleController@reservedStudents');
    Route::resource('schedule', 'ScheduleController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::get('schedule_user/autocomplete', 'UserController@autocomplete');
    Route::get('teacher_schedule/{id}', 'ScheduleController@getUserByScheduleId');
    Route::resource('student', 'StudentController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::resource('report', 'ReportController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::get('/test/autocomplete', 'TestController@autocomplete');
    Route::resource('test', 'TestController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::get('/test/student/{id}', 'TestController@getStudentsByTestId');
    Route::get('/step/autocomplete', 'StepController@autocomplete');
    Route::get('/course/autocomplete', 'CourseController@autocomplete');
    Route::resource('curriculum', 'CurriculumController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::get('/curriculum/autocomplete', 'CurriculumController@autocomplete');
    Route::get('/curriculum_group/autocomplete', 'CurriculumController@autocompleteWithCompanyId');
    Route::get('/progress/students', 'ProgressController@getUsersAndProgresses');
    Route::get('/interview_history/student/{id}', 'InterviewHistoryController@getListByStudentId');
    Route::resource('interview_history', 'InterviewHistoryController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::get('/missing_evaluation/history', 'MissingEvaluationItemController@getListByCompanyId');
    Route::resource('user', 'UserController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);

    Route::group(['prefix' => 'auth'], function ($router) {
        // 管理ログイン関係
        Route::post('login', 'AuthController@login')->name('auth.login');
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');
    });

});
