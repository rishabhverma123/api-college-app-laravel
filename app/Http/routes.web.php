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

Route::get('admin',
    ['uses' => 'AdminsController@getAdminLogin', 'as' => 'admin.getLogin']
);
Route::post('admin',
    ['uses' => 'AdminsController@postAdminLogin', 'as' => 'admin.postLogin']
);


Route::group(['prefix' => 'admin', 'middleware' => ['auth:admins']], function () {

    Route::get('home', ['uses' => 'AdminsController@getHome', 'as' => 'admin.getHome']);
    Route::get('logout', ['uses' => 'AdminsController@getLogout', 'as' => 'admin.getLogout']);

    //For POST action routes like message sending etc
    Route::group([], function () {
        Route::post('messages', [
            'uses' => 'AdminsController@postNewAdminMessage', 'as' => 'admin.postNewChatMessage'
        ]);

    });


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
        Route::get('history', [
            'uses' => 'AdminsController@getHistoryNotifications', 'as' => 'admin.getHistoryNotifications'
        ]);

        Route::get('add', [
            'uses' => 'AdminsController@getCreateNotification', 'as' => 'admin.getAddNotification'
        ]);
        Route::post('add', [
            'uses' => 'NotificationsController@postCreateNotification', 'as' => 'admin.postAddNotification'
        ]);
        Route::post('confirmAdd', [
            'uses' => 'NotificationsController@postConfirmSendNotification', 'as' => 'admin.postConfirmAddNotification'
        ]);

    });
});

//all public web routes here, temporarily


