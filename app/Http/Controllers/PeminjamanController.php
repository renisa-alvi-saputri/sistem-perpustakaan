<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Buku;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    // ================= INDEX (PEMINJAMAN) =================
    public function index()
    {
        // 🔥 kalau anggota, cuma lihat miliknya sendiri
        if (Auth::user()->role == 'anggota') {
            $peminjaman = Peminjaman::with('user', 'buku')
                ->where('user_id', Auth::id())
                ->get();

            return view('peminjaman.anggota', compact('peminjaman'));
        }

        // 👮 petugas
        $peminjaman = Peminjaman::with('user', 'buku')->get();
        $anggota = User::where('role', 'anggota')->get();
        $buku = Buku::where('stok', '>', 0)->get();

        return view('peminjaman.petugas', compact('peminjaman', 'anggota', 'buku'));
    }

    // ================= STORE (PINJAM) =================
    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required',
            'tgl_pinjam' => 'required|date',
            'tgl_kembali' => 'required|date'
        ]);

        $buku = Buku::findOrFail($request->buku_id);

        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok habis!');
        }

        // 👮 PETUGAS
        if (Auth::user()->role == 'petugas') {
            $request->validate(['user_id' => 'required']);

            Peminjaman::create([
                'user_id' => $request->user_id,
                'buku_id' => $buku->id,
                'tgl_pinjam' => $request->tgl_pinjam,
                'tgl_kembali' => $request->tgl_kembali,
                'status' => 'dipinjam'
            ]);
        }
        // 👤 ANGGOTA
        else {
            Peminjaman::create([
                'user_id' => Auth::id(),
                'buku_id' => $buku->id,
                'tgl_pinjam' => $request->tgl_pinjam,
                'tgl_kembali' => $request->tgl_kembali,
                'status' => 'dipinjam'
            ]);
        }

        $buku->decrement('stok');

        return back()->with('success', 'Berhasil pinjam!');
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $peminjaman->update([
            'user_id' => $request->user_id,
            'buku_id' => $request->buku_id,
            'tgl_pinjam' => $request->tgl_pinjam,
            'tgl_kembali' => $request->tgl_kembali,
        ]);

        return back()->with('success', 'Data berhasil diupdate');
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        Peminjaman::findOrFail($id)->delete();
        return back();
    }

    // ================= HALAMAN PENGEMBALIAN =================
    public function pengembalian()
    {
        $peminjaman = Peminjaman::with('user', 'buku')
            ->where('status', 'dipinjam')
            ->get();

        return view('pengembalian.petugas', compact('peminjaman'));
    }

    // ================= STORE PENGEMBALIAN =================
    public function storePengembalian(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required'
        ]);

        $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);

        // ❌ cegah double return
        if ($peminjaman->status == 'dikembalikan') {
            return back()->with('error', 'Buku sudah dikembalikan!');
        }

        $tglKembali = Carbon::parse($peminjaman->tgl_kembali);
        $hariIni = Carbon::now();

        $denda = 0;

        // 🔥 CEK TELAT
        if ($hariIni->gt($tglKembali)) {
            $hariTelat = $hariIni->diffInDays($tglKembali);
            $denda = $hariTelat * 1000;
        }

        // 🔥 UPDATE
        $peminjaman->update([
            'status' => 'dikembalikan',
            'tgl_dikembalikan' => $hariIni,
            'denda' => $denda
        ]);

        // 🔥 BALIKIN STOK
        $peminjaman->buku->increment('stok');

        return back()->with('success', 'Berhasil dikembalikan');
    }
}
