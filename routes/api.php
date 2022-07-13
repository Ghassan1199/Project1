<?php

use App\Http\Controllers\AdminCon;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\gymController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



//the route to create the admin with the gym
Route::post('register_admin',[AdminCon::class,'store']);
Route::post('admin_login',[LoginController::class,'adminLogin']);
Route::post('coach_login',[LoginController::class,'coachLogin']);
Route::post('user_login',[LoginController::class,'userLogin']);
Route::post('create_user',[UsersController::class,'store']);
Route::post('create_coach',[CoachController::class,'store']);
Route::post('create_sub_user',[UsersController::class,'create_sup']);
Route::post('create_contract',[CoachController::class,'create_cont']);
Route::post('create_qual',[CoachController::class,'create_qual']);
Route::post('add_days',[UsersController::class,'editTrainingDays']);
Route::get('show_user/{id}',[UsersController::class,'show']);
Route::get('show_coach/{id}',[CoachController::class,'show']);
Route::get('show_gym/{id}',[gymController::class,'show']);