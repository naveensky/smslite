<?php

Route::get('/', 'HomeController@getHome');
Route::get('/features', 'HomeController@getAbout');
Route::get('/pricing', 'HomeController@getPricing');
Route::get('/buy', 'HomeController@getBuy');
Route::get('/plan/{num}', 'HomeController@getPlan');
Route::get('/contact', 'HomeController@getContact');
Route::get('/terms', 'HomeController@getTerms');
Route::get('/privacy', 'HomeController@getPrivacy');