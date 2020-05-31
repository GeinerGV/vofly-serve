@extends('home')

@section('dash-content')
<div class="table-blq h-100">
	@yield('alert')
	<form class="w-100">
		<div class="form-row">
			<div class="col-md-6 mb-3">
				<label for="len_rows">Rangos de tiempo</label>
				@php
					$len = request()->len;
					if ($len) {
						$len = intval($len);
					} else {
						$len = 15;
					}
					$vals = [15, 30, 50];
				@endphp
				<select class="custom-select" id="len_rows" required>
					@foreach ($vals as $item)
						<option @if ($len==$item)
						selected
					@endif value="{{$item!=15?$item:''}}">{{$item}}</option>
					@endforeach
				</select>
			</div>
		</div>
	</form>
	<div class="table-cnt table-responsive">
		<table class="table table-hover" id="table-data">
			<thead>
				<tr>
					@yield('dashboard-table-head')
				</tr>
			</thead>
			<tbody>
				@yield('dashboard-table-body')
			</tbody>
		</table>
	</div>
	@yield('dashboard-table-pagination')
</div>
<div id="modal-data-table" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">@yield("titleModal", "Editar Tabla")</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="needs-validation" id="form-data" method="POST">
					@csrf
					@yield('edit-form-content')
					<input type="hidden" name="id" id="rowid">
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
				<button id="guardar-cambios" type="button" class="btn btn-primary">Guardar Cambios</button>
			</div>
		</div>
	</div>
</div>
<script src="{{ asset('js/tables.js') }}" defer></script>
@yield("scripts")
@endsection