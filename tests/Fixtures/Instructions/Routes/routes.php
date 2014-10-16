<?php

Route::group(['prefix' => 'alt', 'before' => 'before-filter', 'after' => 'after-filter'], function(){
    Route::get('/moloz', ['as' => 'moloz',  'uses'  => 'AuthController@test']);
});

