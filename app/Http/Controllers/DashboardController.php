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

    // Sum of 'jumlah' grouped by 'pemasok_id'
    $jumlah_masuk_dari_pemasok = BarangMasuk::with('pemasok')
    ->select(DB::raw('DATE(tanggal) as date'), 'pemasok_id', DB::raw('SUM(jumlah) as total'))
    ->where('pemasok_id', 1) // Menyaring pemasok_id yang bernilai 1
    ->groupBy('pemasok_id', DB::raw('DATE(tanggal)'))
    ->get();

    $jumlah_masuk_dari_office = BarangMasuk::with('pemasok')
    ->select(DB::raw('DATE(tanggal) as date'), 'pemasok_id', DB::raw('SUM(jumlah) as total'))
    ->where('pemasok_id', 2) // Menyaring pemasok_id yang bernilai 1
    ->groupBy('pemasok_id', DB::raw('DATE(tanggal)'))
    ->get();

    $jumlah_keluar_dari_office_front = BarangKeluar::with('divisi')
    ->select(DB::raw('DATE(tanggal) as date'), 'divisi_id', DB::raw('SUM(jumlah) as total'))
    ->where('divisi_id', 3) 
    ->groupBy('divisi_id', DB::raw('DATE(tanggal)'))
    ->get();


    $jumlah_keluar_dari_office_back = BarangKeluar::with('divisi')
    ->select(DB::raw('DATE(tanggal) as date'), 'divisi_id', DB::raw('SUM(jumlah) as total'))
    ->where('divisi_id', 5) 
    ->groupBy('divisi_id', DB::raw('DATE(tanggal)'))
    ->get();

    // dd($jumlah_keluar_dari_office);


    // Getting data for 'barang_masuk' grouped by date
    $data_masuk = DB::table('barang_masuk')
        ->select(DB::raw('DATE(tanggal) as date'), DB::raw('COUNT(*) as count'))
        ->groupBy('date')
        ->get();
    
    // Getting data for 'barang_keluar' grouped by date
    $data_keluar = DB::table('barang_keluar')
        ->select(DB::raw('DATE(tanggal) as date'), DB::raw('COUNT(*) as count'))
        ->groupBy('date')
        ->get();

        // dd($data_keluar);

    return view('dashboard', compact('barang', 'barang_masuk', 'barang_keluar', 'total_approval', 'jumlah_masuk_dari_pemasok','jumlah_masuk_dari_office','jumlah_keluar_dari_office_front','jumlah_keluar_dari_office_back', 'data_masuk', 'data_keluar'));
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
                // Menambahkan judul tabel
                fputcsv($file, ['Laporan Barang Masuk']);
                fputcsv($file, ['Kode Kategori', 'Gudang', 'Nama Barang', 'Jumlah', 'Tanggal Masuk']);
                
                $data_masuk = BarangMasuk::where('tanggal', '>=', $dari)
                            ->where('tanggal', '<=', $sampai)
                            ->get();
                foreach ($data_masuk as $item) {
                    fputcsv($file, [
                        $item->kode_bm, 
                        $item->pemasok->nama, 
                        $item->nama, 
                        $item->jumlah, 
                        date('d F Y', strtotime($item->tanggal))
                    ]);
                }

                
            } else {
                // Menambahkan judul tabel
                fputcsv($file, ['Laporan Barang Keluar']);
                fputcsv($file, ['Kode Barang', 'Nama Divisi', 'Nama Barang', 'Jumlah', 'Keterangan','Tanggal']);
                
                $data_keluar = BarangKeluar::where('tanggal', '>=', $dari)
                            ->where('tanggal', '<=', $sampai)
                            ->get();
                foreach ($data_keluar as $item) {
                    fputcsv($file, [
                        $item->kode_bk, 
                        $item->divisi->nama_divisi, 
                        $item->barang->nama, 
                        $item->jumlah,
                        $item->keterangan, 
                        $item->tanggal
                    ]);
                }
            }
        
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}