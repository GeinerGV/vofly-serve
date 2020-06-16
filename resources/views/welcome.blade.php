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
	<body style="overflow-x: hidden;">
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
					<div class="col-12 d-flex justify-content-center" style="user-select: none;">
						{{-- {{ config('app.name', 'Laravel') }} --}}
						<img src="/logo.png" class="d-block" alt="VoFly" width="95%" style="max-width: 865px">
					</div>
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
			<div class="ocean">
				<div class="wave"></div>
				<div class="wave"></div>
			</div>
			<div class="apk-card-cnt">
				<a class="h5 btn text-align-center py-2 toggle-card">Descarga nuestra app</a>
				<div class="apk-blq col-12">
					<div class="row justify-content-around mb-3">
						<div class="d-flex app col-md-5 justify-content-center">
							<div class="card" style="width: 18rem;">
								<img src="/images/icon-vofly-user.png" class="card-img-top" alt="VoFly App" width="256">
								<div class="card-body">
								  <h5 class="card-title">VoFly App</h5>
								  <p class="card-text">Aplicación para los usuarios que desean realizar sus pedidos</p>
								  <a href="/apks/vofly-app-e4915640f5d04d5aa70c91ff67bd4586-signed.apk" download="vofly-app-3.apk" class="btn btn-primary">Descárgala</a>
								</div>
							  </div>
						</div>
						<div class="d-flex app col-md-5 justify-content-center">
							<div class="card" style="width: 18rem;">
								<img src="/images/icon-vofly-driver.png" class="card-img-top" alt="VoFly App" width="256">
								<div class="card-body">
								  <h5 class="card-title">VoFly Driver</h5>
								  <p class="card-text">Aplicación para nuestros repartidores de pedidos</p>
								  <a href="/apks/vofly-driver-app-71ad6b035fc4438c8f048ab06ded288e-signed.apk" download="vofly-driver-3.apk" class="btn btn-primary">Descárgala</a>
								</div>
							  </div>
						</div>
					</div>
					<p class="h3 text-align-center">Pronto estaremos en Google Play</p>
				</div>
			</div>
		</div>
		<script>
			$(function () {
				$(".toggle-card").on("click", function(e) {
					$(".apk-blq").toggleClass("view");
					if ($(".apk-blq").hasClass("view")) {
						$(".toggle-card").text("Ocultar")
					} else {
						$(".toggle-card").text("Descarga nuestra app")
					}
				})
			})
		</script>
	</body>
</html>
