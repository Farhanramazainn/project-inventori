<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $divisi = Pegawai::all();
        return view('pegawai.dftPegawai', compact('divisi'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $thn = Carbon::now()->year;
        $var = 'MLG';
        $bms = Pegawai::count();
        if ($bms == 0) {
            $awal = 10001;
            $kode_divisi = $var.$thn.$awal;
            // BM2021001
        } else {
           $last = Pegawai::all()->last();
           $awal = (int)substr($last->kode_divisi, -5) + 1;
           $kode_divisi = $var.$thn.$awal;
        }

        return view('pegawai.tbhPegawai', compact('kode_divisi'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pegawai = $request->validate([
            'kode_divisi' => 'required',
            'nama_divisi' => 'required',
            'lokasi' => 'required'
        ]);
        // dd($pegawai);

        Pegawai::create($pegawai);
        alert()->success('Berhasil','Divisi Baru Berhasil Ditambahkan.');
        return back();
    }

    public function konfir(Request $request, $id)
    {
        $request->validate([
            'id_divisi' => 'required',
            'nama_divisi' => 'required',
            'lokasi' => 'required',
            'password' => 'required'
        ]);
        // dd($request->all());

        $divisi = new User;
        $divisi->nama = $request->nama_divisi;
        $divisi->lokasi = $request->lokasi;
        $divisi->password = bcrypt($request->password);
        $divisi->save();

        alert()->success('Berhasil','Role divisi Berhasil Diperbaharui.');
        return back();
    }

    public function edit($id)
    {
        $divisi = Pegawai::where('id_divisi', $id)->get();
        // dd($pegawai);
        return view('pegawai.edtPegawai', compact('divisi'));
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
        $data_p = Pegawai::where('id_divisi', $id)->first();
        $rules = [
            'kode_divisi' => 'required',
            'nama_divisi' => 'required',
            'lokasi' => 'required',
        ];

        // if ($request->lokasi != $data_p->lokasi) {
        //    $rules['email'] = 'required|email:dns|unique:pegawai';
        // }

        $divisi = $request->validate($rules);

        Pegawai::where('id_divisi', $id)->update($divisi);
        alert()->success('Berhasil','Data divisi Berhasil Diupdate.');
        return redirect('/pegawai');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Pegawai::where('id_divisi', $id)->delete();
        alert()->success('Berhasil','Data divisi Berhasil Dihapus.');
        return back();
    }
}
