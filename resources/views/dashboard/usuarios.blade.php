@extends('layouts.dashboard.table')

@php
	$heads = ["#", "Nombre", "Correo", "Celular", "Dirección"];
	$row = 0;
@endphp

@section('title', 'Usuarios')
@section('dashboard-table-head')
	@foreach ($heads as $item)
	<th scope="col">{{$item}}</th>
	@endforeach
@endsection

@section('dashboard-table-body')
	@foreach ($pagination as $item)
		<tr>
			{{-- <th scope="row">{{$item->id}}</th> --}}
			<th scope="row">{{++$row}}</th>
			<td>{{$item->name}}</td>
			<td>{{$item->email}}</td>
			<td>{{str_replace("+51", "", $item->phone)}}</td>
			<td>{{$item->direccion}}</td>
		</tr>
	@endforeach
@endsection

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

@section('edit-form-content')
	<div class="form-row">
		<div class="form-group col-md-6">
			<label for="name">Nombre</label>
			<input type="text" class="form-control" id="name" 
				name="name" placeholder="Nombre" required
			/>
		</div>
		<div class="form-group col-md-6">
			<label for="email">Correo</label>
			<input type="email" class="form-control" id="email" name="email" 
			placeholder="Correo" required>
		</div>
	</div>
	<div class="form-row">
		<div class="form-group col-md-6">
			<label for="phone">Celular</label>
			<input type="tel" class="form-control" id="phone" name="phone" 
				placeholder="Celular" required maxlength="9" minlength="9">
		</div>
		<div class="form-group col-md-6">
			<label for="direccion">Dirección</label>
			<input type="text" class="form-control" id="direccion" name="direccion" 
				placeholder="Dirección" required>
		</div>
	</div>
@endsection

@section('scripts')

@include('layouts.dashboard.edittable', ['pagination'=>$pagination, 'heads'=>$heads])
<script src="{{ mix("/js/components/Usuarios.js") }}"></script>
@endsection

@section('dashboard-table-pagination')
	@include('layouts.dashboard.pagination', ['pagination'=>$pagination])
@endsection
