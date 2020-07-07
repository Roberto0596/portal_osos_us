@extends('layout')

@section('content')
	
	<div class="wrapper">

		@include('ComputerCenterPanel.header')
		@include('ComputerCenterPanel.aside')
		@yield('content-computer')
		@include('ComputerCenterPanel.footer')
	</div>

@stop