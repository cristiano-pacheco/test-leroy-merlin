<?php

Route::get('/products', 'ProductsController@index');
Route::get('/products/{id}', 'ProductsController@show');
Route::post('/products', 'ProductsController@store');
Route::put('/products/{id}', 'ProductsController@update');
Route::delete('/products/{id}', 'ProductsController@delete');

Route::get('processing_status/{id}', 'FilesController@show')->name('processing.status');
