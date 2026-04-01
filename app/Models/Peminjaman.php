<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'user_id',
        'buku_id',
        'tgl_pinjam',
        'tgl_kembali',
        'tgl_dikembalikan',
        'status',
        'denda'
    ];

    // RELASI KE USER
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // RELASI KE BUKU
    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}
