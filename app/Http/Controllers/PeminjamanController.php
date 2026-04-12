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
     * Halaman daftar peminjaman.
     * - Anggota : hanya melihat miliknya sendiri
     * - Petugas : melihat semua data
     */
    public function index()
    {
        if (Auth::user()->role === 'anggota') {
            $peminjaman = Peminjaman::with('user', 'buku')
                ->where('user_id', Auth::id())
                ->whereIn('status', ['menunggu', 'dipinjam', 'ditolak'])
                ->get();

            $buku = Buku::where('stok', '>', 0)->get();

            return view('peminjaman.anggota', compact('peminjaman', 'buku'));
        }

        $peminjaman = Peminjaman::with('user', 'buku')
            ->whereIn('status', ['menunggu', 'dipinjam', 'ditolak'])
            ->get();

        $anggota = User::where('role', 'anggota')->get();
        $buku    = Buku::where('stok', '>', 0)->get();

        return view('peminjaman.petugas', compact('peminjaman', 'anggota', 'buku'));
    }

    /**
     * Proses peminjaman buku oleh anggota.
     * Validasi: maksimal 3 buku aktif (status menunggu/dipinjam).
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'anggota') {
            abort(403, 'Hanya anggota yang bisa meminjam.');
        }

        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
        ]);

        // Cek jumlah buku yang sedang dipinjam atau menunggu konfirmasi
        $jumlahAktif = Peminjaman::where('user_id', Auth::id())
            ->whereIn('status', ['menunggu', 'dipinjam'])
            ->count();

        // Tolak jika sudah 3 buku aktif
        if ($jumlahAktif >= 3) {
            return back()->with('error', 'Kamu sudah meminjam 3 buku. Kembalikan buku terlebih dahulu sebelum meminjam lagi.');
        }

        $buku = Buku::findOrFail($request->buku_id);

        // Cek stok buku
        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis.');
        }

        Peminjaman::create([
            'user_id'     => Auth::id(),
            'buku_id'     => $buku->id,
            'tgl_pinjam'  => now(),
            'jatuh_tempo' => now()->addDays(7),
            'status'      => 'menunggu',
        ]);

        $buku->decrement('stok');

        return back()->with('success', 'Buku berhasil dipinjam!');
    }

    /**
     * Konfirmasi peminjaman oleh petugas → status jadi 'dipinjam'.
     */
    public function konfirmasi($id)
    {
        $p = Peminjaman::findOrFail($id);
        $p->status = 'dipinjam';
        $p->save();

        return back();
    }

    /**
     * Tolak peminjaman oleh petugas → status jadi 'ditolak', stok dikembalikan.
     */
    public function tolak(Request $request, $id)
    {
        $p = Peminjaman::findOrFail($id);
        $p->status       = 'ditolak';
        $p->alasan_tolak = $request->alasan_tolak;
        $p->save();

        // Kembalikan stok buku
        $p->buku->increment('stok');

        return back()->with('success', 'Peminjaman ditolak.');
    }

    /**
     * Halaman pengembalian.
     * - Anggota : diarahkan ke halaman khusus anggota
     * - Petugas : melihat semua data yang sudah/sedang dikembalikan
     */
    public function pengembalian()
    {
        if (Auth::user()->role === 'anggota') {
            return redirect()->route('pengembalian.anggota');
        }

        $peminjaman = Peminjaman::with('user', 'buku')
            ->whereIn('status', ['dikembalikan', 'selesai'])
            ->orderBy('tgl_kembali', 'desc')
            ->get();

        return view('pengembalian.petugas', compact('peminjaman'));
    }

    /**
     * Proses pencatatan pengembalian oleh petugas (hitung denda Rp2.000/hari).
     */
    public function storePengembalian(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
        ]);

        $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Buku sudah dikembalikan.');
        }

        $jatuhTempo = Carbon::parse($peminjaman->jatuh_tempo);
        $hariIni    = Carbon::now();
        $denda      = 0;

        // Hitung denda jika terlambat
        if ($hariIni->gt($jatuhTempo)) {
            $hariTelat = $hariIni->diffInDays($jatuhTempo);
            $denda     = $hariTelat * 2000; // Rp2.000 per hari
        }

        $peminjaman->update([
            'status'      => 'dikembalikan',
            'tgl_kembali' => $hariIni,
            'denda'       => $denda,
        ]);

        // Kembalikan stok buku
        $peminjaman->buku->increment('stok');

        return back()->with('success', 'Pengembalian berhasil.');
    }

    /**
     * Halaman pengembalian khusus anggota.
     */
    public function pengembalianAnggota()
    {
        // Buku yang sedang dipinjam anggota ini
        $peminjaman = Peminjaman::with('buku')
            ->where('user_id', Auth::id())
            ->where('status', 'dipinjam')
            ->get();

        // Riwayat buku yang sudah dikembalikan
        $riwayatPengembalian = Peminjaman::with('buku')
            ->where('user_id', Auth::id())
            ->where('status', 'dikembalikan')
            ->get();

        return view('pengembalian.anggota', compact('peminjaman', 'riwayatPengembalian'));
    }

    /**
     * Proses pengembalian oleh anggota (tanpa hitung denda, status 'dikembalikan').
     * Denda dihitung nanti oleh petugas saat konfirmasi selesai.
     */
    public function storePengembalianAnggota(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
        ]);

        $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);

        // Pastikan buku milik anggota yang login
        if ($peminjaman->user_id != Auth::id()) {
            return back()->with('error', 'Data tidak valid.');
        }

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Buku sudah dikembalikan.');
        }

        $peminjaman->update([
            'status'      => 'dikembalikan',
            'tgl_kembali' => now(),
        ]);

        // Kembalikan stok buku
        $peminjaman->buku->increment('stok');

        return back()->with('success', 'Pengembalian berhasil.');
    }

    /**
     * Konfirmasi pengembalian selesai oleh petugas → status jadi 'selesai'.
     */
    public function selesai($id)
    {
        $p = Peminjaman::findOrFail($id);
        $p->status = 'selesai';
        $p->save();

        return back()->with('success', 'Status jadi selesai.');
    }

    /**
     * Halaman riwayat peminjaman.
     * - Kepala  : melihat semua riwayat yang sudah selesai
     * - Anggota : hanya melihat riwayat miliknya sendiri
     */
    public function riwayat()
    {
        if (Auth::user()->role === 'kepala') {
            $peminjaman = Peminjaman::with('user', 'buku')
                ->where('status', 'selesai')
                ->get();

            return view('riwayat.kepala', compact('peminjaman'));
        }

        // Riwayat peminjaman selesai milik anggota yang login
        $peminjaman = Peminjaman::with('buku')
            ->where('user_id', Auth::id())
            ->where('status', 'selesai')
            ->get();

        return view('riwayat.anggota', compact('peminjaman'));
    }
}
