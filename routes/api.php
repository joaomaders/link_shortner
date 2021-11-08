<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/urls', [UrlController::class, 'list']);

Route::prefix('/url')->group( function () {
        Route::get('/access/{short_url}', [UrlController::class, 'access']);
        Route::get('/exists/{short_url}', [UrlController::class, 'checkIfExists']);
        Route::post('/store', [UrlController::class, 'store']);
    }
);

