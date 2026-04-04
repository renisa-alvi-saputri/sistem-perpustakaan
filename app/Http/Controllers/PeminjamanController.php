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
                ->whereIn('status', ['menunggu', 'dipinjam'])
                ->get();

            $buku = Buku::where('stok', '>', 0)->get();

            return view('peminjaman.anggota', compact('peminjaman', 'buku'));
        }

        $peminjaman = Peminjaman::with('user', 'buku')
            ->whereIn('status', ['menunggu', 'dipinjam'])
            ->get();

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
            'jatuh_tempo' => now()->addDays(7),
            'status' => 'menunggu'
        ]);

        $buku->decrement('stok');

        return redirect('/peminjaman')->with('success', 'Buku berhasil dipinjam');
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
            'jatuh_tempo' => $request->jatuh_tempo,
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

        // Ambil semua peminjaman yang sudah dikembalikan
        $peminjaman = Peminjaman::with('user', 'buku')
            ->whereIn('status', ['dikembalikan', 'selesai'])
            ->orderBy('tgl_kembali', 'desc')
            ->get();

        return view('pengembalian.petugas', compact('peminjaman'));
    }

    /**
     * Proses pengembalian oleh petugas (dengan denda)
     */
    public function storePengembalian(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id'
        ]);

        $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Buku sudah dikembalikan');
        }

        $jatuhTempo = Carbon::parse($peminjaman->jatuh_tempo);
        $hariIni = Carbon::now();
        $denda = 0;

        if ($hariIni->gt($jatuhTempo)) {
            $hariTelat = $hariIni->diffInDays($jatuhTempo);
            $denda = $hariTelat * 1000;
        }

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tgl_kembali' => $hariIni,
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
        $riwayatPengembalian = Peminjaman::with('buku')
            ->where('user_id', Auth::id())
            ->where('status', 'dikembalikan')
            ->get();

        $peminjaman = Peminjaman::with('buku')
            ->where('user_id', Auth::id())
            ->where('status', 'dipinjam')
            ->get();

        return view('pengembalian.anggota', compact('peminjaman', 'riwayatPengembalian'));
    }

    /**
     * Proses pengembalian oleh anggota (tanpa denda)
     */
    public function storePengembalianAnggota(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id'
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
            'tgl_kembali' => now()
        ]);

        $peminjaman->buku->increment('stok');

        return back()->with('success', 'Pengembalian berhasil');
    }

    public function riwayat()
    {
        $peminjaman = Peminjaman::with('buku')
            ->where('user_id', Auth::id())
            ->where('status', 'selesai')
            ->get();

        return view('riwayat.anggota', compact('peminjaman'));
    }

    public function konfirmasi($id)
    {
        $p = Peminjaman::findOrFail($id);
        $p->status = 'dipinjam';
        $p->save();

        return redirect()->back();
    }

    public function selesai($id)
    {
        $p = Peminjaman::findOrFail($id);
        $p->status = 'selesai';
        $p->save();

        return back()->with('success', 'Status jadi selesai');
    }
}
