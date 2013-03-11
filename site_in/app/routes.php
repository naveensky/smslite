<?php

Route::get('/', 'HomeController@getHome');
Route::get('/about', 'HomeController@getAbout');
Route::get('/pricing', 'HomeController@getPricing');
Route::get('/buy', 'HomeController@getBuy');
Route::get('/contact', 'HomeController@getContact');