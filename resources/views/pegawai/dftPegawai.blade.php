@extends('layout.main')

@section('pegawai', 'active')

@section('content')

<a href="{{ url('/tbhPegawai') }}" class="btn btn-primary btn-sm mb-3"><i class="fa fa-plus"></i> Tambah Divisi</a>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong class="card-title">Daftar Divisi</strong>
            </div>
            <div class="card-body">
                <table id="data-pegawai" class="table table-striped table-bordered" data-ordering="false">
                    <thead>
                        <tr>
                            
                            <th>Kode Divisi</th>
                            <th>Keterangan</th>
                            <th>Lokasi</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($divisi as $item)
                        <tr>
                           
                            <td>{{ $item->kode_divisi }}</td>
                            <td>{{ $item->nama_divisi }}</td>
                            <td>{{ $item->lokasi }}</td>
                            <td>
                                <!-- <a data-toggle="modal" data-target="#key{{ $item->id_divisi }}"
                                    class="btn btn-sm btn-warning"><i class="fa fa-key"></i></a> -->
                                <a href="/edtPegawai/{{ $item->id_divisi }}" class="btn btn-sm btn-success"><i
                                        class="fa fa-pencil-square-o"></i></a>
                                <a href="/hpsPegawai/{{ $item->id_divisi }}" class="btn btn-sm btn-danger"><i
                                        class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@foreach ($divisi as $item)
<div class="modal fade" id="key{{ $item->id_divisi }}" tabindex="-1" role="dialog" aria-labelledby="keyLabel"
    aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="keyLabel"><b>Hak Akses</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('konfir', ['id'=>$item->id_divisi]) }}" method="POST">
                @method('put')
                @csrf
                <input type="hidden" name="id_divisi" value="{{ $item->divisi }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kode_divisi" class="form-label">Kode divisi</label>
                        <input type="text" class="form-control" id="kode_divisi" name="kode_divisi"
                            value="{{ $item->kode_divisi }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="nama_divisi" class="form-label">Nama Divisi</label>
                        <input type="hidden" name="lokasi" value="{{ $item->lokasi }}">
                        <input type="text" class="form-control" id="nama_divisi" name="nama_divisi"
                            value="{{ $item->nama_divisi }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Hak Akses</label>
                        <select name="jabatan" id="jabatan" class="form-control" onchange="konfirmasi()">
                            <option value="">Pilih Hak Akses..</option>
                            <option value="Admin">Admin</option>
                            <option value="Staff">Staff</option>
                        </select>
                    </div>
                    <div class="mb-3" id="isi_password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning btn-sm" type="submit">Konfir</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('lihat-barang')
<script type="text/javascript">
    $(document).ready(function () {
        $('#data-pegawai').DataTable();
    });

    function konfirmasi() {
        // console.log('hasil');
        document.getElementById("isi_password").innerHTML = ' <label for="' +
            'password " class="' +
            ' form - label ">Password</label> <' +
            'input type = "password"' +
            'class = "form-control"' +
            'id = "password"' +
            ' name = "password" > ';
    }

</script>
@endsection
