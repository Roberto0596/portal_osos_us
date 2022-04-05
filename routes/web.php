<?php

use Illuminate\Support\Facades\Route;

$alumnDomain  =  env('ALUMN_DOMAIN');

Route::group(['domain' => $alumnDomain], function() {

	Route::group(['prefix'=> 'alumn', 'namespace'=>'Alumn'], function()
	{
	  	Route::name('alumn.')->group(function()
	  	{
			Route::group(["middleware"=>["maintenance"]],function(){

				Route::get('/sign-in',[
					'uses' => 'AuthController@login', 
					'as' => 'login'
				]);
			});	  		

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
			], function() {

				Route::get('/notify/show',[
						'uses'=>'UserController@notify', 
						'as' => 'notify.show'
				]);

				Route::get('/notify/{route?}/{id?}',[
						'uses'=>'UserController@seeNotify', 
						'as' => 'notify'
				]);

				Route::group(['middleware' => ['candidate']
				], function() {
					
					Route::get('/documents',[
						'uses'=>'PdfController@index', 
						'as' => 'documents'
					]);

					Route::post('/documents/show',[
						'uses'=>'PdfController@showDocuments', 
						'as' => 'documents.show'
					]);

					Route::get('/documents/redirectTo',[
						'uses'=>'PdfController@redirectToDocument', 
						'as' => 'documents.redirectTo'
					]);

					Route::get('pdf/cedula/{document?}',[
						'uses'=>'PdfController@getGenerarCedula', 
						'as' => 'cedula'
					]);

					Route::get('pdf/delete/document/{id?}',[
						'uses'=>'PdfController@deleteDocument', 
						'as' => 'delete.document'
					]);

					Route::post('pdf/getDocument/{documentType?}',[
						'uses'=>'PdfController@getOfficialDocument', 
						'as' => 'pdf.getDocument'
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

				    Route::post('/debit/save/card', [
				        'uses' => 'DebitController@payCard', 
				        'as' => 'debit.save.card'
				    ]);

				    Route::post('/debit/save/spei', [
				        'uses' => 'DebitController@paySpei', 
				        'as' => 'debit.save.spei'
				    ]);

				    Route::post('/debit/save/oxxo', [
				        'uses' => 'DebitController@payOxxo', 
				        'as' => 'debit.save.oxxo'
				    ]);

				    Route::get('/debit/note/{id_order?}', [
				        'uses' => 'DebitController@note', 
				        'as' => 'debit.note'
					]);

					Route::post('debit/pay-upload', [
						'uses' => 'DebitController@pay_upload', 
						'as' => 'debit.pay.upload'
					]);

					/*
					|-------------------------------------------------------------------
					| Tickets
					|-------------------------------------------------------------------
					*/

					Route::get('/tickets',[
						'uses'=>'TicketController@index', 
						'as' => 'tickets'
					]);

					Route::post('/tickets/show',[
						'uses'=>'TicketController@show', 
						'as' => 'tickets.show'
					]);

					/*
					|-------------------------------------------------------------------
					| Academic Charge
					|-------------------------------------------------------------------
					*/

					Route::get('/academic-charge',[
						'uses'=>'AcademicChargeController@index', 
						'as' => 'academicCharge'
					]);

					Route::post('/academic-charge/show/{period?}',[
						'uses'=>'AcademicChargeController@show', 
						'as' => 'academicCharge.show'
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

				    Route::post('/charge/save', [
				        'uses' => 'ChargeController@save', 
				        'as' => 'charge.save'
				    ]);

				    Route::post('/charge/finally', [
				        'uses' => 'ChargeController@finally', 
				        'as' => 'charge.finally'
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

				Route::post('/user/save/{user?}', [
			        'uses' => 'UserController@save', 
			        'as' => 'user.save'
				]);
				
				// te lleva a la parte de usuarios
				Route::get('/user', [
			        'uses' => 'UserController@index', 
			        'as' => 'user'
				]);

				//sirve para mostrar los registros en la tabla
				Route::post('/debit/datatable', [
			        'uses' => 'DebitController@datatable', 
			        'as' => 'debit.show'
				]);
				
				//sirve para mostrar un registros en especifico
				Route::post('/debit/see', [
			        'uses' => 'DebitController@seeDebit', 
			        'as' => 'debit.see'
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

				Route::post('/debit/validate', [
			        'uses' => 'DebitController@validateDebit', 
			        'as' => 'debit.validate'
				]);

				Route::get('/debit/delete', [
			        'uses' => 'DebitController@delete', 
			        'as' => 'debit.delete'
				]);

				Route::post('/debit/upload', [
			        'uses' => 'DebitController@upload', 
			        'as' => 'debit.upload'
				]);

				//sirve para ver los detalles del pago
				Route::post('/debit/payment-details', [
			        'uses' => 'DebitController@showPayementDetails', 
			        'as' => 'user.showPayementDetails'
				]);

				Route::get('/debit/search-alumn', [
			        'uses' => 'DebitController@searchAlumn', 
			        'as' => 'debit.search.alumn'
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

				//ticket
				Route::get('/tickets/get',[
					'uses'=>'DebitController@getTicket', 
					'as' => 'get.ticket'
				]);

				Route::post('/tickets/report',[
					'uses'=>'DebitController@ticketReport', 
					'as' => 'ticket.report'
				]);	

				Route::post('/change-serie',[
					'uses'=>'SettingsController@changeSerie', 
					'as' => 'settings.changeSerie'
				]);

				Route::post('/generate-excel',[
					'uses'=>'DebitController@excelGenerate', 
					'as' => 'excel.generate'
				]);	

				Route::get('/notify/show',[
					'uses'=>'UserController@notify', 
					'as' => 'notify.show'
				]);	

				Route::get('/notify/{route?}/{id?}',[
						'uses'=>'UserController@seeNotify', 
						'as' => 'notify'
				]);	

				Route::get('/check-debit',[
						'uses'=>'DebitController@check', 
						'as' => 'notify'
				]);			
			});
	  	});
	});

	Route::group(['namespace' => 'Website'], function()
	{
		Route::group(["middleware"=>["maintenance"]],function(){

			Route::get('/', [
				'uses' => 'WebsiteController@index', 
				'as' => 'home'
			]);	

		});

	    Route::get('/restore-pass/{token?}', [
	        'uses' => 'WebsiteController@viewRestore', 
	        'as' => 'restore'
	    ]);	

	    Route::post('/restore/{instance?}', [
	        'uses' => 'WebsiteController@restorePassword', 
	        'as' => 'restore.password'
	    ]);	

		Route::get('/maintenance',[
			'uses' => 'WebsiteController@inMaintenance', 
			'as' => 'maintenance'
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

				Route::post('/problem/show', [
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

				Route::post('/users/show', [
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

				Route::post('/alumns/show', [
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
				Route::post('/reset-passwords/show', [
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
				Route::post('/documents/show', [
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


				//================== rutas CRUD debit_type ===================//

				//muestra la tabla de debit type
				Route::get('/debit-type', [
			        'uses' => 'DebitTypeController@index', 
			        'as' => 'debit-type'
				]);	

				//carga datos de tabla debit type
				Route::post('/debit-type/show', [
			        'uses' => 'DebitTypeController@show', 
			        'as' => 'show'
				]);

				//elimina un registro
				Route::get('/debit-type/delete/{id?}', [
			        'uses' => 'DebitTypeController@delete', 
			        'as' => 'delete'
				]);
				
				//sirve para guardar un nuevo registro
				Route::post('/debit-type/create', [
			        'uses' => 'DebitTypeController@create', 
			        'as' => 'debittype.create'
				]);

				//sirve para editar un nuevo registro
				Route::post('/debit-type/update', [
			        'uses' => 'DebitTypeController@update', 
			        'as' => 'debittype.update'
				]);

				//carga la info de un registro para que se solicita a traves de ajax
				Route::post('/debit-type/seeDebitType', [
			        'uses' => 'DebitTypeController@see', 
			        'as' => 'debittype.see'
				]);

				//================== rutas CRUD document_type ===================//

				//muestra la tabla de document type
				Route::get('/document-type', [
			        'uses' => 'DocumentTypeController@index', 
			        'as' => 'document-type'
				]);	

				//carga datos de tabla debit type
				Route::post('/document-type/show', [
			        'uses' => 'DocumentTypeController@show', 
			        'as' => 'documentType.show'
				]);

				//sirve para guardar un nuevo registro
				Route::post('/document-type/create', [
			        'uses' => 'DocumentTypeController@create', 
			        'as' => 'documentType.create'
				]);

				//elimina un registro
				Route::get('/document-type/delete/{id?}', [
			        'uses' => 'DocumentTypeController@delete', 
			        'as' => 'documentType.delete'
				]);
				
			

				//sirve para editar un nuevo registro
				Route::post('/document-type/update', [
			        'uses' => 'DocumentTypeController@update', 
			        'as' => 'documentType.update'
				]);

				//carga la info de un registro para que se solicita a traves de ajax
				Route::post('/document-type/seeDoc', [
			        'uses' => 'DocumentTypeController@see', 
			        'as' => 'documentType.see'
				]);

				//=============================================================//

				Route::get('/failed/registers', [
			        'uses' => 'FailedRegisterController@index', 
			        'as' => 'failed.index'
				]);

				Route::post('/failed/registers/show', [
			        'uses' => 'FailedRegisterController@show', 
			        'as' => 'failed.show'
				]);

				Route::post('/failed/registers/encGrupo', [
			        'uses' => 'FailedRegisterController@encGrupo', 
			        'as' => 'failed.encGrupo'
				]);

				Route::post('/failed/registers/save', [
			        'uses' => 'FailedRegisterController@save', 
			        'as' => 'failed.save'
				]);

				Route::get('/document/request', [
			        'uses' => 'DocumentRequestController@index', 
			        'as' => 'document.request'
				]);

				Route::post('/document/request/upload', [
			        'uses' => 'DocumentRequestController@upload', 
			        'as' => 'document.request.upload'
				]);

				Route::get('/document/request/fix/{id?}', [
			        'uses' => 'DocumentRequestController@fix', 
			        'as' => 'document.request.fix'
				]);

				//================== Promedios altos ===================//

				//muestra la tabla de high_averages
				Route::get('/high-averages', [
			        'uses' => 'HighAveragesController@index', 
			        'as' => 'high-averages'
				]);	

				
				Route::post('/high-averages/load/{period?}', [
			        'uses' => 'HighAveragesController@loadData', 
			        'as' => 'high-averages.load'
				]);	

				Route::post('/high-averages/search', [
			        'uses' => 'HighAveragesController@search', 
			        'as' => 'high-averages.search'
				]);	

				Route::post('/high-averages/add', [
			        'uses' => 'HighAveragesController@addAlumn', 
			        'as' => 'high-averages.add'
				]);	

				
				Route::get('/high-averages/delete/{id?}', [
			        'uses' => 'HighAveragesController@delete', 
			        'as' => 'high-averages.delete'
				]);

				Route::get('/notify/show',[
					'uses'=>'UserController@notify', 
					'as' => 'notify.show'
				]);	

				Route::get('/notify/{route?}/{id?}',[
						'uses'=>'UserController@seeNotify', 
						'as' => 'notify'
				]);	

				Route::group(["prefix" => "ticket"], function() {
					Route::get("/", [
						"uses" => "TicketsController@index",
						"as" => "ticket.index"
					]);

					Route::post("/datatable", [
						"uses" => "TicketsController@datatable",
						"as" => "ticket.datatable"
					]);
				});

				Route::get('/search-alumn', [
			        'uses' => '\App\Http\Controllers\FinancePanel\DebitController@searchAlumn', 
			        'as' => 'debit.search.alumn'
				]);
			});
	  	});
	});

	Route::group(['prefix' => 'departaments', 'namespace' => 'DepartamentPanel'], function()
	{
	  	Route::name('departament.')->group(function()
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

	  		Route::group(['middleware' => ['departament.user']
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
				
				Route::post('/debit/show', [
			        'uses' => 'DebitController@showDebit', 
			        'as' => 'debit.show'
				]);

				Route::post('/debit/see', [
			        'uses' => 'DebitController@seeDebit', 
			        'as' => 'debit.see'
				]);

				Route::get('/debit/delete/{id}', [
			        'uses' => 'DebitController@delete', 
			        'as' => 'debit.see'
				]);

				Route::group(["prefix" => "logs"], function() {

					Route::name('logs.')->group(function() {

						Route::group(["prefix" => "classrooms"], function() {

							Route::get('/', [
						        'uses' => 'ClassRoomController@index', 
						        'as' => 'classrooms.index'
							]);

							Route::get('/create', [
						        'uses' => 'ClassRoomController@create', 
						        'as' => 'classrooms.create'
							]);

							Route::get('/edit/{id?}', [
						        'uses' => 'ClassRoomController@edit', 
						        'as' => 'classrooms.edit'
							]);

							Route::get('/delete/{id?}', [
						        'uses' => 'ClassRoomController@delete', 
						        'as' => 'classrooms.delete'
							]);

							Route::post('/save/{instance?}', [
						        'uses' => 'ClassRoomController@save', 
						        'as' => 'classrooms.save'
							]);

						});

						Route::group(["prefix" => "reports"], function() {
							Route::get('/', [
						        'uses' => 'ReportController@index', 
						        'as' => 'report.index'
							]);

							Route::post('/datatable', [
						        'uses' => 'ReportController@datatable', 
						        'as' => 'report.datatable'
							]);
						});

						Route::group(["prefix" => "equipments"], function() {

							Route::get('/', [
						        'uses' => 'EquipmentController@index', 
						        'as' => 'equipment.index'
							]);

							Route::get('/delete/{id?}', [
						        'uses' => 'EquipmentController@delete', 
						        'as' => 'equipment.delete'
							]);

							Route::post('/save', [
						        'uses' => 'EquipmentController@save', 
						        'as' => 'equipment.save'
							]);

							Route::post('/fill', [
						        'uses' => 'EquipmentController@fillOrQuit', 
						        'as' => 'equipment.fill'
							]);

							Route::get('/getEquipment', [
						        'uses' => 'EquipmentController@equipment', 
						        'as' => 'equipment.getEquipment'
							]);

							Route::get('/getAlumnInfo', [
						        'uses' => 'EquipmentController@alumnData', 
						        'as' => 'equipment.alumnData'
							]);

						});

					});

				});

			});
	  	});
	});

});

$logDomain  =  env('LOG_DOMAIN');

Route::group(['domain' => $logDomain, 'namespace' => 'Logs'], function() {

	Route::name('logs.')->group(function() {

		Route::get("/sign-in", [
			"uses" => "AuthController@login",
			"as" => "auth"
		]);

		Route::post("/sign-in", [
			"uses" => "AuthController@postLogin",
		]);

		Route::group(["middleware" => "logs.user"], function() {

			Route::get("/", [
				"uses" => "LoginController@index",
				"as" => "login"
			]);

			Route::post("/close-booking", [
				"uses" => "LoginController@closeBooking",
				"as" => "close.booking"
			]);

			Route::group(["prefix" => "classrooms"], function() {

				Route::name('classroom.')->group(function() {

					Route::post("/", [
						"uses" => "ClassRoomController@index",
						"as" => "index"
					]);

					Route::post("/save", [
						"uses" => "ClassRoomController@save",
						"as" => "save"
					]);

					Route::get("/get-equipment/{id?}", [
						"uses" => "ClassRoomController@getEquipment",
						"as" => "get.equipment"
					]);

				});

			});		

			Route::group(["prefix" => "quick_booking"], function() {

				Route::name('quick.')->group(function() {

					Route::get("/get", [
						"uses" => "LoginController@getQuickBooking",
						"as" => "get"
					]);

					Route::post("/save", [
						"uses" => "LoginController@saveQuickBooking",
						"as" => "save"
					]);

				});

			});
		});

  	});
});


