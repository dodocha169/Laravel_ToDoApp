<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\FolderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get
('/dashboard', function () {
    return view
    ('dashboard');
})
    ->middleware
    (['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')
    ->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])
            ->name('profile.destroy');
    });

Route::get
('/', [HomeController::class, 'index'])
    ->name('home');

Route::get
("/folders/{id}/tasks", [TaskController::class, "index"])
    ->name("tasks.index");

Route::get
('/folders/create', [FolderController::class, "showCreateForm"])
    ->name('folders.create');

Route::post
('/folders/create', [FolderController::class, "create"]);

Route::get
('/folders/{id}/tasks/create', [TaskController::class, "showCreateForm"])
    ->name('tasks.create');

Route::post
('/folders/{id}/tasks/create', [TaskController::class, "create"]);

Route::get
('/folders/{id}/edit', [FolderController::class, "showEditForm"])
    ->name('folders.edit');

Route::post
('/folders/{id}/edit', [FolderController::class, "edit"]);

require __DIR__ . '/auth.php';
