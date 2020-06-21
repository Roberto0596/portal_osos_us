<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix'=> 'alumn', 'namespace'=>'Alumn'], function()
{
  	Route::name('alumn.')->group(function()
  	{
  		Route::get('/sign-in',[
	        'uses' => 'AuthController@login', 
	        'as' => 'login'
	    ]);

	    Route::post('/sign-in',[
	        'uses' => 'AuthController@postLogin', 
		]);

		Route::get('/form',[
			'uses' => 'FormController@index', 
			'as' => 'form'
		]);
		 

	    Route::get('/sign-out', [
	        'uses' => 'AuthController@logout', 
	        'as' => 'logout'
	    ]);

	    Route::post('/users/registerAlumn/{user?}',[
	        'uses' => 'UserController@registerAlumn', 
	        'as' => 'users.registerAlumn'
	    ]);

  		Route::group(['middleware' => ['alumn.user']
		], function()
		{
			Route::get('/', [
		        'uses' => 'HomeController@index', 
		        'as' => 'home'
		    ]);

		    Route::get('/user', [
		        'uses' => 'UserController@index', 
		        'as' => 'user'
		    ]);

		});
  	});
});

Route::group(['prefix'=> 'finance', 'namespace'=>'FinancePanel'], function()
{
  	Route::name('finance.')->group(function()
  	{
  		Route::get('/sign-in',[
	        'uses' => 'AuthController@login', 
	        'as' => 'login'
	    ]);

	    Route::post('/sign-in',[
	        'uses' => 'AuthController@postLogin', 
	    ]);

	    Route::get('/sign-out', [
	        'uses' => 'AuthController@logout', 
	        'as' => 'logout'
	    ]);

  		Route::group(['middleware' => ['finance.user']
		], function()
		{
			Route::get('/', [
		        'uses' => 'HomeController@index', 
		        'as' => 'home'
		    ]);

		    Route::get('/user', [
		        'uses' => 'UserController@index', 
		        'as' => 'user'
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