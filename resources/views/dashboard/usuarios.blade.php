@extends('layouts.dashboard.table')
@php
	$heads = ["#", "Nombre", "Correo", "Celular", "Dirección"];
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
			<td>{{$item->name}}</td>
			<td>{{$item->email}}</td>
			<td>{{$item->phone}}</td>
			<td>{{$item->direccion}}</td>
		</tr>
	@endforeach
@endsection
@section('dashboard-table-pagination')
	@include('layouts.dashboard.pagination', ['pagination'=>$pagination])
@endsection