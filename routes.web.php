<?php

/*
|--------------------------------------------------------------------------
| WEB Application Routes
|--------------------------------------------------------------------------
|
| Here are all the routes for the online web interface, admin portals and e
| everything related to HTTP HTML
*/

Route::get('/', function () {
    return view('welcome');
});


//all public admin routes here, temporarily
Route::group([], function () {
    Route::group(['prefix' => 'questionPapers'], function () {
        Route::get('add', [
            'uses' => 'DocumentsController@getAddQP', 'as' => 'admin.getAddQP'
        ]);
        Route::post('add', [
            'uses' => 'DocumentsController@postAddQP', 'as' => 'admin.postAddQP'
        ]);
    });

    Route::group(['prefix' => 'resumes'], function () {
        Route::get('add', [
            'uses' => 'DocumentsController@getAddResume', 'as' => 'admin.getAddResume'
        ]);
        Route::post('add', [
            'uses' => 'DocumentsController@postAddResume', 'as' => 'admin.postAddResume'
        ]);
    });


    Route::group(['prefix' => 'notifications'], function () {
        Route::get('add', [
            'uses' => 'NotificationsController@getCreateNotification', 'as' => 'admin.getAddNotif'
        ]);
        Route::post('add', [
            'uses' => 'NotificationsController@postCreateNotification', 'as' => 'admin.postAddNotif'
        ]);
    });
});


