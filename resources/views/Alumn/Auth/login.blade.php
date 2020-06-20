@extends('layout')

@section('content')

<div class="login-form">

    <form action="" method="post">

        {{ csrf_field() }}

        <h2 class="text-center">Bienvenido</h2>  

        <div class="form-group">

            <input type="email" class="form-control" placeholder="e-mail" name="email" required="required">

        </div>

        <div class="form-group">

            <input type="password" class="form-control" placeholder="Password" name="password" required>

        </div>

        <div class="form-group">

            <button type="submit" class="btn btn-primary btn-block">Log in</button>

        </div>

    </form>

    

</div>

@stop