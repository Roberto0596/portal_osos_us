@extends('layout')

@section('content')
	
	<div class="loader-classroom">
	  <div class="lds-circle"><div></div></div>
	</div>
	<div class="wrapper">

		@include('DepartamentPanel.header')
		@include('DepartamentPanel.aside')
		@yield('content-departament')
		@include('DepartamentPanel.footer')
	</div>

@stop