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
  

// Route::get('/webcam', 'WebcamController@index');
// Route::post('/webcam', 'WebcamController@store')->name('webcam.capture');

// Auth::routes();

Route::get('/mutasi', 'HomeController@mutasi')->name('home');

// kontrol belum selesai
Route::match(array('GET','POST'),'/kontrol', 'PasienController@kontrol')->name('kontrol');
Route::get('kontrol-show', 'PasienController@showkontrol')->name('kontrol.show');

Route::get('/updateantrean', 'HomeController@updateantrean')->name('updatetask4');
Route::get('/404', function () {
    return view('404');
});

// fallback roue when not correct link
Route::fallback(function () {
    return view('login');
});

// login
Route::get('/login', 'LoginController@index')->name('login');
Route::post('/postlogin', 'LoginController@postlogin')->name('postlogin');
Route::post('/logout', 'LoginController@logout')->name('logout');
Route::get('/ambil_icd', 'PegawaiController@ambil_icd')->name('ambil_icd');
Route::get('/cekstokobat', 'PegawaiController@cekstokobat')->name('cekstokobat');


//admin
Route::group(['middleware' => ['auth:pegawai', 'is_admin']], function () {
    Route::match(array('GET','POST'),'/admin', 'PegawaiController@admin')->name('homeadmin');
    Route::match(array('GET','POST'),'/riwayatradiologi', 'PegawaiController@riwayatradiologi')->name('riwayatradiologi');
    Route::match(array('GET','POST'),'/persentasepoli', 'PegawaiController@persentasepoli')->name('persentasepoli');
    Route::match(array('GET','POST'),'/persentaseranap', 'PegawaiController@persentaseranap')->name('persentaseranap');
    Route::get('/admin/profile', 'PegawaiController@profile')->name('homeadmin/profile');
    Route::post('/admin/profile/password', 'PegawaiController@gantipassword')->name('homeadmin/profile/password');

    Route::get('/admin/kelolalogin', 'LoginController@kelolalogin')->name('kelolalogin');
    Route::post('/admin/tambahlogin', 'LoginController@tambahlogin')->name('tambahlogin');
    Route::post('/admin/aksilogin', 'LoginController@aksilogin')->name('aksilogin');

    Route::get('/admin/persentase', 'PegawaiController@persentase')->name('persentase');
    Route::get('/admin/penilaianperawat', 'PegawaiController@penilaianperawat')->name('penilaianperawat');
    Route::get('/admin/hargaobat', 'PegawaiController@hargaobat')->name('hargaobat');
    Route::get('/admin/indikatormutu', 'IndikatorMutuController@indikatormutu')->name('indikatormutu');
    Route::get('/admin/indikatormutu/kebersihan-tangan', 'IndikatorMutuController@kebersihanTangan')->name('indikatormutu.kebersihan_tangan');
    Route::get('/admin/indikatormutu/hapus/{id}', [App\Http\Controllers\IndikatorMutuController::class, 'hapus'])->name('hapus.mutu');
    Route::get('/admin/indikatormutu/edit/{id}', [App\Http\Controllers\IndikatorMutuController::class, 'edit'])->name('edit.mutu');
    Route::post('/admin/indikatormutu/update/{id}', [App\Http\Controllers\IndikatorMutuController::class, 'update'])->name('update.mutu');
    Route::get('/admin/indikatormutu/delete/{id}', [App\Http\Controllers\IndikatorMutuController::class, 'destroy'])->name('hapus.mutu');

     Route::get('/admin/indikatormutu/apd', 'IndikatorMutuController@apd')->name('indikatormutu.apd');
     Route::get('/indikatormutu/identifikasi-pasien', 'IndikatorMutuController@identifikasiPasien')->name('indikatormutu.identifikasi_pasien');
     Route::get('/indikatormutu/seksio-emergensi', 'IndikatorMutuController@seksioEmergensi')->name('indikatormutu.seksio_emergensi');
     Route::get('/indikatormutu/waktu-tunggu-rajal', 'IndikatorMutuController@waktuTungguRajal')->name('indikatormutu.waktu_tunggu_rajal');
     Route::get('/indikatormutu/penundaan-operasi','IndikatorMutuController@penundaanOperasi')->name('indikatormutu.penundaan_operasi');
     Route::get('/indikatormutu/visite-dokter', 'IndikatorMutuController@visiteDokter')->name('indikatormutu.visite_dokter');
     Route::get('/indikatormutu/hasil-kritis-lab', 'IndikatorMutuController@hasilKritisLab')->name('indikatormutu.hasil_kritis_lab');
     Route::get('/indikatormutu/formularium', 'IndikatorMutuController@formularium')->name('indikatormutu.formularium');
     Route::get('/indikatormutu/clinical-pathway', 'IndikatorMutuController@clinicalPathway')->name('indikatormutu.clinical_pathway');
     Route::get('/indikatormutu/pencegahan-jatuh', 'IndikatorMutuController@pencegahanJatuh')->name('indikatormutu.pencegahan_jatuh');
     Route::get('/indikatormutu/tanggap-komplain', 'IndikatorMutuController@tanggapKomplain')->name('indikatormutu.tanggap_komplain');
     Route::get('/indikatormutu/kepuasan-pasien', 'IndikatorMutuController@kepuasanPasien')->name('indikatormutu.kepuasan_pasien');
     Route::post('/indikatormutu/simpan-mutu', [App\Http\Controllers\IndikatorMutuController::class, 'simpanmutu'])->name('indikatormutu.simpan-mutu');

    Route::get('/admin/kerusakan-all', 'PetugasController@kerusakan_all')->name('kerusakan.all');
    Route::post('/admin/kerusakan-all/{id}', 'PetugasController@update')->name('kerusakan-update-admin');
    
    Route::match(array('GET','POST'),'/admin/pemesananobat', 'PegawaiController@pemesananobat')->name('pemesananobat');
    Route::get('/admin/selisihobat', 'PegawaiController@selisihobat')->name('selisihobat');
    // Route::get('/admin/updatehargaobat', 'PegawaiController@updatehargaobat')->name('updatehargaobat');
    Route::match(array('GET','POST'), '/admin/biayarajal', 'PegawaiController@biayarajal')->name('admin-biayarajal');
    Route::match(array('GET','POST'),'/admin/tutupbilling', 'PegawaiController@tutupbilling')->name('tutupbilling');

    Route::get('/absensi/rekapadmin', 'WebcamController@rekapadmin')->name('absensi.rekapadmin');
    Route::match(array('GET','POST'),'/absensi/detailrekapadmin', 'WebcamController@detailrekapadmin')->name('absensi.detailrekapadmin');

    Route::get('/barcode', 'BarcodeController@barcode')->name('barcode');
    Route::get('/getpasien', 'BarcodeController@getpasien')->name('getpasien');
    Route::get('/riwayatperiksa', 'BarcodeController@riwayatperiksa')->name('riwayatperiksa');
    Route::post('/simpanregistrasi', 'BarcodeController@simpanregistrasi')->name('registrasi.simpan');
    Route::get('/cetakkartu', 'BarcodeController@cetakkartu')->name('cetakkartu');
    Route::match(array('GET','POST'),'/tambahpasien', 'BarcodeController@tambahpasien')->name('tambahpasien');
    Route::get('/ambil_pasien', 'BarcodeController@ambil_pasien')->name('ambil_pasien');

    // penilaian SIMRS
    Route::match(array('GET','POST'),'erm/pengisian-igd', 'PengisianSimrsController@ermigd')->name('simrs.igd');
    Route::match(array('GET','POST'),'erm/pengisian-rawatinap', 'PengisianSimrsController@ermrawatinap')->name('simrs.rawatinap');
    Route::match(array('GET','POST'),'erm/pengisian-rawatjalan', 'PengisianSimrsController@ermrawatjalan')->name('simrs.rawatjalan');
    Route::match(array('GET','POST'),'erm/pengisian-farmasi', 'PengisianSimrsController@ermfarmasi')->name('simrs.farmasi');
    Route::match(array('GET','POST'),'erm/pengisian-gizi', 'PengisianSimrsController@ermgizi')->name('simrs.gizi');
    Route::match(array('GET','POST'),'erm/pengisian-operasi', 'PengisianSimrsController@ermoperasi')->name('simrs.operasi');
    // upload data 
    Route::match(array('GET','POST'),'/berkasdigital', 'PengisianSimrsController@berkasdigital')->name('simrs.berkasdigital');
    Route::match(array('GET','POST'),'/berkasdigitalsimpan', 'PengisianSimrsController@berkasdigitalsimpan')->name('simrs.berkasdigitalsimpan');
    Route::delete('/berkasdigitalhapus/{$id}', 'PengisianSimrsController@berkasdigitalhapus')->name('simrs.berkasdigitalhapus');
    
});

// login dokter
Route::group(['middleware' => ['auth:pegawai', 'is_dokter']], function () {
    Route::get('/dokter', 'PegawaiController@dokter')->name('homedokter');
    Route::get('/dokter/profile', 'PegawaiController@profile')->name('homedokter/profile');
    Route::post('/dokter/profile/password', 'PegawaiController@gantipassword')->name('homedokter/profile/password');

    Route::get('/apotek', 'ApotekController@apotek')->name('homeapotek');

    Route::get('/pasien/ralan', 'PasienController@show')->name('pasien/ralan');    
    Route::get('/form/soap', 'FormController@formsoap')->name('form-soap');
    Route::get('/form/listdata', 'FormController@listdata')->name('form/listdata');
    Route::post('/form/tambah', 'FormController@create')->name('form/tambah');
    Route::get('/form/resepracikan', 'FormController@resepracikan')->name('resepracikan');
    Route::get('/form/hapus', 'FormController@delete')->name('form/hapus');

    Route::match(array('GET','POST'),'/form', 'FormController@menurajal')->name('menu-rajal');
    Route::get('/form/awalmedisparu', 'FormController@rjawalmedisparu')->name('form-awalmedisparu');
    Route::get('/form/awalmedisbedah', 'FormController@rjawalmedisbedah')->name('form-awalmedisbedah');
    Route::get('/form/awalmedissyaraf', 'FormController@rjawalmedissyaraf')->name('form-awalmedissyaraf');
    Route::get('/form/awalmedisobsgyn', 'FormController@rjawalmedisobsgyn')->name('form-awalmedisobsgyn');
    Route::get('/form/awalmedisanak', 'FormController@rjawalmedisanak')->name('form-awalmedisanak');
    Route::get('/form/awalmedismata', 'FormController@rjawalmedismata')->name('form-awalmedismata');
    Route::get('/form/awalmedisdalam', 'FormController@rjawalmedisdalam')->name('form-awalmedisdalam');
    Route::get('/form/awalmedisjantung', 'FormController@rjawalmedisjantung')->name('form-awalmedisjantung');
    Route::get('/form/awalmedis-', 'FormController@rjawalmedisumum')->name('form-awalmedis-');
    Route::get('/form/penanganandokter', 'FormController@rjpenanganandokter')->name('form-penanganandokter');
    Route::get('/form/diagnosa', 'FormController@rjdiagnosa')->name('form-diagnosa');
    Route::get('/form/resume', 'FormController@rjresume')->name('form-resume');
    Route::get('/form/resumegabung', 'FormController@rjresumegabung')->name('form-resumegabung');
    Route::get('/form/permintaanlab', 'FormController@permintaanlab')->name('form-permintaanlab');
    Route::get('/form/permintaanrad', 'FormController@permintaanrad')->name('form-permintaanrad');
    Route::get('/form/resep', 'FormController@rjresep')->name('form-resep');
    Route::get('/form/rincianbiaya', 'FormController@rincianbiaya')->name('form-rincianbiaya');

    // Menu Rawat Inap
    Route::match(array('GET','POST'),'/menu-ranap', 'FormControllerRanap@menuranap')->name('menu-ranap');
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
    Route::get('/menu-ranap/rincianbiaya', 'FormControllerRanap@rincianbiaya')->name('formri-rincianbiaya');

    // Menu Radologi
    Route::match(array('GET','POST'),'/menu-radiologi', 'FormControllerRad@menuradiologi')->name('menu-radiologi');
    Route::get('/menu-radiologi/form', 'FormControllerRad@formradiologi')->name('form-radiologi');
    Route::post('/menu-radiologi/tambah', 'FormControllerRad@create')->name('formrad-tambah');
});

// login pegawai
Route::group(['middleware' => ['auth:pegawai', 'is_petugas']], function () {
    Route::get('/pegawai', 'WebcamController@index')->name('homepegawai');
    Route::post('/pegawai/webcam', 'WebcamController@store')->name('webcam.capture');
    Route::get('/pegawai/profile', 'PegawaiController@profile')->name('homepegawai/profile');
    Route::post('/pegawai/profile/password', 'PegawaiController@gantipassword')->name('homepegawai/profile/password');

    Route::get('/absensi/rekap', 'WebcamController@rekap')->name('absensi.rekap');

    Route::get('/antrean', 'PetugasController@antrean')->name('antrean');
    Route::get('/kerusakan', 'PetugasController@kerusakan')->name('kerusakan');
    Route::post('/kerusakan/tambah', 'PetugasController@create')->name('kerusakan-tambah');
    Route::post('/kerusakan/update/{id}', 'PetugasController@update')->name('kerusakan-update');
    Route::post('/kerusakan/hapus/{id}', 'PetugasController@hapus')->name('kerusakan-hapus');
    Route::get('/antrean/updateantrean', 'PetugasController@updateantrean')->name('updateantrean');
});
