<?php


Route::get('tests/find/{id}', 'TestController@find');

Route::post('tests/make', ['as' => 'tests.make', 'uses' => 'TestController@make']);

Route::post('tests/delete', ['as' => 'tests.delete', 'uses' => 'TestController@delete']);
