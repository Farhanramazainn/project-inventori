<!DOCTYPE html>
<html>

<head>
    <title>Laporan Rekapitulasi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <style type="text/css">
        table tr td {
            font-size: 9pt;
        }

        table thead tr th {
            text-align: center;
            font-size: 11pt;
        }

        .total th {
            font-size: 11pt;
            color: red;
        }

        hr {
            margin-top: 1px;
            margin-bottom: 30px;
            border: 2px;
            color: rgb(4, 79, 102);
        }

        img {
            height: 100px;
            width: 100px;
        }

    </style>

    <center>
        <img src="images/kantor.png" alt="">
        <h5>Inventori Barang Kantor Grapari Telkomsel
            <br>Laporan Barang Masuk</h4><br>
            <h6>Tanggal : {{ date('d-M-Y', strtotiMe($dari)) }} -
                {{ date('d-M-Y', strtotime($sampai)) }}
            </h5>
        </center>
        <hr>

        <table class='table table-bordered'>
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Barang Masuk</th>
                    <th>Supplier</th>
                    <th>Tanggal Masuk</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data_masuk as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->kode_bm }}</td>
                    <td>{{ $item->pemasok->nama }}</td>
                    <td>{{ date('d F Y', strtotime($item->tanggal)) }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ number_format($item->jumlah) }}{{ $item->satuan }}</td>
                </tr>
                @endforeach
            
            </tbody>
        </table>

    </body>

    </html>
