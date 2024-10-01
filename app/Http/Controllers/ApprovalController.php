<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Approval;
use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\Pegawai;
use Carbon\Carbon;

class ApprovalController extends Controller
{

    public function index()
    {
        // Ambil semua data dari tabel approvals
        $approvals = Approval::with(['divisi', 'barang'])->get();
        
        // Kirim data ke view
        return view('barang.approvals.index', compact('approvals'));
    }

    // public function index()
    // {
    //     $approvals = Approval::all();
    //     return view('barang.approvals.index', compact('approvals'));
    // }
    public function approve($id)
    {
        $approval = Approval::find($id);
        if ($approval) {
            $approval->status = 'approved';
            $approval->save();

            // Pindahkan data ke tabel barang_keluar
            BarangKeluar::create([
                'kode_bk' => $approval->kode_bk,
                'divisi_id' => $approval->divisi_id,
                'barang_id' => $approval->barang_id,
                'jumlah' => $approval->jumlah,
                'satuan' => $approval->satuan,
                'tanggal' => $approval->tanggal,
                'keterangan' => $approval->keterangan
            ]);

            // Update stok barang di tabel barang
            $dt_barang = Barang::where('id_barang', $approval->barang_id)->first();
            if ($dt_barang) {
                $dt_barang->jumlah -= $approval->jumlah;
                $dt_barang->save();
            }
        }

        return redirect()->route('approvals.index');
    }

    // public function reject($id)
    // {
    //     $approval = Approval::find($id);
    //     if ($approval) {
    //         $approval->status = 'rejected';
    //         $approval->save();
    //     }

    //     return redirect()->route('approvals.index');
    // }
    public function reject(Request $request, $id)
    {
        $approval = Approval::find($id);
        if ($approval) {
            $approval->status = 'rejected';
            $approval->keterangan_approval = $request->input('keterangan_approval'); // Menyimpan keterangan
            $approval->save();
        }

        return redirect()->route('approvals.index');
    }
    public function destroy($id)
    {
        $approval = Approval::find($id);
        if ($approval) {
            $approval->delete();
        }

        return redirect()->route('approvals.index');
    }

}
