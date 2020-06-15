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

Route::permanentRedirect('/', '/admin')
    ->middleware(['auth','admin']);

Route::prefix('admin')
 ->namespace('Admin')
 ->middleware(['auth','admin'])
 ->group(function(){
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::resource('database', 'DatabasesController');
    Route::post('database/updatedatasiswa', 'DatabasesController@updateDataSiswa') ->name('database.updateDataSiswa');
    Route::post('database/updateNilai', 'DatabasesController@updateNilaiDetail') ->name('database.updateNilaiDetail');
    Route::get('database/deletedatasiswa/{id}', 'DatabasesController@destroyDataSiswa');
    Route::get('database/{id}/editData', 'DatabasesController@editDataSiswa');
    Route::get('database/{id}/editNilai', 'DatabasesController@editNilai');
    // Route::get('database', 'DatabasesController@indexNilai')->name('daftarsiswa');
    Route::get('nilai-detail', 'DatabasesController@indexNilaiDetail')->name('nilai-detail');
 });


// Auth::routes(['register' => false]);
Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
