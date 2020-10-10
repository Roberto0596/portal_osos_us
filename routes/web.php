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

		//te lleva  a a la vista para enviar la peticion de restaurar pass
		Route::get('/restore-pass',[
	        'uses' => 'AuthController@requestRestorePass', 
	        'as' => 'RequestRestorePass'
		]);		

		//envia la peticion de restaurar pass
		Route::post('/restore-pass',[
	        'uses' => 'AuthController@sendRequest', 
	        'as' => 'sendRequest'
	    ]);
	 
	    Route::get('/sign-out', [
	        'uses' => 'AuthController@logout', 
	        'as' => 'logout'
	    ]);

	    Route::post('/users/registerAlumn',[
	        'uses' => 'AccountController@registerAlumn', 
	        'as' => 'users.registerAlumn'
	    ]);

	    Route::get('/account/first_step',[
	        'uses' => 'AccountController@index', 
	        'as' => 'users.first_step'
	    ]);

	    Route::post('/account/postStep/{step}',[
	        'uses' => 'AccountController@save', 
	        'as' => 'users.postStep'
	    ]);

  		Route::group(['middleware' => ['alumn.user']
		], function()
		{
			Route::post('/notify/show',[
					'uses'=>'UserController@notify', 
					'as' => 'notify.show'
			]);

			Route::get('/notify/{route?}/{id?}',[
					'uses'=>'UserController@seeNotify', 
					'as' => 'notify'
			]);

			Route::group(['middleware'=>['candidate']
			], function()
			{
				Route::get('/documents',[
					'uses'=>'PdfController@index', 
					'as' => 'documents'
				]);

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

				Route::post('/tab/see',[
			        'uses' => 'PdfController@tabCache', 
			        'as' => 'tab.see'
			    ]);

				Route::get('/user', [
			        'uses' => 'UserController@index', 
			        'as' => 'user'
			    ]);

			    Route::post('/user/save/{user?}', [
			        'uses' => 'UserController@save', 
			        'as' => 'user.save'
			    ]);

			    Route::get('/debits', [
			        'uses' => 'DebitController@index', 
			        'as' => 'debit'
			    ]);

			    Route::put('/debit/show', [
			        'uses' => 'DebitController@show', 
			        'as' => 'debit.show'
			    ]);
			});

			Route::get('/', [
		        'uses' => 'HomeController@index', 
		        'as' => 'home'
		    ]);

		    Route::post('/home/save-problem', [
		        'uses' => 'HomeController@saveProblem', 
		        'as' => 'home.problem'
		    ]);

		    Route::group(["middleware"=>["inscriptionOpen"]
			],function(){

				Route::group(["middleware" => ["inscription"]
				],function(){

					Route::get('/re-inscripcion',[
						'uses' => 'FormController@indexForm', 
						'as' => 'form.inscription'
					])->middleware('notnoob');

					Route::get('/inscripcion',[
						'uses' => 'FormController@indexInscription', 
						'as' => 'form.reinscription'
					])->middleware('noob');
			
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

					Route::post('/pay-spei', [
					        'uses' => 'PaymentController@pay_spei', 
					        'as' => 'pay.spei'
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

					Route::post('/pay-rollback/{orderId?}', [
				        'uses' => 'PaymentController@rollBack', 
				        'as' => 'pay.rollback'
				    ]);
				});
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

			Route::post('/save/document/inscription', [
				        'uses' => 'PdfController@saveDocument', 
				        'as' => 'save.document.inscription'
			]);

		    Route::get('/pay-cash-oxxo', [
				        'uses' => 'PaymentController@pay_cash_oxxo', 
				        'as' => 'pay.oxxo'
			]);

			Route::get('/pay-cash-spei', [
				        'uses' => 'PaymentController@pay_cash_spei', 
				        'as' => 'pay.spei.view'
			]);

			Route::post('/save/document', [
				        'uses' => 'PaymentController@pay_cash_spei', 
				        'as' => 'pay.spei.view'
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

			Route::get('/debit/delete/{id}', [
		        'uses' => 'DebitController@delete', 
		        'as' => 'debit.delete'
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
			Route::post('/debit/update', [
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

			Route::get('/generateGroups', [
		        'uses' => 'PendingsController@generateGroups', 
		        'as' => 'generate'
		    ]);

			Route::get('/load-data',[
				'uses' => 'PendingsController@loadData', 
				'as' => 'load'
			]);

			Route::get('/print/pdf',[
				'uses' => 'PendingsController@print', 
				'as' => 'pdf'
			]);

			Route::get('/generate-pdf',[
				'uses' => 'PendingsController@generatePdf', 
				'as' => 'pdfGenerate'
			]);	

			Route::get('/delete-groups',[
				'uses' => 'PendingsController@deleteGroups', 
				'as' => 'deleteGroups'
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
});

Route::group(['prefix'=> 'admin', 'namespace'=>'AdminPanel'], function()
{
  	Route::name('admin.')->group(function()
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

  		Route::group(['middleware' => ['admin.user']
		], function()
		{
			Route::get('/', [
		        'uses' => 'HomeController@index', 
		        'as' => 'home'
			]);

			//problemas
			Route::get('/problems', [
		        'uses' => 'ProblemController@index', 
		        'as' => 'problem'
			]);

			Route::put('/problem/show', [
		        'uses' => 'ProblemController@show', 
		        'as' => 'problem.show'
			]);

		    Route::post('/problem/see', [
		        'uses' => 'ProblemController@seeProblem', 
		        'as' => 'problem.see'
		    ]);

		    Route::get('/problem/delete/{id?}', [
		        'uses' => 'ProblemController@delete', 
		        'as' => 'problem.see'
		    ]);

		    Route::get('/problem/fixed/{id?}', [
		        'uses' => 'ProblemController@fixed', 
		        'as' => 'problem.see'
		    ]);

			//user
			Route::post('/user/save/{user?}', [
		        'uses' => 'UserController@save', 
		        'as' => 'user.save'
			]);
			
			Route::get('/user', [
		        'uses' => 'UserController@index', 
		        'as' => 'user'
			]);

			//users
			Route::get('/users', [
		        'uses' => 'UsersController@index', 
		        'as' => 'users'
			]);

			Route::put('/users/show', [
		        'uses' => 'UsersController@show', 
		        'as' => 'users.show'
			]);

			Route::get('/users/create/user', [
		        'uses' => 'UsersController@create', 
		        'as' => 'users.create'
			]);

			Route::get('/users/edit/user/{id?}', [
		        'uses' => 'UsersController@edit', 
		        'as' => 'users.edit'
			]);

			Route::post('/users/create/save/{user?}', [
		        'uses' => 'UsersController@save', 
		        'as' => 'users.save'
			]);

			Route::get('/users/delete/{id?}', [
		        'uses' => 'UsersController@delete', 
		        'as' => 'users.delete'
			]);

			//alumnos
			Route::get('/alumns', [
		        'uses' => 'AlumnController@index', 
		        'as' => 'alumns'
			]);

			Route::put('/alumns/show', [
		        'uses' => 'AlumnController@show', 
		        'as' => 'alumns.show'
			]);	

			Route::get('/alumns/delete/{id}', [
		        'uses' => 'AlumnController@delete', 
		        'as' => 'alumns.delete'
			]);	

			Route::post('/alumns/update', [
		        'uses' => 'AlumnController@update', 
		        'as' => 'alumns.update'
			]);	

			Route::post('/alumns/alumnDada', [
		        'uses' => 'AlumnController@seeAlumnData', 
		        'as' => 'alumns.alumnDada'
			]);	

			Route::post('/alumns/generateEnrollment', [
		        'uses' => 'AlumnController@generateEnrollment', 
		        'as' => 'alumns.enrollment'
			]);

			//reiniciar constraseñas

			//te lleva al tabla de solicitudes apra restaurar pass

			Route::get('/reset-passwords', [
		        'uses' => 'ResetPassController@index', 
		        'as' => 'reset-pass'
			]);			

			//carga los datos de esta tabla
			Route::put('/reset-passwords/show', [
		        'uses' => 'ResetPassController@show', 
		        'as' => 'reset-pass.show'
			]);

			//envia el id del usuario que desea cambiar el pass	
			Route::post('/reset-passwords/send-pass', [
		        'uses' => 'ResetPassController@sendPass', 
		        'as' => 'reset.pass.save'
			]);	

			Route::post('/period/save/{period?}', [
		        'uses' => 'HomeController@savePeriod', 
		        'as' => 'period.save'
			]);	

			//reportes
			Route::get('/report', [
		        'uses' => 'ReportController@index', 
		        'as' => 'report'
			]);


			//Document

			//te lleva a la tabla de documentos
			Route::get('/document', [
		        'uses' => 'DocumentController@index', 
		        'as' => 'document'
			]);		

			//carga datos de tabla documentos
			Route::put('/documents/show', [
		        'uses' => 'DocumentController@show', 
		        'as' => 'show'
			]);

			//Sirve para cambiar el estado de un documento
			Route::post('/document/update-status',[
				'uses'=>'DocumentController@updateStatus', 
				'as' => 'updatestatus'
			]);		

			Route::get('/settings', [
		        'uses' => 'SettingsController@index', 
		        'as' => 'settings'
			]);	

			Route::post('/settings/save/{instance?}', [
		        'uses' => 'SettingsController@save', 
		        'as' => 'save.setting'
			]);	

		});
  	});
});
