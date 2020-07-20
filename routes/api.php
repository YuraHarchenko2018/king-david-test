<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use MongoDB\Client as Mongo;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'UsersController@login');
Route::post('registration', 'UsersController@registration');

Route::resource('board', 'BoardsController');

Route::resource('task', 'TasksController');
Route::post('task/uploadPhoto', "UploadController@store");
Route::post('attachLabels/{id}', 'TasksController@attachLabels');

Route::resource('label', 'LabelsController');
Route::resource('task-statuses', 'TaskStatusesController');

Route::get('statistics/totalTask', 'StatisticController@totalTask');
Route::get('statistics/totalDoneTask', 'StatisticController@totalDoneTask');
Route::get('statistics/progressInPercentage', 'StatisticController@progressInPercentage');
Route::get('statistics/bestUserByLastWeek', 'StatisticController@bestUserByLastWeek');