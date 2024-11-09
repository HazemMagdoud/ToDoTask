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

use App\Http\Controllers\ExportController;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
Route::get('/show/{id}', [TaskController::class, 'show'])->name('tasks.show')->middleware('auth');
Route::post('/tasks/add-or-update/{id}','TaskController@store')->name('tasks.add-or-update')->middleware('auth');
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index')->middleware('auth');
Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy')->middleware('auth');
Route::patch('/tasks/{id}/complete', [TaskController::class, 'markAsCompleted'])->name('tasks.complete')->middleware('auth');
Route::post('/export/csv', [ExportController::class, 'exportCsv'])->name('export.csv');
Route::post('/export/excel', [ExportController::class, 'exportExcel'])->name('export.excel');
Route::post('/export/pdf', [ExportController::class, 'exportPdf'])->name('export.pdf');
Route::get('/search', [TaskController::class, 'search'])->name('search');

