<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\SuratMasukController;
use App\Http\Controllers\Admin\SuratKeluarController;
use App\Http\Controllers\Admin\JenisSuratController;
use App\Http\Controllers\Admin\DisposisiSuratController;

use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\Tu\StafTuDashboardController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Route Admin
Route::group(['prefix'  => 'admin/'],function(){
    Route::get('/',[DashboardController::class, 'dashboard'])->name('admin.dashboard');

    Route::group(['prefix'  => 'manajemen_jabatan'],function(){
        Route::get('/',[JabatanController::class, 'index'])->name('admin.jabatan');
        Route::get('/add',[JabatanController::class, 'add'])->name('admin.jabatan.add');

        Route::post('/',[JabatanController::class, 'post'])->name('admin.jabatan.post');
        Route::get('{id}/edit',[JabatanController::class, 'edit'])->name('admin.jabatan.edit');
        Route::patch('{id}/update',[JabatanController::class, 'update'])->name('admin.jabatan.update');
        Route::delete('{id}/delete',[JabatanController::class, 'delete'])->name('admin.jabatan.delete');
    });
    Route::group(['prefix'  => 'manajemen_disposisi_surat'],function(){
        Route::get('/',[DisposisiSuratController::class, 'index'])->name('admin.disposisi_surat');
        Route::get('/add',[DisposisiSuratController::class, 'add'])->name('admin.disposisi_surat.add');

        Route::post('/',[DisposisiSuratController::class, 'post'])->name('admin.disposisi_surat.post');
        Route::get('{id}/edit',[DisposisiSuratController::class, 'edit'])->name('admin.disposisi_surat.edit');
        Route::patch('{id}/update',[DisposisiSuratController::class, 'update'])->name('admin.disposisi_surat.update');
        Route::delete('{id}/delete',[DisposisiSuratController::class, 'delete'])->name('admin.disposisi_surat.delete');
    });
    Route::group(['prefix'  => 'manajemen_surat_masuk'],function(){
        Route::get('/',[SuratMasukController::class, 'index'])->name('admin.surat_masuk');
        Route::get('/add',[SuratMasukController::class, 'add'])->name('admin.surat_masuk.add');

        Route::post('/',[SuratMasukController::class, 'post'])->name('admin.surat_masuk.post');
        Route::get('{id}/edit',[SuratMasukController::class, 'edit'])->name('admin.surat_masuk.edit');
        Route::patch('{id}/update',[SuratMasukController::class, 'update'])->name('admin.surat_masuk.update');
        Route::delete('{id}/delete',[SuratMasukController::class, 'delete'])->name('admin.surat_masuk.delete');
    });
    Route::group(['prefix'  => 'manajemen_surat_keluar'],function(){
        Route::get('/',[SuratKeluarController::class, 'index'])->name('admin.surat_keluar');
        Route::get('/add',[SuratKeluarController::class, 'add'])->name('admin.surat_keluar.add');

        Route::post('/',[SuratKeluarController::class, 'post'])->name('admin.surat_keluar.post');
        Route::get('{id}/edit',[SuratKeluarController::class, 'edit'])->name('admin.surat_keluar.edit');
        Route::patch('{id}/update',[SuratKeluarController::class, 'update'])->name('admin.surat_keluar.update');
        Route::delete('{id}/delete',[SuratKeluarController::class, 'delete'])->name('admin.surat_keluar.delete');
    });
    Route::group(['prefix'  => 'manajemen_jenis_surat'],function(){
        Route::get('/',[JenisSuratController::class, 'index'])->name('admin.jenis_surat');
        Route::get('/add',[JenisSuratController::class, 'add'])->name('admin.jenis_surat.add');

        Route::post('/',[JenisSuratController::class, 'post'])->name('admin.jenis_surat.post');
        Route::get('{id}/edit',[JenisSuratController::class, 'edit'])->name('admin.jenis_surat.edit');
        Route::patch('{id}/update',[JenisSuratController::class, 'update'])->name('admin.jenis_surat.update');
        Route::delete('{id}/delete',[JenisSuratController::class, 'delete'])->name('admin.jenis_surat.delete');
    });
    Route::group(['prefix'  => 'manajemen_user'],function(){
        Route::get('/',[UserController::class, 'index'])->name('admin.user');
        Route::get('/add',[UserController::class, 'add'])->name('admin.user.add');

        Route::post('/',[UserController::class, 'post'])->name('admin.user.post');
        Route::get('{id}/edit',[UserController::class, 'edit'])->name('admin.user.edit');
        Route::patch('{id}/update',[UserController::class, 'update'])->name('admin.user.update');
        Route::delete('{id}/delete',[UserController::class, 'delete'])->name('admin.user.delete');
        Route::patch('/aktifkanStatus/{id}', 'UserController@aktifkanStatus')->name('admin.user.aktifkanStatus');
        Route::patch('/nonAktifkanStatus/{id}', 'UserController@nonAktifkanStatus')->name('admin.user.nonAktifkanStatus');
    });
});

//Route Staf Tata Usaha
Route::group(['prefix'  => 'staf_tu/'],function(){
    Route::get('/',[StafTuDashboardController::class, 'dashboard'])->name('staf_tu.dashboard');
});