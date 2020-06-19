@extends('dashboard.usuarios')

@section('title', 'Drivers')

@section('alert')
	@if (isset($alert))
	<div class="alert alert-{{$alert[0]}} alert-dismissible fade show" role="alert">
		{{$alert[1]}} {{isset($alert[2]) ? $alert[2] : ""}}
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	@endif
@endsection

@php
	$heads = ["#", "DNI", "Activo", "Habilitado", "Nombre", "Correo", "Celular", "Dirección"];
	$row = 0;
@endphp

@section('dashboard-table-head')
	@foreach ($heads as $item)
	<th scope="col">{{$item}}</th>
	@endforeach
	{{-- @parent --}}
@endsection

@section('dashboard-table-body')
	@foreach ($pagination as $item)
		<tr>
			{{-- <th scope="row">{{$item->id}}</th> --}}
			<th scope="row">{{++$row}}</th>
			<td>{{$item->dni}}</td>
			@if ($item->verified_at)
				@if ($item->activo)
					<td style="color: var(--success); text-align: center">
						<svg class="bi bi-check-circle-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
						</svg>
					</td>
				@else
					<td style="color: var(--danger); text-align: center">
						<svg class="bi bi-x-circle-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.146-3.146a.5.5 0 0 0-.708-.708L8 7.293 4.854 4.146a.5.5 0 1 0-.708.708L7.293 8l-3.147 3.146a.5.5 0 0 0 .708.708L8 8.707l3.146 3.147a.5.5 0 0 0 .708-.708L8.707 8l3.147-3.146z"/>
						</svg>
					</td>
				@endif
				<td style="color: var(--success); text-align: center">
					<svg class="bi bi-check-circle-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
					</svg>
				</td>
			@else
				<td></td>
				<td style="color: var(--danger); text-align: center">
					<svg class="bi bi-x-circle-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.146-3.146a.5.5 0 0 0-.708-.708L8 7.293 4.854 4.146a.5.5 0 1 0-.708.708L7.293 8l-3.147 3.146a.5.5 0 0 0 .708.708L8 8.707l3.146 3.147a.5.5 0 0 0 .708-.708L8.707 8l3.147-3.146z"/>
					</svg>
				</td>
			@endif
			<td>{{$item->user->name}}</td>
			<td>{{$item->user->email}}</td>
			<td>{{str_replace("+51", "", $item->user->phone)}}</td>
			<td>{{$item->user->direccion}}</td>
		</tr>
	@endforeach
@endsection

@section('edit-form-content')
	<div class="form-group">
		<label for="dni">DNI</label>
		<input type="text" class="form-control" id="dni" name="dni" placeholder="DNI">
	</div>
	<div class="form-row">
		<div class="col">
			<div class="form-check">
				<input class="form-check-input" type="checkbox" name="habilitado" id="habilitado">
				<label class="form-check-label" for="habilitado">
					Habilitado
				</label>
			</div>
		</div>
	</div>
	{{-- @parent --}}
@endsection


@section('scripts')
@include('layouts.dashboard.edittable', ['pagination'=>$pagination, 'heads'=>$heads])
<script src="{{ mix("/js/components/Drivers.js") }}"></script>
@endsection
