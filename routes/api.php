<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\appusers\AppUsersController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\QuizCategoryController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\UnitMovementController;
use App\Http\Controllers\Api\Defect;

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

Route::middleware('auth:appUser')->get('/user', function (Request $request) {
    return $request->user();
});



        Route::group(['prefix' => 'user'], function () {

	    Route::group(['middleware' => ['auth:appUser']], function () {

	 

	  	Route::get('home', [HomeController::class, 'apiHome']);
	  	Route::get('quizcategory/{id}/{model_id}/{vid}', [QuizCategoryController::class, 'quizcategory']);
	  	Route::get('quiz/{id}/{vid}/{shop_id}', [QuizController::class, 'quiz']);
	    Route::post('answerquery', [QuizController::class, 'answerquery']);
	  	Route::get('loadquery/{cat_id}/{quiz_id}', [QuizController::class, 'loadquery']);
	  	Route::get('sheduledunits', [UnitMovementController::class, 'sheduledunits']);
	  	Route::post('moveunitfromstore', [UnitMovementController::class, 'moveunitfromstore']);
	  	Route::get('getcomponent/{route_id}/{vehicle_id}', [UnitMovementController::class, 'getcomponent']);
	    Route::post('moveunit', [UnitMovementController::class, 'moveunit']);
	    Route::get('qdefects/{vehicle_id}', [Defect::class, 'qdefects']);
	    Route::get('loaddefects/{defect_id}', [Defect::class, 'loaddefects']);
	    Route::get('unitswithdefects', [Defect::class, 'unitswithdefects']);
	    Route::post('correctdefect', [Defect::class, 'correctdefect']);
	    Route::get('checkunitshop/{chasis}/{shop_id}', [UnitMovementController::class, 'checkunitshop']);

	     Route::post('profile/password/update', [AppUsersController::class,'password'])->name('password');  
		 
		 Route::get('unitsinshop/{shop_id}', [UnitMovementController::class, 'unitsinshop']);

	    
	  	
	  Route::get('profile', function (Request $request) {

	  	$master=array();
	  	$master['user']=$request->user();
	  	$master['shop']= $request->user()->shop;

             return $master;
        });




});

Route::post('login', [AppUsersController::class,'login'])->name('login');   


});



