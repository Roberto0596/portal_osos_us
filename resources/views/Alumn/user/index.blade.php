@extends('Alumn.main')

@section('content-alumn')

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

            <!-- Profile Image -->
            <div class="card card-primary card-outline">

              <div class="card-body box-profile">

                <div class="text-center">

                  <img class="profile-user-img img-fluid img-circle" src="{{asset($user->photo)}}" alt="User profile picture">

                </div>

                <h3 class="profile-username text-center">{{$user->name}}</h3>

                <p class="text-muted text-center">Software Engineer</p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Followers</b> <a class="float-right">1,322</a>
                  </li>
                  <li class="list-group-item">
                    <b>Following</b> <a class="float-right">543</a>
                  </li>
                  <li class="list-group-item">
                    <b>Friends</b> <a class="float-right">13,287</a>
                  </li>
                </ul>

                <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>

      </div>

    </div>

  </section>

</div>

@stop
