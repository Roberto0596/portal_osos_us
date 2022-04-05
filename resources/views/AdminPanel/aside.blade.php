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
                <a href="#" class="d-block">{{ Auth::guard('admin')->user()->name }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{route('admin.home')}}" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Inicio
                        </p>
                    </a>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Usuarios externos
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('admin.alumns')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Alumnos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('admin.users')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Usuarios</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{route('admin.report')}}" class="nav-link">
                        <i class="nav-icon fas fa-chart-pie" aria-hidden="true"></i>
                        <p>
                            Reportes
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('admin.problem')}}" class="nav-link">
                        <i class="nav-icon fas fa-archive"></i>
                        <p>
                            Problemas
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('admin.failed.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-times"></i>
                        <p>
                            Inscripciones fallidas
                        </p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{ route('admin.document') }}" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Documentos
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.document.request') }}" class="nav-link">
                        <i class="nav-icon fas fa-folder"></i>
                        <p>
                            Solicitudes de documentos
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('admin.ticket.index')}}" class="nav-link">
                        <i class="nav-icon fa fa-file"></i>
                        <p>
                            Visualizador de tickets
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.debit-type') }}" class="nav-link">
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>
                            Tipos de Adeudo
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.document-type') }}" class="nav-link">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>
                            Tipos de Documentos
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.high-averages') }}" class="nav-link">
                        <i class="nav-icon fas fa-medal"></i>
                        <p>
                            Promedios Altos
                        </p>
                    </a>
                </li>
                

                <li class="nav-item">
                    <a href="{{route('admin.user')}}" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Mi cuenta
                        </p>
                    </a>
                </li>

                 <li class="nav-item">
                    <a href="{{route('admin.settings')}}" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Configuración
                        </p>
                    </a>
                </li>

            </ul>

        </nav>

    </div>

</aside>