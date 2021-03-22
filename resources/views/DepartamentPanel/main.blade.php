@extends('layout')

@section('content')
	
	<div class="wrapper">

		@include('DepartamentPanel.header')
		@include('DepartamentPanel.aside')
		@yield('content-departament')
		@include('DepartamentPanel.footer')
	</div>

@stop