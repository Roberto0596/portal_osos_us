@extends('layout')

@section('content')

<div class="login-form">

    <form action="" method="post">

        <h2 class="text-center">Bienvenido</h2>  

        <div class="form-group">

            <input type="text" class="form-control" placeholder="Username" required="required">

        </div>

        <div class="form-group">

            <input type="password" class="form-control" placeholder="Password" required="required">

        </div>

        <div class="form-group">

            <button type="submit" class="btn btn-primary btn-block">Log in</button>

        </div>

    </form>

    

</div>

@stop