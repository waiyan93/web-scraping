<?php

use App\Http\Controllers\CsvController;
use App\Http\Controllers\ResultController;
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


Route::middleware(['auth'])->group(function() {
    Route::get('/', [ResultController::class, 'index']);
    
    Route::get('/results/search', [ResultController::class, 'search'])->name('results.search');
    Route::get('/results/{id}/html', [ResultController::class, 'showHTML'])->name('results.html');
    Route::resource('results', ResultController::class)->except([
        'update', 'destory'
    ]);
    Route::get('/csvs/{id}', [CsvController::class, 'show']);
});


require __DIR__.'/auth.php';
