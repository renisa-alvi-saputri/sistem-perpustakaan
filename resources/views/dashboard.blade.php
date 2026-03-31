@extends('layouts.app')

@section('content')

@if(auth()->user()->role == 'petugas')

<h2 class="text-2xl font-semibold mb-6">Hai, Petugas 👋</h2>
@include('dashboard.petugas')

@elseif(auth()->user()->role == 'anggota')

<h2 class="text-2xl font-semibold mb-6">Hai, Anggota 👋</h2>
@include('dashboard.anggota')

@elseif(auth()->user()->role == 'kepala')

<h2 class="text-2xl font-semibold mb-6">Hai, Kepala 👋</h2>
@include('dashboard.kepala')

@else
<h2>Role tidak dikenali</h2>
@endif

@endsection
