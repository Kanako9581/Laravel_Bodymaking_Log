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
Route::patch('/posts/{post}/edit', 'PostController@update')->name('posts.update');
Route::resource('posts', 'PostController');
// Route::get('/', 'PostController@search')->name('posts.search');
Route::patch('/posts/{post}', 'PostController@update_image')->name('posts.update_image');
Route::patch('/posts/{post}/toggle_like', 'PostController@toggle_like')->name('posts.toggle_like');
Route::get('/tags/{tag}', 'PostController@tag')->name('posts.tag');
Route::resource('likes', 'LikeController')->only(['index', 'store', 'destroy']);
Route::resource('follows', 'FollowController')->only(['index', 'store', 'destroy']);
Route::get('/users/{user}/follower', 'FollowController@followerIndex')->name('follows.follower');
Route::resource('comments', 'CommentController')->only(['store', 'destroy']);
Route::resource('users', 'UserController')->only(['index', 'show', 'edit', 'update']);
Route::get('users/{user}/edit_image', 'UserController@editImage')->name('users.edit_image');
Route::patch('users/{user}/edit_image', 'UserController@updateImage')->name('users.update_image');