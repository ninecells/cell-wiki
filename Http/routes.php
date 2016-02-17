<?php

Route::group(['middleware' => ['web']], function () {

    Route::group(['namespace' => 'NineCells\Wiki\Http\Controllers'], function() {

        Route::group(['prefix' => 'wiki'], function() {

            Route::get('/{key?}', 'WikiController@GET_page');
            Route::get('/{key?}/edit', 'WikiController@GET_page_form');
            Route::put('/update', 'WikiController@PUT_page_form');
        });
    });
});
