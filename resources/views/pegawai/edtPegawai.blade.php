@extends('layout.main')

@section('pegawai', 'active')

@section('content')

<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header"><b>Edit Pegawai</b></div>
            <div class="card-body card-block">
                @foreach ($divisi as $item)
                <form method="POST" action="{{ route('uptPegawai', ['id'=>$item->id_divisi]) }}">
                    @method('put')
                    @csrf
                    <div class="mb-3">
                        <label for="kode_divisi" class="form-label">Kode divisi</label>
                        <input type="text" class="form-control" id="kode_divisi" name="kode_divisi"
                            value="{{ $item->kode_divisi}}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="nama_divisi" class="form-label">Nama Divisi</label>
                        <input type="text" class="form-control" id="nama_divisi" name="nama_divisi"
                            value="{{ $item->nama_divisi}}">
                    </div>
                    <div class="mb-3">
                        <label for="lokasi" class="form-label">Lokasi</label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi" value="{{ $item->lokasi}}">
                    </div>
                    <button type="submit" class="btn btn-sm btn-warning">Edit</button>
                    <a href="/pegawai" class="btn btn-sm btn-danger">Kembali</a>
                </form>
                @endforeach
            </div>
        </div>
    </div>

</div>

@endsection
