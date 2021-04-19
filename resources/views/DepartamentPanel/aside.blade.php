<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background: green !important;">

    <a href="/" class="brand-link">

        <img src="{{ asset('img/temple/unisierra.png') }}" alt="" class="brand-image img-circle elevation-3"
            style="opacity: .8">

        <span class="brand-text font-weight-light">Unisierra</span>

    </a>

    <div class="sidebar">

        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('img/temple/osos_alfa.png') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::guard('departament')->user()->name }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{route('departament.home')}}" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Inicio
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('departament.debit')}}" class="nav-link">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>
                            Adeudos
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('departament.user')}}" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Mi cuenta
                        </p>
                    </a>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-folder"></i>
                        <p>
                            Bitacora
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('departament.logs.classrooms.index')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Aulas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('departament.logs.equipment.index')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Equipos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('admin.users')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Reporte</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>

        </nav>

    </div>

</aside>