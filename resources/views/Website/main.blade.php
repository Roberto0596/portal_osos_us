@extends('layout')

@section('content')

<div class="content-custom">

	@include('Website.header')

	@yield('main-content')

</div>

@stop