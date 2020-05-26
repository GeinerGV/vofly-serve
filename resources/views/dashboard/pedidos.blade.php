@extends('layouts.dashboard.table')
@php
	$heads = ["#", "Usuario", "Driver", "Origen", "Destino", "Pedido", "Plan", "Recorrido", "Estado"];
	$row = 0;
@endphp
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
            <td>{{$item->user->phone}}</td>
            <td>{{isset($item->driver) ? $item->driver->dni : ""}}</td>
            <td>{{$item->recojo->place->direccion}}</td>
            <td>{{$item->entrega->place->direccion}}</td>
            <td>{{$item->carga->tipo}}</td>
            <td>{{$item->plan->nombre}}</td>
            <td>{{$item->distanciaFormatoStr()}}</td>
            <td>{{$item->getEstado()}}</td>
		</tr>
	@endforeach
@endsection
@php
	$distancia = 2;
	$pag_init = $pagination->currentPage()-$distancia;
	if ($pag_init<1) $pag_init =  1;
	$pag_end = $pagination->currentPage() + 2 + ($pagination->currentPage()-$pag_init);
	if ($pag_end>$pagination->lastPage()) $pag_end = $pagination->lastPage();
@endphp
@section('dashboard-table-pagination')
	@include('layouts.dashboard.pagination', ['pagination'=>$pagination])
@endsection