<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;
    protected $table = 'divisi';
    protected $primaryKey = 'id_divisi';
    protected $fillable = [
        'kode_divisi',
        'nama_divisi',
        'lokasi'
    ];

    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class);
    }
    public function approval()
    {
        return $this->hasMany(Approval::class);
    }

    public function barang()
    {
        return $this->hasMany(Barang::class);
    }

}
