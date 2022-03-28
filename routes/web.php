<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResultController;

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
    return view('welcome');
});

Route::get('/login', function(){

})->name('login'); 


Route::middleware(['auth'])->group(function() {
    Route::get('/results', [ResultController::class, 'index'])->name('results.index');
    Route::get('/results/search', [ResultController::class, 'search'])->name('results.search');
    Route::get('/results/{id}', [ResultController::class, 'show'])->name('results.show');
});
