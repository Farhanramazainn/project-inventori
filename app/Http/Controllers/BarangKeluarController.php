<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\Pegawai;
use App\Models\Approval;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $barang_keluar = BarangKeluar::all();
        return view('barang.barang keluar.dftBarangKeluar', compact('barang_keluar'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $barang = Barang::all();
        $divisi = Pegawai::all();

        $thn = Carbon::now()->year;
        $var = 'BK';
        $bms = BarangKeluar::count();
        if ($bms == 0) {
            $awal = 10001;
            $kode_bk = $var.$thn.$awal;
            // BK2021001
        } else {
           $last = BarangKeluar::all()->last();
           $awal = (int)substr($last->kode_bk, -5) + 1;
           $kode_bk = $var.$thn.$awal;
        }
        return view('barang.barang keluar.tbhBarangKeluar', compact('kode_bk', 'barang', 'divisi'));
    }

    public function get_barang($id)
    {
        $data_bk = Barang::where('id_barang', $id)->first();

        return response()->json([
            'data_bk' => $data_bk,
        ]);

        // return view('barang.barang keluar.tbhBarangKeluar', compact('kode_bk', 'barang', 'pegawai', 'id', 'jml_barang'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     // dd($request->all());
    //     $request->validate([
    //         'nama_divisi' => 'required',
    //         'tgl_keluar' => 'required'
    //     ]);

    //     $kode_bk = $request->kode_bk;
    //     $nama_divisi = $request->nama_divisi;
    //     $tgl = $request->tgl_keluar;
    //     $nama_barang = $request->nama_barang;
    //     $id_barang = $request->id_barang;
    //     $jumlah = $request->jml;
    //     $satuan = $request->satuan;

    //     foreach ($jumlah as $key => $value) {
    //         // dd($value);
    //         if ($value == 0) {
    //             continue;
    //         }
    //         $dt_barang = Barang::where('id_barang', $id_barang[$key])->first();
    //         // dd([$jumlah[$key], $dt_barang->jumlah]);
    //         if ($jumlah[$key] > $dt_barang->jumlah) {
    //             alert()->error('Gagal','Jumlah Barang Melebihi Stok Barang.');
    //             return back()   ;
    //         } else {
    //             Barang::where('id_barang', $id_barang[$key])->update([
    //                 'jumlah' => $dt_barang->jumlah - $jumlah[$key]
    //             ]);
    //             BarangKeluar::insert([
    //                 'kode_bk' => $kode_bk,
    //                 'divisi_id' => $nama_divisi,
    //                 'barang_id' => $id_barang[$key],
    //                 'jumlah' => $jumlah[$key],
    //                 'satuan' => $satuan[$key],
    //                 'tanggal' => $tgl,
    //             ]);
    //             alert()->success('Berhasil','Kegiatan Berhasil Ditambahkan.');
    //             return back();
    //         }
    //     }
    // }
    public function store(Request $request)
    {
        $request->validate([
            'nama_divisi' => 'required',
            'tgl_keluar' => 'required',
            'id_barang' => 'required|array',
            'jml' => 'required|array',
            'satuan' => 'required|array',
            'keterangan' => 'nullable|array'
        ]);

        $kode_bk = $request->kode_bk;
        $nama_divisi = $request->nama_divisi;
        $tgl = $request->tgl_keluar;
        $id_barang = $request->id_barang;
        $jumlah = $request->jml;
        $satuan = $request->satuan;
        $keterangan = $request->keterangan;

        foreach ($id_barang as $key => $barang_id) {
            $jumlah_barang = $jumlah[$key];

            if ($jumlah_barang == 0) {
                continue;
            }

            $dt_barang = Barang::where('id_barang', $barang_id)->first();
            if (!$dt_barang) {
                alert()->error('Gagal', 'Barang tidak ditemukan.');
                return back();
            }

            if ($jumlah_barang > $dt_barang->jumlah) {
                alert()->error('Gagal', 'Jumlah Barang Melebihi Stok Barang.');
                return back();
            }

            // Simpan data ke tabel approval dengan status 'pending'
            Approval::create([
                'kode_bk' => $kode_bk,
                'divisi_id' => $nama_divisi,
                'barang_id' => $barang_id,
                'jumlah' => $jumlah_barang,
                'satuan' => $satuan[$key],
                'tanggal' => $tgl,
                'keterangan' => $keterangan,
                'status' => 'pending'
            ]);

            // Update stok barang di tabel barang
            // Barang::where('id_barang', $barang_id)->update([
            //     'jumlah' => $dt_barang->jumlah - $jumlah_barang
            // ]);
        }

        alert()->success('Berhasil', 'Data barang keluar berhasil disimpan untuk proses approval.');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
