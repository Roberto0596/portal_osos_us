@extends('DepartamentPanel.main')

@section('content-departament')

<div class="content-wrapper">

  <section class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1>{{$instance->id ? 'Crear' : 'Editar'}} <small>aula</small></h1>

        </div>

        <!-- <div class="col-sm-6">

          <ol class="breadcrumb float-sm-right">

            <li class="breadcrumb-item active"><a href="#">Home</a></li>

          </ol>

        </div> -->

      </div>

    </div>

  </section>

  <section class="content">

    <div class="container">

      <div class="card">

        <div class="card-header"><h3>Complete el formulario</h3></div>

        <form action="{{ route('departament.logs.classrooms.save', $instance) }}">

          <div class="card-body">

            <div class="form-group">
              <label for="code">Nombre</label>
              <input type="text" class="form-control" id="name" name="name" value="{{$instance->id ? $instance->name : old('name')}}" placeholder="Ingrese un nombre" required="">
            </div>

            <div class="form-group">
              <label for="code">Estado</label>
              <select class="form-control" id="status" name="status" required="">
                <option value="0" @if($instance->status == 0) selected @endif>Activo y libre</option>
                <option value="1" @if($instance->status == 1) selected @endif>Ocupado o en mantenimiento</option>
              </select>
            </div>
        
          </div>

          <div class="card-footer">
            <a href="{{route('departament.logs.classrooms.index')}}" class="btn btn-danger">Volver</a>
            <button class="btn btn-success">Guardar</button>
          </div>

        </form>

      </div>

    </div>

  </section>

</div>

<script>

</script>

@stop
