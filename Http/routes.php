<?php

Route::group(['middleware' => ['web']], function () {

    Route::group(['namespace' => 'NineCells\Wiki\Http\Controllers'], function() {

        Route::group(['prefix' => 'wiki'], function() {

            Route::get('/{page_key?}', 'WikiController@GET_page');
            Route::get('/', 'WikiController@GET_page_form');
        });
    });
});
