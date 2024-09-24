<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;
    protected $table = 'barang_keluar';
    protected $primaryKey = 'id_barang_keluar';
    protected $fillable = [
        'barang_id',
        'divisi_id',
        'kode_bk',
        'jumlah',
        'satuan',
        'tanggal',
        'keterangan'
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
