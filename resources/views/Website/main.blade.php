@extends('layout')

@section('content')

<div class="content">

	@include('Website.header')

	@yield('main-content')

</div>

@stop