<?php

use App\Http\Controllers\EpisodesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SeasonsController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\Autenticador;
use App\Mail\SeriesCreated;
use App\Models\Serie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/* padrão */
/*Route::get('/', function () {
    return redirect('/series');
})->middleware(Autenticador::class);*/

Route::resource('/series', SeriesController::class)->except(['show']);

/*Route::get('/series/{series}/seasons', [SeasonsController::class, 'index'])->name('seasons.index')->middleware('autendicador');

Route::get('/season/{season}/episodes', [EpisodesController::class, 'index'])->name('episodes.index');
Route::post('/season/{season}/episodes', [EpisodesController::class, 'update'])->name('episodes.update');*/

//Agrupando para uso do middleware
Route::middleware('autendicador')->group(function () {
    Route::get('/', function () {
        return redirect('/series');
    });

    Route::get('/series/{series}/seasons', [SeasonsController::class, 'index'])->name('seasons.index');

    Route::get('/season/{season}/episodes', [EpisodesController::class, 'index'])->name('episodes.index');
    Route::post('/season/{season}/episodes', [EpisodesController::class, 'update'])->name('episodes.update');
});

//login
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('singin');
Route::get('/logout', [LoginController::class, 'destroy'])->name('logout');

//usuarios
Route::get('/register', [UsersController::class, 'create'])->name('users.create');
Route::post('/register', [UsersController::class, 'store'])->name('users.store');

//email
Route::get('/email', function () {
    return new SeriesCreated(
        'Série de teste',
        10,
        5,
        10
    );
});



//Rota de destroy (excluir)
//Route::delete('/series/destroy/{serie}', [SeriesController::class, 'destroy'])->name('series.destroy')->whereNumber('serie');


/* Rotas laravel 9 nova */
/*Route::controller(SeriesController::class)->group(function () {
    Route::get('/series', 'index')->name('series.index');
    Route::get('/series/criar', 'create')->name('series.create');
    Route::post('/series/salvar', 'store')->name('series.store');
});*/

/* Padrão rotas */
/*Route::get('/series', [SeriesController::class, 'index']);
Route::get('/series/criar', [SeriesController::class, 'create']);
Route::post('/series/salvar', [SeriesController::class, 'store']);*/
