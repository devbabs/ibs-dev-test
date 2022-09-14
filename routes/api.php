<?php

use App\Http\Controllers\Api\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('auth')->group(function ($router)
{
    Route::post('login', 'AuthController@login')->name('api.auth.login');
    Route::post('logout', 'AuthController@logout')->name('api.auth.logout');
});

Route::get('me', 'AuthController@me')->name('api.auth.me')->middleware('api.auth');

Route::resource('books', BookController::class)->only([
    'index',
    'show',
    'store'
])->names([
    'index' => 'books.index',
    'show' => 'books.show',
    'store' => 'books.store'
]);

Route::post('books/{book}/comment', [BookController::class, 'comment'])->name('books.comment');

Route::get('books/{book}/comments', [BookController::class, 'comments'])->name('books.comments');
