<?php

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

// route crear nuevo libro
Route::post('book/create','LibrosController@store')->name('create');
Route::post('book/delete','LibrosController@destroy')->name('delete');
Route::get('book/{isbn}','LibrosController@detalis')->name('details');
Route::get('book/','LibrosController@show')->name('show');
