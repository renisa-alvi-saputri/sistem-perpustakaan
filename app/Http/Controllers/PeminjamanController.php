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
    /**
     * Menampilkan halaman peminjaman
     * - Anggota: hanya lihat miliknya
     * - Petugas: lihat semua data
     */
    public function index()
    {
        if (Auth::user()->role === 'anggota') {
            $peminjaman = Peminjaman::with('user', 'buku')
                ->where('user_id', Auth::id())
                ->get();

            $buku = Buku::where('stok', '>', 0)->get();

            return view('peminjaman.anggota', compact('peminjaman', 'buku'));
        }

        $peminjaman = Peminjaman::with('user', 'buku')->get();
        $anggota = User::where('role', 'anggota')->get();
        $buku = Buku::where('stok', '>', 0)->get();

        return view('peminjaman.petugas', compact('peminjaman', 'anggota', 'buku'));
    }

    /**
     * Proses peminjaman buku oleh anggota
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'anggota') {
            abort(403, 'Hanya anggota yang bisa meminjam.');
        }

        $request->validate([
            'buku_id' => 'required|exists:bukus,id'
        ]);

        $buku = Buku::findOrFail($request->buku_id);

        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok habis');
        }

        Peminjaman::create([
            'user_id' => Auth::id(),
            'buku_id' => $buku->id,
            'tgl_pinjam' => now(),
            'tgl_kembali' => now()->addDays(7),
            'status' => 'dipinjam'
        ]);

        $buku->decrement('stok');

        return back()->with('success', 'Buku berhasil dipinjam');
    }

    /**
     * Update data peminjaman (umumnya oleh petugas)
     */
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

    /**
     * Hapus data peminjaman
     */
    public function destroy($id)
    {
        Peminjaman::findOrFail($id)->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }

    /**
     * Halaman pengembalian
     * - Anggota diarahkan ke halaman khusus anggota
     * - Petugas melihat semua data yang masih dipinjam
     */
    public function pengembalian()
    {
        if (Auth::user()->role === 'anggota') {
            return redirect()->route('pengembalian.anggota');
        }

        $peminjaman = Peminjaman::with('user', 'buku')
            ->where('status', 'dipinjam')
            ->get();

        return view('pengembalian.petugas', compact('peminjaman'));
    }

    /**
     * Proses pengembalian oleh petugas (dengan denda)
     */
    public function storePengembalian(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjamans,id'
        ]);

        $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Buku sudah dikembalikan');
        }

        $tglKembali = Carbon::parse($peminjaman->tgl_kembali);
        $hariIni = Carbon::now();
        $denda = 0;

        if ($hariIni->gt($tglKembali)) {
            $hariTelat = $hariIni->diffInDays($tglKembali);
            $denda = $hariTelat * 1000;
        }

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tgl_dikembalikan' => $hariIni,
            'denda' => $denda
        ]);

        $peminjaman->buku->increment('stok');

        return back()->with('success', 'Pengembalian berhasil');
    }

    /**
     * Halaman pengembalian khusus anggota
     */
    public function pengembalianAnggota()
    {
        $peminjaman = Peminjaman::with('buku')
            ->where('user_id', Auth::id())
            ->where('status', 'dipinjam')
            ->get();

        return view('pengembalian.anggota', compact('peminjaman'));
    }

    /**
     * Proses pengembalian oleh anggota (tanpa denda)
     */
    public function storePengembalianAnggota(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjamans,id'
        ]);

        $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);

        if ($peminjaman->user_id != Auth::id()) {
            return back()->with('error', 'Data tidak valid');
        }

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Buku sudah dikembalikan');
        }

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tgl_dikembalikan' => now()
        ]);

        $peminjaman->buku->increment('stok');

        return back()->with('success', 'Pengembalian berhasil');
    }
}
