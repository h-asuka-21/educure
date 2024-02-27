<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api', 'prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin.'], function () {
    Route::get('/student/detail/{id}', 'StudentController@detail');
    Route::get('/student/reserve', 'ReserveController@getStudentPerDate');
    Route::get('/student/progress/{id}', 'ProgressController@getGraphData');
    Route::get('/student/evaluation/{id}', 'StudentController@evaluation');
    Route::get('/student/teacher_evalution/{id}', 'StudentController@teacher_evalution');
    Route::get('/student/progress_list/{id}', 'ProgressController@getProgressList');
    Route::get('/student/student_statistics', 'StudentController@student_statistics');
    Route::get('/student/company_statistics', 'StudentController@company_statistics');
    Route::get('/student/company_ranking', 'StudentController@companyRanking');
    Route::get('/course/curriculum/{id}', 'CourseController@curriculum');
    Route::post('/course/curriculum/{id}', 'CourseController@saveCurriculum');
    Route::get('/course/autocomplete', 'CourseController@autocomplete');
    Route::get('/course_group/autocomplete/{id}', 'CourseController@autocompleteWithCompanyId');
    Route::get('/course_group_student/autocomplete/{id}', 'CourseController@autocompleteWithStudentId');
    Route::get('/curriculum/autocomplete', 'CurriculumController@autocomplete');
    Route::get('/test/autocomplete', 'TestController@autocomplete');
    Route::get('/interview_history/student/{id}', 'InterviewHistoryController@getListByStudentId');
    Route::get('/missing_evaluation/history', 'MissingEvaluationItemController@getListByCompanyId');
    Route::resource('interview_history', 'InterviewHistoryController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::get('schedule_user/autocomplete', 'UserController@autocomplete');
    Route::get('/company/autocomplete', 'CompanyController@autocomplete');
    Route::get('/schedule/calendar', 'ScheduleController@calendar');
    Route::post('/schedule/bulk', 'ScheduleController@bulk');
    Route::get('/step/autocomplete', 'StepController@autocomplete');
    Route::get('/progress/students', 'ProgressController@getUsersAndProgresses');
    Route::resource('company', 'CompanyController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::resource('user', 'UserController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::resource('student', 'StudentController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::resource('test', 'TestController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::resource('curriculum', 'CurriculumController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::resource('course', 'CourseController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::resource('step', 'StepController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::resource('schedule', 'ScheduleController', ['only' => ['store', 'update', 'destroy']]);
    Route::get('step_curriculum/{id}', 'StepController@getListByCurriculumIdWithPagenate');
    Route::group(['prefix' => 'auth'], function ($router) {
        // 管理ログイン関係
        Route::post('login', 'AuthController@login')->name('auth.login');
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');
        Route::post('update', 'AuthController@update');
    });
});
