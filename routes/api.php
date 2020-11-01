<?php

Route::post('/auth/reset-password', 'Api\AuthController@passwordResetRequest');
Route::post('/auth/change-password', 'Api\AuthController@changePassword');

Route::group(['middleware' => 'auth:api', 'namespace' => 'Api'], function () {
    Route::get('/stats', 'ListingController@stats');

    Route::get('/tags', 'ListingController@tags');
    Route::get('/categories', 'ListingController@categories');
    Route::resource('/posts', 'PostController', ['only' => ['index', 'show']]);
    Route::resource('/comments', 'CommentController', ['only' => ['index', 'show']]);
});

Route::group(['namespace' => 'Admin', 'middleware' => 'auth:api'], function () {
    Route::resource('/posts', 'PostController', ['only' => ['store', 'update', 'destroy']]);
    Route::put('/posts/{post}/publish', 'PostController@publish')->middleware('admin');
    Route::resource('/categories', 'CategoryController', ['only' => ['show', 'update', 'destroy', 'store']]);
    Route::resource('/tags', 'TagController', ['only' => ['show', 'update', 'destroy', 'store']]);
    Route::delete('/comments/{comment}', 'CommentController@destroy');
    Route::resource('/users', 'UserController', ['middleware' => 'admin', 'only' => ['index', 'destroy']]);
    Route::post('/users', 'UserController@register');
});


Route::post('/posts/{post}/comment', 'BlogController@comment');
