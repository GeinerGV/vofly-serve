@extends('home')

@section('dash-content')
<div class="table-blq h-100">
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
	<div class="table-cnt">
		<table class="table table-hover" id="currentTable">
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
<script src="{{ asset('js/tables.js') }}" defer></script>
@endsection