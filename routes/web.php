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
// Route::get('/', 'TextMiningController@index')->name('welcome');
Route::get('/', 'LoginController@index')->name('welcome');

// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/404', function () {
    return view('404');
});

// fallback roue when not correct link
Route::fallback(function () {
    return view('404');
});

// login
Route::get('/login', 'LoginController@index')->name('login');
Route::post('/postlogin', 'LoginController@postlogin')->name('postlogin');
Route::post('/logout', 'LoginController@logout')->name('logout');
Route::get('/ambil_icd', 'PegawaiController@ambil_icd')->name('ambil_icd');

//admin
Route::group(['middleware' => ['auth:pegawai', 'is_admin']], function () {
    Route::get('/admin', 'PegawaiController@admin')->name('homeadmin');
    Route::get('/admin/profile', 'PegawaiController@profile')->name('homeadmin/profile');
    Route::post('/admin/profile/password', 'PegawaiController@gantipassword')->name('homeadmin/profile/password');

    Route::get('/admin/kelolalogin', 'LoginController@kelolalogin')->name('kelolalogin');
    Route::get('/admin/resetpassword', 'LoginController@resetpassword')->name('resetpassword');
});

// login dokter
Route::group(['middleware' => ['auth:pegawai', 'is_dokter']], function () {
    Route::get('/dokter', 'PegawaiController@dokter')->name('homedokter');
    Route::get('/dokter/profile', 'PegawaiController@profile')->name('homedokter/profile');
    Route::post('/dokter/profile/password', 'PegawaiController@gantipassword')->name('homedokter/profile/password');

    Route::get('/pasien/ralan', 'PasienController@show')->name('pasien/ralan');    
    Route::get('/form/soap', 'FormController@formsoap')->name('form-soap');
    Route::get('/form/listdata', 'FormController@listdata')->name('form/listdata');
    Route::post('/form/tambah', 'FormController@create')->name('form/tambah');
    Route::get('/form/hapus', 'FormController@delete')->name('form/hapus');

    Route::get('/form', 'FormController@menurajal')->name('menu-rajal');
    Route::get('/form/awalmedisparu', 'FormController@rjawalmedisparu')->name('form-awalmedisparu');
    Route::get('/form/awalmedisbedah', 'FormController@rjawalmedisbedah')->name('form-awalmedisbedah');
    Route::get('/form/awalmedissyaraf', 'FormController@rjawalmedissyaraf')->name('form-awalmedissyaraf');
    Route::get('/form/awalmedisobsgyn', 'FormController@rjawalmedisobsgyn')->name('form-awalmedisobsgyn');
    Route::get('/form/awalmedisanak', 'FormController@rjawalmedisanak')->name('form-awalmedisanak');
    Route::get('/form/awalmedismata', 'FormController@rjawalmedismata')->name('form-awalmedismata');
    Route::get('/form/awalmedisdalam', 'FormController@rjawalmedisdalam')->name('form-awalmedisdalam');
    Route::get('/form/awalmedisjantung', 'FormController@rjawalmedisumum')->name('form-awalmedisjantung');
    Route::get('/form/awalmedis-', 'FormController@rjawalmedisumum')->name('form-awalmedis-');
    Route::get('/form/penanganandokter', 'FormController@rjpenanganandokter')->name('form-penanganandokter');
    Route::get('/form/diagnosa', 'FormController@rjdiagnosa')->name('form-diagnosa');
    Route::get('/form/resume', 'FormController@rjresume')->name('form-resume');
    Route::get('/form/permintaanlab', 'FormController@permintaanlab')->name('form-permintaanlab');
    Route::get('/form/permintaanrad', 'FormController@permintaanrad')->name('form-permintaanrad');
    Route::get('/form/resep', 'FormController@rjresep')->name('form-resep');

    // Menu Rawat Inap
    Route::get('/menu-ranap', 'FormControllerRanap@menuranap')->name('menu-ranap');
    Route::get('/menu-ranap/resume', 'FormControllerRanap@riresume')->name('formri-resume');
    Route::get('/menu-ranap/soap', 'FormControllerRanap@risoap')->name('formri-soap');
    Route::get('/menu-ranap/penanganandokter', 'FormControllerRanap@ripenanganandokter')->name('formri-penanganandokter');
    Route::get('/menu-ranap/permintaanlab', 'FormControllerRanap@ripermintaanlab')->name('formri-permintaanlab');
    Route::get('/menu-ranap/permintaanrad', 'FormControllerRanap@ripermintaanrad')->name('formri-permintaanrad');
    Route::get('/menu-ranap/resep', 'FormControllerRanap@riresep')->name('formri-resep');
    Route::get('/menu-ranap/medisumum', 'FormControllerRanap@rimedisumum')->name('formri-medisumum');
    Route::get('/menu-ranap/mediskebidanan', 'FormControllerRanap@rimediskebidanan')->name('formri-mediskebidanan');
    Route::get('/menu-ranap/listdata', 'FormControllerRanap@listdata')->name('formri-listdata');
    Route::post('/menu-ranap/tambah', 'FormControllerRanap@create')->name('formri-tambah');
    Route::get('/menu-ranap/hapus', 'FormControllerRanap@delete')->name('formri-hapus');

});

// login pegawai
Route::group(['middleware' => ['auth:pegawai', 'is_petugas']], function () {
    Route::get('/pegawai', 'PegawaiController@index')->name('homepegawai');
    Route::get('/pegawai/profile', 'PegawaiController@profile')->name('homepegawai/profile');
    Route::post('/pegawai/profile/password', 'PegawaiController@gantipassword')->name('homepegawai/profile/password');

    // Route::get('/pdt/mutasi', 'PetugasController@menurajal')->name('menu-rajal');
});
