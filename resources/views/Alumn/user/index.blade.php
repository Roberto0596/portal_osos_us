@extends('Alumn.main')

@section('content-alumn')

<style>
  .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
    color: #fff;
    background-color: #fd7e14 !important;
}
</style>

<div class="content-wrapper">

  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Mi Perfil</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Unisierra</a></li>
            <li class="breadcrumb-item active">Alumno perfil</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">

    <div class="container-fluid">

      <div class="row">
        
        <div class="col-md-3">

          <div class="card card-warning card-outline">

            <div class="card-body box-profile">

              <div class="text-center">

                <img class="profile-user-img img-fluid img-circle" src="{{Croppa::url($user->photo, 400, 400)}}" alt="User profile picture">

              </div>

              <h3 class="profile-username text-center">{{$user->name}}</h3>

              <p class="text-muted text-center">{{ $user->sAlumn->PlanEstudio->Carrera->Nombre }}</p>

<!--               <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                  <b>Followers</b> <a class="float-right">1,322</a>
                </li>
                <li class="list-group-item">
                  <b>Following</b> <a class="float-right">543</a>
                </li>
                <li class="list-group-item">
                  <b>Friends</b> <a class="float-right">13,287</a>
                </li>
              </ul> -->

              <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->

            </div>

          </div>

        </div>

        <div class="col-md-9">

          <div class="card">

            <div class="card-header p-2">

              <ul class="nav nav-pills">

                <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Noticias</a></li>
                <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Editar</a></li>
                <li style="margin-top: 10px;margin-left: 5%;">Recuerda que el cambio de estos datos, no alteran tu registro de la Universidad</li>

              </ul>

            </div>

            <div class="card-body">

              <div class="tab-content">

                <div class="active tab-pane scroll-perfil" id="activity">

                  <div class="post">

                    <div class="user-block">

                      <img class="img-circle img-bordered-sm" src="{{asset('img/temple/unisierra.png')}}" alt="user image">

                      <span class="username">

                        <a href="#">Unisierra</a>

                        <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>

                      </span>

                      <span class="description">Shared publicly - 7:30 PM today</span>

                    </div>

                    <p>
                      Lorem ipsum represents a long-held tradition for designers,
                      typographers and the like. Some people hate it and argue for
                      its demise, but others ignore the hate as they create awesome
                      tools to help create filler text for everyone from bacon lovers
                      to Charlie Sheen fans.
                    </p>

                  </div>

                </div>

                <div class="tab-pane" id="settings">

                  <form class="form-horizontal" method="post" action="{{route('alumn.user.save',$user)}}" enctype="multipart/form-data">

                    {{ csrf_field() }}

                    <div class="form-group row">

                      <label for="inputName" class="col-sm-2 col-form-label">Nombre</label>

                      <div class="col-sm-10">

                        <input type="text" class="form-control" name="name" placeholder="Nombre" value="{{$user->name}}">

                      </div>

                    </div>

                    <div class="form-group row">

                      <label for="inputName" class="col-sm-2 col-form-label">Apellido</label>

                      <div class="col-sm-10">

                        <input type="text" class="form-control" name="lastname"value="{{$user->lastname}}" required>

                      </div>

                    </div>

                    <div class="form-group row">

                      <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>

                      <div class="col-sm-10">

                        <input type="email" class="form-control form-control-lg" value="{{$user->email}}" id="inputEmail" readonly>

                      </div>

                    </div>

                    <div class="form-group row">

                      <label for="inputName2" class="col-sm-2 col-form-label">Contraseña</label>

                      <div class="col-sm-10">

                        <input type="password" class="form-control" name="password" placeholder="Password">

                      </div>

                    </div>

                    <div class="form-group">

                      <div class="panel">SUBIR FOTO</div>

                      <input type="file" class="newPicture" name="newPicture">

                      <p class="help-block">Peso máximo de la foto 2MB</p>

                      <img src="{{asset($user->photo)}}" class="img-thumbnail preview" width="100px">
                      
                    </div>

                    <div class="form-group row">

                        <button type="submit" class="btn btn-warnign flotante" title="Guardar"><i class="fa fa-save" style="color: white !important"></i></button>

                    </div>

                  </form>

                </div>

              </div>

            </div>

          </div>

        </div>

      </div>

    </div>

  </section>

</div>

<script src="{{asset('js/alumn/user.js')}}"></script>

@stop
