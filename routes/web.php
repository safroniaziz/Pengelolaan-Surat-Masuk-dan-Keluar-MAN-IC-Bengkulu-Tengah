<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JabatanController;
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

Route::group(['prefix'  => 'admin/'],function(){
    Route::get('/',[DashboardController::class, 'dashboard'])->name('admin.dashboard');

    Route::group(['prefix'  => 'manajemen_jabatan'],function(){
        Route::get('/',[JabatanController::class, 'index'])->name('admin.jabatan');
        Route::get('/add',[JabatanController::class, 'add'])->name('admin.jabatan.add');

        Route::post('/',[JabatanController::class, 'post'])->name('admin.jabatan.post');
        Route::get('/{id}/edit',[JabatanController::class, 'edit'])->name('admin.jabatan.edit');
        Route::patch('/',[JabatanController::class, 'update'])->name('admin.jabatan.update');
        Route::delete('{id}/delete',[JabatanController::class, 'delete'])->name('admin.jabatan.delete');
    });
});

