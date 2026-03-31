<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;

        if ($role == 'anggota') {
            return view('dashboard.anggota');
        } elseif ($role == 'petugas') {
            return view('dashboard.petugas');
        } elseif ($role == 'kepala') {
            return view('dashboard.kepala');
        }
    }
}
