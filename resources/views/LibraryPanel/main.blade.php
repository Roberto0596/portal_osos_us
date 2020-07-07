@extends('layout')

@section('content')
	
	<div class="wrapper">

		@include('LibraryPanel.header')
		@include('LibraryPanel.aside')
		@yield('content-library')
		@include('LibraryPanel.footer')
	</div>

@stop