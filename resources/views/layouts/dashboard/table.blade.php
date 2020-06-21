@extends('home')

@section('dash-content')
<div class="table-blq h-100"  style="max-width: calc(100vw - 17px)">
	@yield('alert')
	<div id="tbl-blq">
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
	</div>
	@yield('dashboard-table-pagination')
</div>
<script>

window.GetJsonEncodeData = function (encode) {
	var ta = document.createElement("textarea");
	ta.innerHTML = encode;
	const txt = ta.innerText.replace(/\\/g, "\\\\");
	return JSON.parse(txt);
}

</script>

@yield("scripts")
<script src="{{ mix('js/tables.js') }}" defer></script>
@endsection
