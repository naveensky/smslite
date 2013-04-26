<?php

Route::get('/', 'HomeController@getHome');
Route::get('/features', 'HomeController@getAbout');
Route::get('/pricing', 'HomeController@getBuy');
Route::get('/plan/{num}', 'HomeController@getPlan');
Route::get('/contact', 'HomeController@getContact');
Route::get('/terms', 'HomeController@getTerms');
Route::get('/privacy', 'HomeController@getPrivacy');
Route::get('/thanks', 'HomeController@getThanks');


Route::get('/10-things-youll-love-about-msngr', 'BlogController@get10ThingsYouWillLoveMsngr');