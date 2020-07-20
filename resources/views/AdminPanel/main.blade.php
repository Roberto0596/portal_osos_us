@extends('layout')

@section('content')
	
	<div class="wrapper">

		@include('AdminPanel.header')
		@include('AdminPanel.aside')
		@yield('content-admin')
		@include('AdminPanel.footer')
	</div>

@stop