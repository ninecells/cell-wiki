<?php

Route::group(['middleware' => ['web']], function () {

    Route::group(['namespace' => 'NineCells\Wiki\Http\Controllers'], function() {

        Route::group(['prefix' => 'wiki'], function() {

            Route::get('/test', 'WikiController@GET_page_form');
            Route::get('/{page_key?}', 'WikiController@GET_page');
        });
    });
});
