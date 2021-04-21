<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('book/create', 'LibrosController@store')->name('create');
Route::post('book/delete', 'LibrosController@destroy')->name('delete');
Route::get('book/{isbn}', 'LibrosController@detalis')->name('details');
Route::get('book/', 'LibrosController@show')->name('show');
