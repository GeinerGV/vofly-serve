@extends('layouts.dashboard.table')
@php
	$heads = ["#", "Usuario", "Driver", "Origen", "Destino", "Pedido", "Plan", "Recorrido", "Estado"];
@endphp

@section('title', 'Pedidos')
@section('dashboard-table-head')
	@foreach ($heads as $item)
	<th scope="col">{{$item}}</th>
	@endforeach
@endsection

@section('dashboard-table-body')
	@foreach ($pagination as $item)
		<tr>
			{{-- <th scope="row">{{$item->id}}</th> --}}
			{{-- <th scope="row">{{++$row}}</th>
			<td>{{$item->name}}</td>
			<td>{{$item->email}}</td>
			<td>{{$item->phone}}</td>
            <td>{{$item->direccion}}</td> --}}
            <th scope="row">{{++$row}}</th>
            <td>{{isset($item->user) ? $item->user->phone : $item->user_id}}</td>
            <td>{{isset($item->driver) ? $item->driver->dni : ""}}</td>
            <td>{{$item->recogible->place->direccion}}</td>
            <td>{{$item->entregable->place->direccion}}</td>
            <td>{{$item->cargable->tipo}}</td>
            <td>{{$item->plan->nombre}}</td>
            <td>{{$item->distanciaFormatoStr()}}</td>
            <td>{{$item->getEstado()}}</td>
		</tr>
	@endforeach
@endsection
@php
	/* $distancia = 2;
	$pag_init = $pagination->currentPage()-$distancia;
	if ($pag_init<1) $pag_init =  1;
	$pag_end = $pagination->currentPage() + 2 + ($pagination->currentPage()-$pag_init);
	if ($pag_end>$pagination->lastPage()) $pag_end = $pagination->lastPage(); */
@endphp
@section('dashboard-table-pagination')
	@include('layouts.dashboard.pagination', ['pagination'=>$pagination])
@endsection
@section('scripts')

@include('layouts.dashboard.edittable', ['pagination'=>$pagination, 'heads'=>$heads])
<script src="{{ mix("/js/components/Pedidos.js") }}"></script>
@endsection
