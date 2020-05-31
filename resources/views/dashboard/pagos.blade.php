@extends('layouts.dashboard.table')
@php
	$heads = ["#", "Nombre", "Descripción", "Precio"];
	$row = 0;
@endphp

@section('alert')
	@if (isset($alert))
	<div class="alert alert-{{$alert[0]}} alert-dismissible fade show" role="alert">
		{{-- <strong>Holy guacamole!</strong> --}} {{$alert[1]}}
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	@endif
@endsection

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
			<td>{{$item->nombre}}</td>
			<td>{{$item->descripcion}}</td>
			<td>{{$item->precio}}</td>
		</tr>
	@endforeach
@endsection

@section('edit-form-content')
	<div class="form-group">
		<label for="nombre">Nombre</label>
		<input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre">
	</div>
	<div class="form-group">
		<label for="descripcion">Descripción</label>
		<input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripción">
	</div>
	<div class="form-group">
		<label for="precio">Precio</label>
		<input type="number" class="form-control" id="precio" name="precio"placeholder="Precio">
	</div>
@endsection

@section('dashboard-table-pagination')
	@include('layouts.dashboard.pagination', ['pagination'=>$pagination])
@endsection

@section('scripts')
	@include('layouts.dashboard.edittable', ['pagination'=>$pagination])
	<script>
		var selectNewRow = function(id) {
			var lastRowData = getTableData()[id];
			$("#nombre").val(lastRowData.nombre);
			$("#descripcion").val(lastRowData.descripcion);
			$("#precio").val(lastRowData.precio);
			$("#rowid").val(lastRowData.id);
		}
		var lastRowData = {};
		$(function() {
			$("#modal-data-table").on("show.bs.modal", function (e) {
				selectNewRow($(e.relatedTarget).parents("tr").index());
			})
			$("#guardar-cambios").on("click", function (e) {
				if (!parseFloat($("#precio").val()) || parseFloat($("#precio").val())<0) {
					$("#precio").addClass("is-invalid")
					return;
				} else {
					$("#precio").removeClass("is-invalid")
				}
				$("#form-data").submit();
			})
		})
	</script>
@endsection