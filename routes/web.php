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

			Route::get('/documents',[
				'uses'=>'PdfController@index', 
				'as' => 'documents'
			])->middleware('candidate');

			Route::put('/documents/show',[
				'uses'=>'PdfController@showDocuments', 
				'as' => 'documents.show'
			]);

			Route::get('pdf/cedula/{document?}',[
				'uses'=>'PdfController@getGenerarCedula', 
				'as' => 'cedula'
			]);

			Route::get('pdf/generar/{document?}',[
				'uses'=>'PdfController@getGenerarConstancia', 
				'as' => 'constancia'
			]);

			Route::post('pdf/generar/{tipo}/{accion}/{pago}',[
				'uses'=>'PdfController@getGenerarFicha', 
				'as' => 'fichas'
			]);

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
		
				Route::post('form/save', [
					'uses' => 'FormController@save',
					'as'   => 'form.save'
				]);

				Route::post('form/save/inscription', [
					'uses' => 'FormController@saveInscription',
					'as'   => 'save.inscription'
				]);

				Route::post('form/getMunicipio', [
					'uses' => 'FormController@getMunicipios',
					'as'   => 'form.getMunicipio'
				]);
		    });

		    Route::group(["middleware"=> ["inscriptionFaseTwo"] 
			],function(){

			    //Pago de inscription
			    Route::get('/payment', [
			        'uses' => 'PaymentController@index', 
			        'as' => 'payment'
				]);
				
			    Route::post('/pay-card', [
				        'uses' => 'PaymentController@pay_card', 
				        'as' => 'pay.card'
				]);

				Route::post('/pay-cash', [
				        'uses' => 'PaymentController@pay_cash', 
				        'as' => 'pay.cash'
				]);

				Route::post('/pay-stei', [
				        'uses' => 'PaymentController@pay_stei', 
				        'as' => 'pay.stei'
				]);

				Route::post('/pay-upload', [
					'uses' => 'PaymentController@pay_upload', 
					'as' => 'pay.upload'
				]);
			});

			Route::group(["middleware"=>["inscriptionFaseThree"]
			],function()
			{
				Route::get('/payment/note', [
			        'uses' => 'PaymentController@note', 
			        'as' => 'payment.note'
				]);
			});

		    Route::group(["middleware"=>["inscriptionFaseFour"]
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

		    Route::get('/pay-cash-oxxo', [
				        'uses' => 'PaymentController@pay_cash_oxxo', 
				        'as' => 'pay.oxxo'
			]);

			Route::get('/pay-cash-stei', [
				        'uses' => 'PaymentController@pay_cash_stei', 
				        'as' => 'pay.stei.view'
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

			//te lleva a la vista de adeudos	
			Route::get('/debit', [
		        'uses' => 'DebitController@index', 
		        'as' => 'debit'
			]);

		
			// te lleva a la parte de usuarios
			Route::get('/user', [
		        'uses' => 'UserController@index', 
		        'as' => 'user'
			]);

			//sirve para mostrar los registros en la tabla
			Route::put('/debit/show', [
		        'uses' => 'DebitController@showDebit', 
		        'as' => 'user.show'
			]);
			
			//sirve para mostrar un registros en especifico
			Route::post('/debit/see', [
		        'uses' => 'DebitController@seeDebit', 
		        'as' => 'user.see'
			]);


			//sirve para actualizar el estado de un adeudo
			Route::put('/debit/update', [
		        'uses' => 'DebitController@update', 
		        'as' => 'debit.update'
			]);
			
			//sirve para guardar un nuevo adeudo
			Route::post('/debit/save', [
		        'uses' => 'DebitController@save', 
		        'as' => 'debit.save'
			]);

			//sirve para ver los detalles del pago
			Route::post('/debit/payment-details', [
		        'uses' => 'DebitController@showPayementDetails', 
		        'as' => 'user.showPayementDetails'
			]);

			

			
			

			
		});
  	});
});

Route::group(['prefix'=> 'computo', 'namespace'=>'ComputerCenterPanel'], function()
{
  	Route::name('computo.')->group(function()
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

  		Route::group(['middleware' => ['computer.user']
		], function()
		{
			Route::get('/', [
		        'uses' => 'HomeController@index', 
		        'as' => 'home'
			]);

			Route::post('/user/save/{user?}', [
		        'uses' => 'UserController@save', 
		        'as' => 'user.save'
			]);
			
			Route::get('/user', [
		        'uses' => 'UserController@index', 
		        'as' => 'user'
			]);

			Route::get('/debit', [
		        'uses' => 'DebitController@index', 
		        'as' => 'debit'
			]);

			Route::post('/debit/save', [
		        'uses' => 'DebitController@save', 
		        'as' => 'debit.save'
			]);

			Route::post('/debit/update', [
		        'uses' => 'DebitController@update', 
		        'as' => 'debit.update'
			]);
			
			Route::put('/debit/show', [
		        'uses' => 'DebitController@showDebit', 
		        'as' => 'user.show'
			]);

			Route::post('/debit/see', [
		        'uses' => 'DebitController@seeDebit', 
		        'as' => 'user.see'
			]);

		});
  	});
});

Route::group(['prefix'=> 'library', 'namespace'=>'LibraryPanel'], function()
{
  	Route::name('library.')->group(function()
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

  		Route::group(['middleware' => ['library.user']
		], function()
		{
			Route::get('/', [
		        'uses' => 'HomeController@index', 
		        'as' => 'home'
			]);

			Route::post('/user/save/{user?}', [
		        'uses' => 'UserController@save', 
		        'as' => 'user.save'
			]);
			
			Route::get('/user', [
		        'uses' => 'UserController@index', 
		        'as' => 'user'
			]);

			Route::get('/debit', [
		        'uses' => 'DebitController@index', 
		        'as' => 'debit'
			]);

			Route::post('/debit/save', [
		        'uses' => 'DebitController@save', 
		        'as' => 'debit.save'
			]);

			Route::post('/debit/update', [
		        'uses' => 'DebitController@update', 
		        'as' => 'debit.update'
			]);
			
			Route::put('/debit/show', [
		        'uses' => 'DebitController@showDebit', 
		        'as' => 'user.show'
			]);

			Route::post('/debit/see', [
		        'uses' => 'DebitController@seeDebit', 
		        'as' => 'user.see'
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

    Route::get('/getPassword', [
        'uses' => 'WebsiteController@getPassword', 
        'as' => 'home.password'
    ]);
});