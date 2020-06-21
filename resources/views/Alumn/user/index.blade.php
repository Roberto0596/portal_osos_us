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

          <div class="card card-warning card-outline">

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

          </div>

        </div>

        <div class="col-md-9">

          <div class="card">

            <div class="card-header p-2">

              <ul class="nav nav-pills">

                <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Noticias</a></li>
                <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>

              </ul>

            </div>

            <div class="card-body scroll-perfil">

              <div class="tab-content">

                <div class="active tab-pane" id="activity">

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

                  <form class="form-horizontal">

                    <div class="form-group row">

                      <label for="inputName" class="col-sm-2 col-form-label">Name</label>

                      <div class="col-sm-10">

                        <input type="email" class="form-control" id="inputName" placeholder="Name">

                      </div>

                    </div>

                    <div class="form-group row">

                      <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>

                      <div class="col-sm-10">

                        <input type="email" class="form-control" id="inputEmail" placeholder="Email">

                      </div>

                    </div>

                    <div class="form-group row">

                      <label for="inputName2" class="col-sm-2 col-form-label">Name</label>

                      <div class="col-sm-10">

                        <input type="text" class="form-control" id="inputName2" placeholder="Name">

                      </div>

                    </div>

                    <div class="form-group row">

                      <label for="inputExperience" class="col-sm-2 col-form-label">Experience</label>

                      <div class="col-sm-10">

                        <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>

                      </div>

                    </div>

                    <div class="form-group row">

                      <label for="inputSkills" class="col-sm-2 col-form-label">Skills</label>

                      <div class="col-sm-10">

                        <input type="text" class="form-control" id="inputSkills" placeholder="Skills">
                      </div>

                    </div>

                    <div class="form-group row">

                      <div class="offset-sm-2 col-sm-10">

                        <div class="checkbox">

                          <label>
                            <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                          </label>

                        </div>

                      </div>

                    </div>

                    <div class="form-group row">
                      <div class="offset-sm-2 col-sm-10">
                        <button type="submit" class="btn btn-success">guardar</button>
                      </div>

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

@stop
