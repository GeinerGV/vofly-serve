@extends('layouts.dashboard.table')
@php
	$heads = ["#", "Nombre", "Descripción", "Precio"];
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
			<th scope="row">{{++$row}}</th>
			<td>{{$item->nombre}}</td>
			<td>{{$item->descripcion}}</td>
			<td>{{$item->precio}}</td>
		</tr>
	@endforeach
@endsection
@section('dashboard-table-pagination')
	@include('layouts.dashboard.pagination', ['pagination'=>$pagination])
@endsection