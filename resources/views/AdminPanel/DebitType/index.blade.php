@extends('AdminPanel.main')

@section('content-admin')

<div class="content-wrapper">

  <section class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1>Tipos de Adeudos</h1>

        </div>

        <div class="col-sm-6">

          <ol class="breadcrumb float-sm-right">

            <li class="breadcrumb-item"><a href="#">Home</a></li>

            <li class="breadcrumb-item active"><a href="#">tipos de Adeudo</a></li>

          </ol>

        </div>

      </div>

    </div>

  </section>

  <section class="content">

    <div class="card">

      <div class="card-body">

        
    <div class="card-header" style="padding-bottom: 3%">
        <button data-toggle="modal" data-target="#modalDebitType" class="btn btn-success" id="create">Crear un nuevo Tipo de Adeudo</button>
    </div>

        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        
        <table class="table table-bordered table-hover tableDebitType" id="DebitType">

          <thead>

            <tr>
              <th style="width: 10px">#</th>
              <th>Concepto</th>
              <th style="width: 15%; display:flex;">Acciones</th>
              
            </tr>

          </thead>

        </table>

      </div>

    </div>

  </section>

</div>

<div class="modal fade" id="modalDebitType">

    <div class="modal-dialog modal-lg">
  
      <div class="modal-content">
  
          <form method="post" action="{{route('admin.debittype.create')}}">
              
              {{ csrf_field() }}

              <input type="hidden" name="_token" value="{{ csrf_token() }}" id="tokenUpdate">
  
              <div class="modal-header">
  
                  <h4 class="modal-title">CREAR NUEVO TIPO DE ADEUDO</h4>
  
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
  
              </div>
          
              <div class="modal-body">
  
                  <div class="row">

                    <div class="col-md-12">           
  
                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-ad"></i></span>
                            </div>

                        <input type="text" name="concept" placeholder="Concepto" class="form-control form-control-lg" required>

                    </div>
  
                     
  
                      <div class="col-md-6">           
  
                          <div class="input-group mb-3">
  
                              <div class="input-group-prepend">
                                  <span class="input-group-text">
                                  <i class="fas fa-question"></i></span>
                              </div>

                              <select name="can_delete" id="can_delete" class="form-control form-control-lg">
                                <option value="" disabled selected>Se puede eliminar?</option>
                                <option value="0">No</option>
                                <option value="1">Si</option>
                              </select>
  

                            
                          </div>
  
                      </div>
  
                     
  
                      </div>
  
                  </div>
  
              </div>
  
              <div class="modal-footer justify-content">
  
                  <div class="col-sm container-fluid">
  
                      <div class="row">
  
                          <div class=" col-sm-6 btn-group">
  
                          <button id="cancel" type="button" class="btn btn-danger .px-2 " data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
  
                          </div>
  
                          <div class=" col-sm-6 btn-group">
  
                          <button type="submit" id="sale" class="btn btn-success .px-2"><i class="fa fa-check"></i> Guardar</button>
                          
                          </div>
  
                      </div>
  
                  </div>
  
              </div>
  
         </form>
  
      </div>
  
    </div>
  
  </div>

  <div class="modal fade" id="modalDebitTypeEdit">

    <div class="modal-dialog modal-lg">
  
      <div class="modal-content">
  
          <form method="post" action="{{ route ('admin.debittype.update')}}">


              
            @csrf

          
  
  
              <div class="modal-header">
  
                  <h4 class="modal-title">Editar Adeudo</h4>
  
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
  
              </div>

              <input type="hidden" name="id" value="" id="idToUpdate">
          
              <div class="modal-body">
  
                  <div class="row">

                    <div class="col-md-12">           
  
                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fas fa-ad"></i></span>
                            </div>

                        <input  id="conceptEdit" type="text" name="concept" placeholder="Concepto" class="form-control form-control-lg" required>

                    </div>
  
                     
  
                      <div class="col-md-6">           
  
                          <div class="input-group mb-3">
  
                              <div class="input-group-prepend">
                                  <span class="input-group-text">
                                  <i class="fas fa-question"></i></span>
                              </div>

                              <select name="can_delete" id="can_delete_edit" class="form-control form-control-lg">
                                <option value="" disabled selected>Se puede eliminar?</option>
                                <option value="0">No</option>
                                <option value="1">Si</option>
                              </select>
  

                            
                          </div>
  
                      </div>
  
                     
  
                      </div>
  
                  </div>
  
              </div>
  
              <div class="modal-footer justify-content">
  
                  <div class="col-sm container-fluid">
  
                      <div class="row">
  
                          <div class=" col-sm-6 btn-group">
  
                          <button id="cancelEdit" type="button" class="btn btn-danger .px-2 " data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
  
                          </div>
  
                          <div class=" col-sm-6 btn-group">
  
                          <button type="submit" id="saleEdit" class="btn btn-success .px-2"><i class="fa fa-check"></i> Guardar</button>
                          
                          </div>
  
                      </div>
  
                  </div>
  
              </div>
  
         </form>
  
      </div>
  
    </div>
  
  </div>
  


   <script src="{{ asset('js/admin/debitType.js')}}"></script>





@stop
