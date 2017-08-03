<?php
/*
|--------------------------------------------------------------------------
| WEB API Routes
|--------------------------------------------------------------------------
|
| Here are all the routes for the online API
|
*/


Route::group(['middleware' => ['api'], 'prefix' => 'api'], function () {

    Route::post('register', 'APIController@register');

    Route::post('login', 'APIController@login');

    Route::group(['middleware' => 'jwt-auth'], function () {
        //all protected api routes here i.e with token

        Route::group(['prefix' => 'basics'], function () {
            Route::post('update_fcm_token','APIController@updateFCMToken');
            Route::post('user_details', 'BasicsController@get_user_details');
            Route::post('get_profile', 'BasicsController@get_profile');
        });

        Route::group(['prefix' => 'newsFeeds'], function () {
            Route::post('get', 'NewsFeedController@get_news_feeds');
            Route::post('post', 'NewsFeedController@post_news_feed');
        });


    });

    //all public api routes here i.e without token
    Route::group(['prefix' => 'questionPapers'], function () {
        Route::post('get', 'DocumentsController@getQP');
    });

    Route::group(['prefix' => 'resumes'], function () {
        Route::post('get', 'DocumentsController@getResume');
    });
});
