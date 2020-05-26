<table class="table">
	<thead>
		<tr>
			<th scope="col">#</th>
			<th scope="col">Nombre</th>
			<th scope="col">Last</th>
			<th scope="col">Handle</th>
		</tr>
	</thead>
	<tbody>
	  {{-- <tr>
		<th scope="row">1</th>
		<td>Mark</td>
		<td>Otto</td>
		<td>@mdo</td>
	  </tr> --}}
		@foreach ($pagination as $item)
		<tr>
			<th scope="row">{{$item->id}}</th>
			<td>{{$item->name}}</td>
			<td>Otto</td>
			<td>@mdo</td>
		</tr>
		@endforeach
	</tbody>
</table>