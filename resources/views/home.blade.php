@extends('layouts.app')

@section("head")
<link href="{{ asset('css/page/home.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid">
	<div class="row h-100">
		<div class="col-lg-2 px-0 dash-aside-cnt">
			@include('layouts.dashboard.aside')
		</div>
		<div class="col-lg-10 col body-cnt">
			@yield("dash-content", View::make("dashboard.index", ["pagination"=>$pagination]))
		</div>
	</div>
</div>
@endsection
