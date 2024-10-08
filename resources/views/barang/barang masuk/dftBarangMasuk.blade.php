@extends('layout.main')

@section('barang_masuk', 'active')

@section('content')

<style>
    div.dataTables_wrapper {
        width: 980px;
        margin: 0 auto;
    }

</style>


<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong class="card-title">Daftar Barang Masuk</strong>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="bootstrap-data-table" class="table table-striped table-bordered display nowrap" data-ordering="false">
                        <thead>
                            <tr>
                                <th>Kode Kategori</th>
                                <th>Gudang</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Tanggal Masuk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barang_masuk as $item)
                            <tr>
                                <td>{{ $item->kategori->kategori }}</td>
                                <td>{{ $item->pemasok->nama }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ number_format($item->jumlah) }} {{ $item->satuan }}</td>
                                <td>{{ date('d F Y', strtotime($item->tanggal)) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('table')
<script type="text/javascript">
    $(document).ready(function () {
        $('#bootstrap-data-table-export').DataTable();
    });

</script>
@endsection
