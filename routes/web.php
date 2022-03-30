<?php

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
// * Qua posso inserire ['register' => false] per esempio, e tolgo il tasto register dalla navbar
Auth::routes();

// * Tutte le rotte sono protette con il middleware auth
Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->namespace('Admin')
    ->group(function () {
        Route::get('/', 'HomeController@index')->name('home');
        Route::patch('/posts/{post}/toggle', 'PostController@toggle')->name('posts.toggle');
        Route::patch('/categories/truncate', 'CategoryController@truncate')->name('categories.truncate');
        Route::resource('posts', 'PostController');
        Route::resource('categories', 'CategoryController');
    });

// * {any} Un parametro che può anche non esserci e se c'è ".*" può essere qualunque cosa. In questo modo qualunque rotta deve andare su Vue e andrà gestita in frontend.
Route::get('{any?}', function () {
    return view('guest.home');
})->where("any", ".*");
