<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Route::post('login', [\App\Http\Controllers\LoginController::class, 'authenticate'])->name('login');
//Route::get('register', function(){
//    return redirect()->route('login');
//})->name('register');
Route::post('login', [\App\Http\Controllers\LoginController::class, 'authenticate'])->name('login');
Route::get('logout', function(){
    \Illuminate\Support\Facades\Auth::logout();
    return redirect()->route('login');
});

Route::get('/', function () {
    dd(\Illuminate\Support\Facades\Hash::make('ewqewqewq'));
})->name('home');

Route::group(['prefix' => 'admin','as' => 'admin.', 'middleware' => ['isAdmin']], function(){
    Route::get('/', [\App\Http\Controllers\AdminController::class,'index'])->name('index');
    Route::get('/check-gamers-answers', [\App\Http\Controllers\AdminController::class,'getUsersData'])->name('check-gamers-answers');
    Route::get('/table-results',[\App\Http\Controllers\AdminController::class,'tableResults'])->name('table-results');

    Route::post('/reset-answers', [\App\Http\Controllers\AdminController::class,'resetAnswers'])->name('reset-answers');
    Route::post('/change-type-answer', [\App\Http\Controllers\AdminController::class,'changeTypeAnswer'])->name('change-type-answer');
    Route::post('/play-pause', [\App\Http\Controllers\AdminController::class,'changePlayPause'])->name('play-pause');
    Route::post('/change-score', [\App\Http\Controllers\AdminController::class,'changeScore'])->name('change-score');
});

Route::get('get-meta-data', [\App\Http\Controllers\AdminController::class,'getMetaData'])->name('get-meta-data');

Route::group(['prefix' => 'gamer','as' => 'gamer.','middleware' => 'isGamer'], function(){
    Route::get('/', [\App\Http\Controllers\UserController::class,'index'])->name('index');
    Route::post('/create-answer', [\App\Http\Controllers\UserController::class,'answerCreate'])->name('create-answer');
});
