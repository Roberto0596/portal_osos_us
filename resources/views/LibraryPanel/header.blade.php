<nav class="main-header navbar navbar-expand navbar-orange" role = "navigation">

  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" style="color:white"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <div class="navbar-custom-menu ml-auto">

    <ul class="nav navbar-nav" style="margin: 0px;">

      <li class="dropdown user user-menu generic">

        <a href="#" class = "dropdown-toggle" data-toggle="dropdown"> 

            <img src="{{ asset(\Auth::guard('computercenter')->user()->photo) }}" class="user-image">                       
            <span class = "hidden-xs" style="color: white !important;">{{ Auth::guard('computercenter')->user()->email }}</span>

        </a>

        <ul class="dropdown-menu" >
    
          <li class="user-body">

            <div class = "pull-right">
              
              <a href="{{ route('computo.logout') }}" class="btn btn-default btn-flat">salir</a>
              
            </div>

          </li>

        </ul>
            
      </li>
               
    </ul>

  </div>

</nav>