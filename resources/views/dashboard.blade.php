@extends('layouts.app')

@section('content')

@if(auth()->user()->role == 'petugas')>
@include('dashboard.petugas')

@elseif(auth()->user()->role == 'anggota')
@include('dashboard.anggota')

@elseif(auth()->user()->role == 'kepala')
@include('dashboard.kepala')

@else
<h2>Role tidak dikenali</h2>
@endif

@endsection
