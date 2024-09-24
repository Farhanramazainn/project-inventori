<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Approval ;
use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
//use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $barang = Barang::count('id_barang');
        $barang_masuk = BarangMasuk::count('id_barang_masuk');
        $barang_keluar = BarangKeluar::count('id_barang_keluar');
        $total_approval  = Approval::count('id');

        $data_masuk = DB::table('barang_masuk') // Ganti 'barang' dengan nama tabel yang sesuai
          ->select(DB::raw('DATE(tanggal) as date'), DB::raw('COUNT(*) as count'))
          ->groupBy('date')
          ->get();
        
          $data_keluar = DB::table('barang_keluar')
            ->select(DB::raw('DATE(tanggal) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->get();
              
        return view('dashboard', compact('barang', 'barang_masuk', 'barang_keluar', 'total_approval','data_masuk','data_keluar'));
    }

    public function laporan()
    {
        return view('laporan.laporan');
    }

    public function cetak_laporan(Request $request)
    {
        $request->validate([
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required',
            'jenis_laporan' => 'required'
        ]);

        $dari = $request->tgl_awal;
        $sampai = $request->tgl_akhir;
        $jenis = $request->jenis_laporan;
        $cek = Carbon::today();
        $hari_ini = $cek->toDateString();

        // Validasi tanggal
        if ($dari > $sampai) {
            alert()->error('Data Gagal Dicetak', 'Tanggal Akhir Melebihi Tanggal Awal.');
            return back();
        }

        if ($dari > $hari_ini || $sampai > $hari_ini) {
            alert()->error('Data Gagal Dicetak.', 'Tanggal Melebihi Hari Ini.');
            return back();
        }

        // Menyiapkan nama file CSV
        $fileName = ($jenis == 'masuk') ? 'Laporan Barang Masuk.csv' : 'Laporan Barang Keluar.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        // Callback untuk mengisi data ke dalam CSV
        $callback = function() use($dari, $sampai, $jenis) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            if ($jenis == 'masuk') {
                fputcsv($file, ['ID Barang Masuk', 'Kode Barang', 'Nama Barang', 'Jumlah', 'Tanggal']);
                $data_masuk = BarangMasuk::where('tanggal', '>=', $dari)
                            ->where('tanggal', '<=', $sampai)
                            ->get();
                foreach ($data_masuk as $item) {
                    fputcsv($file, [$item->id_barang_masuk, $item->barang->kode_barang, $item->barang->nama, $item->jumlah, $item->tanggal]);
                }
            } else {
                fputcsv($file, ['ID Barang Keluar', 'Kode Barang', 'Nama Barang', 'Jumlah', 'Tanggal']);
                $data_keluar = BarangKeluar::where('tanggal', '>=', $dari)
                            ->where('tanggal', '<=', $sampai)
                            ->get();
                foreach ($data_keluar as $item) {
                    fputcsv($file, [$item->id_barang_keluar, $item->barang->kode_barang, $item->barang->nama, $item->jumlah, $item->tanggal]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
