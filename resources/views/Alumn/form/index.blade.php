
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
                <div class="col-md-12  col-xs-2  col-lg-12 col-sm-2">
                <div class="stepwizard">
                    <div class="stepwizard-row setup-panel">
                        <div class="stepwizard-step">
                            <a href="#step-1" type="button" class="btn btn-success btn-circle btn-sm">1</a>
                            <p>Datos Personales</p>
                        </div>
                        <div class="stepwizard-step">
                            <a href="#step-2" type="button" class="btn btn-success btn-circle btn-sm" disabled="disabled">2</a>
                            <p>Datos Fisicos</p>
                        </div>
                        <div class="stepwizard-step">
                            <a href="#step-3" type="button" class="btn btn-success btn-circle btn-sm" disabled="disabled">3</a>
                            <p>Dirección</p>
                        </div>
                        <div class="stepwizard-step">
                            <a href="#step-4" type="button" class="btn btn-success btn-circle btn-sm" disabled="disabled">4</a>
                            <p>Deporte</p>
                        </div>
                        <div class="stepwizard-step">
                            <a href="#step-5" type="button" class="btn btn-success btn-circle btn-sm" disabled="disabled">5</a>
                            <p>Imagenes</p>
                        </div>
                        <div class="stepwizard-step">
                            <a href="#step-6" type="button" class="btn btn-success btn-circle btn-sm" disabled="disabled">6</a>
                            <p>Formulario</p>
                        </div>
                        <div class="stepwizard-step">
                            <a href="#step-7" type="button" class="btn btn-success btn-circle btn-sm" disabled="disabled">7</a>
                            <p>Completado</p>
                        </div>
                    </div>
                </div>
                </div>

                <form role="form">                  
                    <div class="row" id="step-1">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Curp</label>
                                <input  maxlength="100" type="text" required="required" class="form-control" placeholder="Ingrese su curp"  />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Domicilio</label>
                                <input  maxlength="100" type="text" required="required" class="form-control" placeholder="Ingrese su domicilio"  />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Cedula</label>
                                <input  maxlength="100" type="text" required="required" class="form-control" placeholder="Ingrese una cedula"  />
                            </div>
                       </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="diaNacimiento" data-alias="diaNacimiento" class="control-label">Dia nacimiento</label>
                                <select id="diaNacimiento" name="diaNacimiento" class="form-control " required="true">
                                    <option value="01">01</option>
                                    <option value="02">02</option>
                                    <option value="03">03</option>
                                    <option value="04">04</option>
                                    <option value="05">05</option>
                                    <option value="06">06</option>
                                    <option value="07">07</option>
                                    <option value="08">08</option>
                                    <option value="09">09</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="19">19</option>
                                    <option value="20">20</option>
                                    <option value="21">21</option>
                                    <option value="22">22</option>
                                    <option value="23">23</option>
                                    <option value="24">24</option>
                                    <option value="25">25</option>
                                    <option value="26">26</option>
                                    <option value="27">27</option>
                                    <option value="28">28</option>
                                    <option value="29">29</option>
                                    <option value="30">30</option>
                                    <option value="31">31</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Municipio de Nacimeinto</label>
                                <input  maxlength="100" type="text" required="required" class="form-control" placeholder="Ingrese una cedula"  />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Municipio de nacimiento </label>
                                <input  maxlength="100" type="text" required="required" class="form-control" placeholder="Ingrese una cedula"  />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="mesNacimiento" data-alias="mesNacimiento" class="control-label">Mes de nacimiento</label>
                                <select id="mesNacimiento" name="mesNacimiento" class="form-control " required="true">
                                    <option value="01">Enero</option>
                                    <option value="02">Febrero</option>
                                    <option value="03">Marzo</option>
                                    <option value="04">Abril</option>
                                    <option value="05">Mayo</option>
                                    <option value="06">Junio</option>
                                    <option value="07">Julio</option>
                                    <option value="08">Agosto</option>
                                    <option value="09">Septiembre</option>
                                    <option value="10">Octubre</option>
                                    <option value="11">Noviembre</option>
                                    <option value="12">Diciembre</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Estado de nacimiento</label>
                                <input  maxlength="100" type="text" required="required" class="form-control" placeholder="Ej. Sonora"  />
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
                                <label class="control-label"><output></output>Año de nacimiento</label>
                                <input  maxlength="4" minlength="4" type="text" required="required" class="form-control" placeholder="Ej. 1999"  />
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
                    <!-- step 2 -->
                   
                    <button type="btnIguiente" class="btn btn-warning">Siguiente</button>              
                   
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

  <script>

 </script>

@stop
