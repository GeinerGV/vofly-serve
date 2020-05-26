@extends('home')

@php
	$path = request()->path();
@endphp

@section('dash-content')
@switch($path)
	@case("dashboard")
<div class="row">
	<div class="p-3 dash-header w-100">
		<h3>Dashboard</h3>
	</div>
</div>
<div class="row py-3 dashboard-cards">
	<div class="col-md-4">
		{{-- .card.text-white.bg-info>.card-header{Usuarios}+.card-body --}}
		<div class="card text-white bg-info">
			<button type="button" class="select_data_type btn btn-light" data-value="Usuarios">
				<svg class="bi bi-eye-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					<path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
					<path fill-rule="evenodd" d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
				</svg>
			</button>
			<div class="card-body">
				<div class="media">
					<div class="d-flex align-items-center flex-column align-self-center mr-3">
						<div class="icon-data-type-cnt">
							<svg class="bi bi-people-fill" width="4em" height="4em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
							</svg>
						</div>
						<p class="text-align-center small h6">USUARIOS</p>
					</div>
					<div class="media-body">
						<div style="font-size: 0.6rem">NUEVOS HOY</div>
						<h4 class="card-title">0+</h4>
						<div class="row">
							<div class="col-6 flex-column">
								<div style="font-size: 0.7rem">TOTAL</div>
								<div class="">{{App\User::all()->count()}}</div>
							</div>
							<div class="col-6 flex-column">
								<div style="font-size: 0.7rem">ACTIVOS</div>
								<div class="">{{App\User::all()->count()}}</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		{{-- .card.text-white.bg-info>.card-header{Usuarios}+.card-body --}}
		<div class="card text-white bg-danger">
			<button type="button" class="select_data_type btn btn-light" data-value="Drivers">
				<svg class="bi bi-eye-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					<path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
					<path fill-rule="evenodd" d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
				</svg>
			</button>
			<div class="card-body">
				<div class="media">
					<div class="d-flex align-items-center flex-column align-self-center mr-3">
						<svg class="bi bi-cart-plus mb-1" width="3.7em" height="3.7em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" d="M8.5 5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1H8V5.5a.5.5 0 0 1 .5-.5z"/>
							<path fill-rule="evenodd" d="M8 7.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1H9v1.5a.5.5 0 0 1-1 0v-2z"/>
							<path fill-rule="evenodd" d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm7 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
						</svg>
						<p class="text-align-center small h6">DRIVERS</p>
					</div>
					<div class="media-body">
						<div style="font-size: 0.6rem">NUEVOS HOY</div>
						<h4 class="card-title">0+</h4>
						<div class="row">
							<div class="col-6 flex-column">
								<div style="font-size: 0.7rem">TOTAL</div>
								<div class="">{{App\Driver::all()->count()}}</div>
							</div>
							<div class="col-6 flex-column">
								<div style="font-size: 0.7rem">ACTIVOS</div>
								<div class="">{{App\Driver::all()->count()}}</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card text-white bg-primary">
			<button type="button" class="select_data_type btn btn-light" data-value="Pedidos">
				<svg class="bi bi-eye-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					<path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
					<path fill-rule="evenodd" d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
				</svg>
			</button>
			<div class="card-body">
				<div class="media">
					<div class="d-flex align-items-center flex-column align-self-center mr-3">
						<svg class="bi bi-bag-plus mb-1"width="3.7em" height="3.7em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" d="M14 5H2v9a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V5zM1 4v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4H1z"/>
							<path d="M8 1.5A2.5 2.5 0 0 0 5.5 4h-1a3.5 3.5 0 1 1 7 0h-1A2.5 2.5 0 0 0 8 1.5z"/>
							<path fill-rule="evenodd" d="M8 7.5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H6a.5.5 0 0 1 0-1h1.5V8a.5.5 0 0 1 .5-.5z"/>
							<path fill-rule="evenodd" d="M7.5 10a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0v-2z"/>
						</svg>
						<p class="text-align-center small h6">PEDIDOS</p>
					</div>
					<div class="media-body">
						<div style="font-size: 0.6rem">NUEVOS HOY</div>
						<h4 class="card-title">0+</h4>
						<div class="row">
							<div class="col-6 flex-column">
								<div style="font-size: 0.7rem">TOTAL</div>
								<div class="">{{App\Delivery::all()->count()}}</div>
							</div>
							<div class="col-6 flex-column">
								<div style="font-size: 0.7rem">PENDIENTES</div>
								<div class="">{{App\Delivery::all()->count()}}</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="w-100 p-5 chart-cnt">
	<form class="w-100">
		<div class="form-row">
			<div class="col-md-6 mb-3">
				<label for="time_type">Rangos de tiempo</label>
				<select class="custom-select" id="time_type" required>
					<option selected value="dia">Última semana</option>
					<option value="mes">Último año</option>
				</select>
			</div>
		</div>
	  </form>
	<canvas id="dashboardChart"></canvas>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="{{ asset('js/dashboard.js') }}" defer></script>
		@break
	@default
		@include('layouts.dashboard.simpletable', ["pagination"=>$pagination])
@endswitch
@endsection