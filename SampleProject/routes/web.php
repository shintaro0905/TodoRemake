<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController; // 追加するのを忘れない
use App\Http\Controllers\TaskController; // 追加するのを忘れない
use App\Http\Controllers\FolderController; // 追加するのを忘れない

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

//ログイン機能

Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/folders/create', [FolderController::class, 'create'])->name('folders.create');
    Route::post('/folders/create', [FolderController::class, 'createFolder'])->name('folders.create');

        Route::group(['middleware' => 'can:view,folder'], function () {
            Route::get('/{folder}', [TaskController::class, 'index'])->name('tasks.index');

            Route::get('/{folder}/tasks/create', [TaskController::class, 'showCreateForm'])->name('tasks.create');
            Route::post('/{folder}/tasks/create', [TaskController::class, 'create'])->name('tasks.create');

            Route::get('/folders/{folder}/tasks/{task}/edit', [TaskController::class, 'showEditForm'])->name('tasks.edit');
            Route::post('/folders/{folder}/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');


    // Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
        });
});

