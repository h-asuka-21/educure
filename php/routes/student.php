<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api', 'prefix' => 'student', 'namespace' => 'Student', 'as' => 'student.'], function () {
    Route::get('/student/evaluation', 'StudentController@evaluation');
    Route::get('/student/teacher_evalution', 'StudentController@teacher_evalution');
    Route::get('/student/evaluation_ranking', 'StudentController@evaluationRanking');
    Route::get('/student/self_ranking', 'StudentController@selfRanking');
    Route::get('/student/progress', 'ProgressController@getGraphData');
    Route::get('/student/progress_list', 'ProgressController@getProgressList');
    Route::get('/student/evalutiontest', 'StudentController@getTeacherEvaluation');
    Route::put('/student', 'StudentController@update');
    Route::get('/reserve/unreported', 'ReservationController@getUnreported');
    Route::post('/reserve/unreported', 'ReservationController@bulkReport');
    Route::get('/test/has_test', 'ProgressController@getLatestUnAnsweredTest');
    Route::get('/progress/current', 'ProgressController@getCurrent');
    Route::post('/progress/save_student_progoress', 'ProgressController@saveStudentProgress');
    Route::resource('schedule', 'ScheduleController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::get('/reserve/today', 'ReservationController@today');
    Route::post('/reserve', 'ReservationController@reserve');
    Route::get('/interview_history/student', 'InterviewHistoryController@getListByStudentId');
    Route::resource('test', 'TestController', ['only' => ['index', 'show', 'store', 'update']]);
    Route::resource('report', 'ReportController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::resource('/curriculum', 'CurriculumController', ['only' => ['index', 'show']]);
    Route::resource('/score', 'ScoreController', ['only' => ['index', 'show', 'store', 'update']]);
    Route::group(['prefix' => 'auth'], function ($router) {
        // 管理ログイン関係
        Route::post('login', 'AuthController@login')->name('auth.login');
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');
    });
});
