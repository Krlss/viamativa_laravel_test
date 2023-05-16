<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\HallController;
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

Route::resources([
    'films' => MovieController::class,
]);

Route::resources([
    'halls' => HallController::class,
]);

Route::get('films/count/{date_published}', [MovieController::class, 'getCountMoviesByDatePublished']);
Route::get('films/{name}/{id_hall}', [MovieController::class, 'getMovieByNameAndIdHall']);
Route::get('halls/review/{name}', [HallController::class, 'getReviewHallByName']);
