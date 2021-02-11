<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', 'TextMiningController@index')->name('welcome');

// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/404', function () {
    return view('404');
});

// login
Route::get('/login', 'LoginController@index')->name('login');
Route::post('/postlogin', 'LoginController@postlogin')->name('postlogin');
Route::get('/logout', 'LoginController@logout')->name('logout');

// login siswa
Route::group(['middleware' => 'auth:siswa'], function () {
    Route::get('/siswa', 'SiswaController@index')->name('homesiswa');
});

// login guru dan admin
Route::group(['middleware' => 'auth:guru'], function () {
        Route::get('/admin', 'GuruController@admin')->name('homeadmin')->middleware('is_admin');
        Route::get('/guru', 'GuruController@index')->name('homeguru');
});