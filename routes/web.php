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

		Route::post('form/save', [
			'uses' => 'FormController@save',
			'as'   => 'form.save'
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

			
			Route::get('pdf','PdfController@getIndex');
			Route::post('pdf/generar/{tipo}/{accion}',['uses'=>'PdfController@getGenerar', 'as' => 'generar']);

			Route::get('/', [
		        'uses' => 'HomeController@index', 
		        'as' => 'home'
		    ]);

		    Route::post('/user/save/{user?}', [
		        'uses' => 'UserController@save', 
		        'as' => 'user.save'
		    ]);

		   	Route::group(["middleware" => ["inscription"]
			],function(){

				Route::get('/form',[
					'uses' => 'FormController@index', 
					'as' => 'form'
				]);
		    });

		    Route::group(["middleware"=> ["inscriptionFaseTwo"] 
			],function(){

			    //Pago de inscription
			    Route::get('/payment', [
			        'uses' => 'PaymentController@index', 
			        'as' => 'payment'
				]);

				Route::get('/payment/card', [
		       		'uses' => 'PaymentController@form_payment', 
		       		'as' => 'payment.card'
		    	]);

			    Route::post('/pay-card', [
				        'uses' => 'PaymentController@pay_card', 
				        'as' => 'pay.card'
				]);
			});

		    Route::group(["middleware"=>["inscriptionFaseThree"]
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

		  	Route::get('/user', [
		        'uses' => 'UserController@index', 
		        'as' => 'user'
		    ])->middleware('candidate');

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
			
			/*

		    Route::get('/user', [
		        'uses' => 'UserController@index', 
		        'as' => 'user'
			]);
			*/

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