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

	    Route::get('/account/first_step',[
	        'uses' => 'UserController@steps', 
	        'as' => 'users.first_step'
	    ]);

	    Route::post('/account/postStep/{step}',[
	        'uses' => 'UserController@postSteps', 
	        'as' => 'users.postStep'
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
		    ])->middleware('candidate');

		    Route::post('/user/save/{user?}', [
		        'uses' => 'UserController@save', 
		        'as' => 'user.save'
		    ]);

		    Route::get('/payment-option', [
			        'uses' => 'PaymentController@index', 
			        'as' => 'payment'
			]);

		    Route::get('/payment-card', [
			        'uses' => 'PaymentController@card_method', 
			        'as' => 'payment.card'
			]);

		    Route::group(["middleware" => ["inscription"]
			],function(){
				//charge academic
			    Route::get('/charge', [
			        'uses' => 'ChargeController@index', 
			        'as' => 'charge'
			    ]);

			    Route::post('/charge/save/{user?}', [
			        'uses' => 'ChargeController@save', 
			        'as' => 'charge.save'
			    ]);
		    });
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