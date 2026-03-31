<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $fillable = ['judul', 'penulis', 'stok', 'kategori_id', 'cover', 'tahun_terbit'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
