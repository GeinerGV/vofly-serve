<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>{{ config('app.name', 'Laravel') }}</title>

		<!-- Fonts -->
		<link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

		<link href="{{ asset('css/vendor.css') }}" rel="stylesheet">
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	</head>
	<body>
		<div id="app">
			{{-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm w-100">
				<div class="container">
					<a class="navbar-brand" href="{{ url('/') }}">
						{{ config('app.name', 'Laravel') }}
					</a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
						<span class="navbar-toggler-icon"></span>
					</button>
	
					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<ul class="navbar-nav ml-auto">
							@auth
								<li class="nav-item">
									<a class="nav-link" href="/dashboard">{{ __('Dashboard') }}</a>
								</li>
							@else
								<li class="nav-item">
									<a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
								</li>
							@endauth
						</ul>
					</div>
				</div>
			</nav> --}}
			<div class="container-fluid wrapper">
				<div class="row h-100" style="justify-content: center; align-items: center;">
					<p class="display-1 text-center col-12 text-light font-weight-bold">
						{{-- {{ config('app.name', 'Laravel') }} --}}
						<img src="/logo.png" alt="VoFly">
					</p>
				</div>
			</div>
			<script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
			<ul class="colorlib-bubbles">
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
			</ul>
		</div>
	</body>
</html>
