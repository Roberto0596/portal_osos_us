@extends('layout')

@section('content')
	
	<div class="wrapper">

		@include('FinancePanel.header')
		@include('FinancePanel.aside')
		@yield('content-finance')
		@include('FinancePanel.footer')
	</div>

	@include('FinancePanel.pusher')

@stop