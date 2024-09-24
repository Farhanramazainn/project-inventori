@extends('layout.main')

@section('pegawai', 'active')

@section('content')

<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header"><b>Tambah divisi</b></div>
            <div class="card-body card-block">
                <form method="POST" action="{{ route('tbhPegawai') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="kode_divisi" class="form-label">Kode Divisi</label>
                        <input type="text" class="form-control" id="kode_divisi" name="kode_divisi"
                            value="{{ $kode_divisi }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="nama_divisi" class="form-label">Nama Divisi</label>
                        <input type="text" class="form-control" id="nama_divisi" name="nama_divisi">
                    </div>
                    <div class="mb-3">
                        <label for="lokasi" class="form-label">Lokasi</label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    <a href="/pegawai" class="btn btn-sm btn-danger">Kembali</a>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection
