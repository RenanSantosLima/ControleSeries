<?php

use App\Http\Controllers\API\SeriesController;
use App\Models\Episode;
use App\Models\Serie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Api
/*Route::get('/series', function () {
    return Serie::all();
});*/

Route::middleware('auth:sanctum')->group(function () {
    //Criar todas as rotas
    Route::apiResource('/series', SeriesController::class);
    Route::get('/series/{series}/seasons', function (Serie $series) {
        return $series->seasons;
    });

    Route::get('/series/{series}/episodes', function (Serie $series) {
        return $series->episodes;
    });

    Route::patch('/episodes/{episode}', function (Episode $episode, Request $request) {
        $episode->watched = $request->watched;
        $episode->save();

        return $episode;
    });
});

Route::post('/login', function (Request $request) {
    $credentials = $request->only(['email', 'password']);
    if (Auth::attempt($credentials) === false) {
        return response()->json("Unauthorized", 401);
    }

    $user = Auth::user();
    $user->tokens()->delete();
    $token = $user->createToken('token', ['is_adim']);

    return response()->json($token->plainTextToken);
});

//Cria uma rota por vez
//Route::get('/series', [SeriesController::class, 'index']);
//Route::post('/series', [SeriesController::class, 'store']);
