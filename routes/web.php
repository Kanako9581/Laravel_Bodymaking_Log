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

Route::get('/', 'PostController@index')->name('top');
Auth::routes();
Route::resource('posts', 'PostController');
Route::resource('likes', 'LikeController')->only(['index', 'store', 'destroy']);
Route::resource('follows', 'FollowController')->only(['index', 'store', 'destroy']);
Route::get('/{users}/follower', 'FollowController@followerIndex');