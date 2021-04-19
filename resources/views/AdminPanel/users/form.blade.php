@extends('AdminPanel.main')

@section('content-admin')

<div class="content-wrapper">

  <section class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1>Crear usuario</h1>

        </div>

        <div class="col-sm-6">

          <ol class="breadcrumb float-sm-right">

            <li class="breadcrumb-item"><a href="#">Home</a></li>

            <li class="breadcrumb-item active"><a href="#">Usuarios</a></li>

            <li class="breadcrumb-item"><a href="#">Crear usuario</a></li>

          </ol>

        </div>

      </div>

    </div>

  </section>

  <section class="content">

    <div class="card">

      <div class="card-body">

        <form action="{{route('admin.users.save', $user)}}" method="post" enctype="multipart/form-data">
          
          {{ csrf_field() }}

          <div class="row">

            <div class="col-md-6">

              <label class="label-style" for="nombre">Nombre completo</label>

              <div class="input-group mb-3">

                  <div class="input-group-prepend">

                    <span class="input-group-text"><i class="fas fa-user"></i></span>

                  </div>

                  <input type="text" id="name" name="name" placeholder="Nombres" class="form-control form-control-lg" required value="{{$user->name}}">

              </div>

            </div>

            <div class="col-md-6">

              <label class="label-style" for="nombre">Apellido(s)</label>

              <div class="input-group mb-3">

                  <div class="input-group-prepend">

                    <span class="input-group-text"><i class="fas fa-user"></i></span>

                  </div>

                  <input type="text" id="lastname" name="lastname" placeholder="Ingrese sus apellidos" class="form-control form-control-lg" required value="{{$user->lastname}}">

              </div>

            </div>

            <div class="col-md-6">

              <label class="label-style" for="nombre">E-mail</label>

              <div class="input-group mb-3">

                  <div class="input-group-prepend">

                    <span class="input-group-text"><i class="fas fa-user"></i></span>

                  </div>

                  <input type="text" id="email" name="email" placeholder="Ingrese su correo" class="form-control form-control-lg" required value="{{$user->email}}">

              </div>

            </div>

            <div class="col-md-6">

              <label class="label-style" for="nombre">Password</label>

              <div class="input-group mb-3">

                  <div class="input-group-prepend">

                    <span class="input-group-text"><i class="fas fa-key"></i></span>

                  </div>

                  <input type="password" id="password" name="password" placeholder="Ingrese su password" class="form-control form-control-lg" @if(!$user->id) required @endif>

              </div>

            </div>

            <div class="col-md-6">

              <label class="label-style" for="nombre">Area</label>

              <div class="input-group mb-3">

                  <div class="input-group-prepend">

                    <span class="input-group-text"><i class="fas fa-building"></i></span>

                  </div>

                  <select name="area_id" id="area_id" class="form-control form-control-lg" required>
                    <option value="">Seleccione un area</option>
                    @foreach($areas as $value)
                      <option value="{{$value->id}}" 
                        @if($user->area_id==$value->id) selected @endif >{{$value->name}}</option>
                    @endforeach
                  </select>

              </div>

            </div>

            <div class="col-md-6">

              <div class="form-group">

                <div class="panel">SUBIR FOTO</div>

                <input type="file" class="photo" name="photo">

                <p class="help-block">Peso máximo de la foto 2MB</p>

                <img src="{{$user->id ? asset($user->photo) : 'img/alumn/default/default.png'}}" class="img-thumbnail preview" width="100px">
                
              </div>

            </div>

          </div>

          <div class="row">
            <div class="col-md-6">
              <a href="{{route('admin.users')}}" class="btn btn-danger" style="width: 100%"><i class="fa fa-times"></i> Cancelar</a>
            </div>
            <div class="col-md-6">
              <button class="btn btn-success" style="width: 100%"><i class="fa fa-check"></i> Guardar</button>
            </div>
          </div>

        </form>

      </div>

    </div>

  </section>

</div>

<script src="{{ asset('js/admin/users.js')}}"></script>

@stop
