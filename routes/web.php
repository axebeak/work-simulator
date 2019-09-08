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

Route::get('/', 'MainController@index');
Route::get('/run', 'MainController@act');


Route::get('/moods', 'MoodController@index');
Route::post('/moods', 'MoodController@store');
Route::resource('/characters', 'CharacterController');
Route::resource('/actions', 'ActionController');
