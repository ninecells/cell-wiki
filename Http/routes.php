<?php

Route::group(['middleware' => ['web']], function () {

    Route::group(['namespace' => 'NineCells\Wiki\Http\Controllers'], function() {

        Route::group(['prefix' => 'wiki'], function() {

            Route::get('/{key?}', 'WikiController@GET_page');
            Route::get('/{key}/edit', 'WikiController@GET_page_form');
            Route::get('/{key}/history', 'WikiController@GET_page_history');
            Route::get('/{key}/compare/{left}/{right}', 'WikiController@GET_page_compare');
            Route::get('/{key}/{rev}', 'WikiController@GET_rev_page');
            Route::put('/update', 'WikiController@PUT_page_form');
        });
    });
});
