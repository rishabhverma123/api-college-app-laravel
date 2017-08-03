<?php

Route::group(['prefix' => 'test',], function () {
    Route::get('1', [
        'uses' => 'TestController@get1'
    ]);
    Route::post('2', [
        'uses' => 'TestController@get2'
    ]);
    Route::get('3', [
        'uses' => 'TestController@get3'
    ]);
});

?>