
@extends('Alumn.main')
@section('content-alumn')


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <script src="{{ asset('js/form/index.js') }}"></script>
    <link href="{{ asset('css/form.css') }}" rel="stylesheet">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3>Brindanos tu información</h3>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="card">
        <div class="card-body">
            <div class="container">
                <div class="stepwizard">
                    <div class="stepwizard-row setup-panel">
                        <div class="stepwizard-step">
                            <a href="#step-1" type="button" class="btn-step-circle" ">1</a>
                            <p>Datos Personales</p>
                        </div>
                        <div class="stepwizard-step">
                            <a href="#step-2" type="button" class="btn-step-circle" disabled="disabled">2</a>
                            <p>Datos Fisicos</p>
                        </div>
                        <div class="stepwizard-step">
                            <a href="#step-3" type="button" class="btn-step-circle" disabled="disabled">3</a>
                            <p>Dirección</p>
                        </div>
                        <div class="stepwizard-step">
                            <a href="#step-4" type="button" class="btn-step-circle" disabled="disabled">4</a>
                            <p>Deporte</p>
                        </div>
                        <div class="stepwizard-step">
                            <a href="#step-5" type="button" class="btn-step-circle" disabled="disabled">5</a>
                            <p>Imagenes</p>
                        </div>
                        <div class="stepwizard-step">
                            <a href="#step-6" type="button" class="btn-step-circle" disabled="disabled">6</a>
                            <p>Formulario</p>
                        </div>
                        <div class="stepwizard-step">
                            <a href="#step-7" type="button" class="btn-step-circle" disabled="disabled">7</a>
                            <p>Completado</p>
                        </div>
                    </div>
                </div>
                <form role="form">
                    <div class="row" id="step-1">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Cedula</label>
                                <input  maxlength="100" type="text" required="required" class="form-control" placeholder="Ingrese una cedula"  />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Cedula</label>
                                <input  maxlength="100" type="text" required="required" class="form-control" placeholder="Ingrese una cedula"  />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Cedula</label>
                                <input  maxlength="100" type="text" required="required" class="form-control" placeholder="Ingrese una cedula"  />
                            </div>
                       </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Cedula</label>
                                <input  maxlength="100" type="text" required="required" class="form-control" placeholder="Ingrese una cedula"  />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Cedula</label>
                                <input  maxlength="100" type="text" required="required" class="form-control" placeholder="Ingrese una cedula"  />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Cedula</label>
                                <input  maxlength="100" type="text" required="required" class="form-control" placeholder="Ingrese una cedula"  />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Cedula</label>
                                <input  maxlength="100" type="text" required="required" class="form-control" placeholder="Ingrese una cedula"  />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Cedula</label>
                                <input  maxlength="100" type="text" required="required" class="form-control" placeholder="Ingrese una cedula"  />
                            </div>
                            <div class="form-group">
                                <label for="sexo" data-alias="Sexo" class="control-label">Sexo</label>
                                <select id="sexo" name="sexo" class="form-control " required="true">
                                    <option value="M">Mujer</option>
                                    <option value="H">Hombre</option>
                                </select>
                              </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Cedula</label>
                                <input  maxlength="100" type="text" required="required" class="form-control" placeholder="Ingrese una cedula"  />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Cedula</label>
                                <input  maxlength="100" type="text" required="required" class="form-control" placeholder="Ingrese una cedula"  />
                            </div>
                            <div class="form-group">
                                <label for="sexo" data-alias="Sexo" class="control-label">Sexo</label>
                                <select id="sexo" name="sexo" class="form-control " required="true">
                                    <option value="M">Mujer</option>
                                    <option value="H">Hombre</option>
                                </select>
                            </div>
                    </div>
                    
                </form>
                   





          
        </div>
        <!-- /.card-body -->
         <!-- <div class="card-footer">
          Footer
        </div> -->
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
   
  </div>
  <!-- /.content-wrapper -->

@stop
