<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_bk', 'divisi_id', 'barang_id', 'jumlah', 'satuan', 'tanggal', 'status', 'keterangan'
    ];


    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function divisi()
    {
        return $this->belongsTo(Pegawai::class, 'divisi_id');
    }
}


