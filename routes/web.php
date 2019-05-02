<?php

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

Route::get('/', 'HomeController@index')->name('index');
Route::get('/table', 'HomeController@standings')->name('table');
Route::get('/info/{team}', 'HomeController@info')->name('info');
Route::get('/api-details', 'HomeController@apiDetails')->name('api-details');