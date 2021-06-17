<!DOCTYPE html>
<html>
<head>
    <title>Sistema portal</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="Universidad de la sierra, Unisierra, Universidad sonora, Universidad en la sierra, Moctezuma, Sonora, Moctezuma sonora, Portal de alumnos unisierra, portal de alumnos, portal de inscripciones, alumnos, portal">
    <meta name="description" content="Bienvenido al sistema de inscripciones de la universidad de la sierra.">
    <link rel="icon" type="image/png" href="{{asset('img/temple/unisierra.png')}}" />
    <!-- estilos -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <!-- select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.css') }}">

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/modals.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/loadLoader.css') }}">

    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('bower/dropzone/dist/dropzone.css')}}" />
    <!-- bootstrap toggle -->
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
   
    <!-- scripts -->
    
    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
     <!-- bootstrap toggle -->
     <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.js') }}"></script>
    <!-- select2 -->
    <script src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('dist/js/demo.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <!-- sweetalert -->
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.js') }}"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>

    <!-- InputMask -->
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>
    <script src="{{ asset('js/temple.js') }}"></script>
    <script src="{{ asset('push/push.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
    <script src="{{asset('bower/dropzone/dist/dropzone.js')}}"></script>

     <!-- html2pdf -->
     <script src="{{asset('plugins/html2pdf/dist/html2pdf.bundle.min.js')}}"></script>
    
    {!! htmlScriptTagJsApi() !!}
</head>
<body id="body" class="sidebar-mini sidebar-collapse" style="height: auto;">
    <!-- Preloader -->

    @include('chunks.load-loader')

    <div id="preloader">

        <div class="preload-content">

            <div id="original-load"></div>

        </div>

    </div>
    
    @yield('content')
    
    <script>
        $(document).ready(function () 
        {
            @if (session()->get('messages'))

                <?php
                    $fm = explode('|', session()->get('messages'));
                    if (count($fm) > 1) 
                    {
                        $ftype = $fm[0];
                        $fmessage = $fm[1];
                        
                    }
                ?>
                var timeout = setTimeout(() => 
                {
                    swal.close()
                }, 15000);
                swal.fire({
                    title: "{{ $fmessage }}",
                    text: "{{isset($fm[2]) ?  $fm[2] : ''}}",
                    type: '{{$ftype}}',
                    buttons: "Aceptar"
                }).then((value) => {
                    clearTimeout(timeout);
                });
                        
            @endif
        });
    </script>
</body>
</html>