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

Route::get('/', 'NianbaoController@entrance')->name('nianbao.entrance');
Route::get('next/{id}', 'CorpsController@next')->name('corp.next');
Route::get('prev/{id}', 'CorpsController@prev')->name('corp.prev');
Route::resource('corp', 'CorpsController')->only(['index', 'show', 'edit', 'update']);
