<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Alumn'],function()
{
	Route::get('/sign-in',[
        'uses' => 'AuthController@login', 
        'as' => 'login'
    ]);

	Route::group(['prefix' => 'alumn','middleware' => 'auth'], function()
	{
		Route::name('alumn.')->group(function()
		{
			Route::get('/', [
		        'uses' => 'HomeController@index', 
		        'as' => 'home'
		    ]);
		});
	});
});

Route::group(['namespace' => 'FinancePanel'],function()
{
	Route::group(['prefix' => 'finance'], function()
	{
		Route::name('finance.')->group(function()
		{
			Route::get('/', [
		        'uses' => 'HomeController@index', 
		        'as' => 'home'
		    ]);
		});
	});
});

Route::group(['namespace' => 'Website'],function()
{
	Route::get('/', [
        'uses' => 'WebsiteController@index', 
        'as' => 'home'
    ]);
});