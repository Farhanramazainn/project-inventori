@extends('layout.main')

@section('dashboard', 'active')

@section('content')

<div class="row">
    <!-- Card untuk Total Barang -->
    <div class="col-md-6 col-lg">
        <div class="card card-custom rounded">
            <div class="p-0 clearfix">
                <i class="fa fa-briefcase bg-custom p-4 font-2xl ml-3 mr-3 mt-3 float-left text-success icon-large"></i>
                <div class="h5 text-danger m-0 mt-1 mb-1 pt-3">Total Barang</div>
                <div class="h3 text-light text-uppercase font-weight font-xs">{{ $barang }}</div>
            </div>
        </div>
    </div>
    <!-- Card untuk Barang Masuk -->
    <div class="col-md-6 col-lg">
        <div class="card card-custom rounded">
            <div class="p-0 clearfix">
                <i class="fa fa-shopping-cart bg-custom p-4 font-2xl ml-3 mr-3 mt-3 float-left text-success icon-large"></i>
                <div class="h5 text-danger m-0 mt-1 mb-1 pt-3">Barang Masuk</div>
                <div class="h3 text-light text-uppercase font-weight font-xs">{{ $barang_masuk }}</div>
            </div>
        </div>
    </div>
    <!-- Card untuk Barang Keluar -->
    <div class="col-md-6 col-lg">
        <div class="card card-custom rounded">
            <div class="p-0 clearfix">
                <i class="fa fa-dropbox bg-custom p-4 font-2xl ml-3 mr-3 mt-3 float-left text-success icon-large"></i>
                <div class="h5 text-danger m-0 mt-1 mb-1 pt-3">Barang Keluar</div>
                <div class="h3 text-light text-uppercase font-weight font-xs">{{ $barang_keluar }}</div>
            </div>
        </div>
    </div>
    <!-- Card untuk Total Approval -->
    <div class="col-md-6 col-lg">
        <div class="card card-custom rounded">
            <div class="p-0 clearfix">
                <i class="fa fa-check-circle bg-custom p-4 font-2xl ml-3 mr-3 mt-3 float-left text-success icon-large"></i>
                <div class="h5 text-danger m-0 mt-1 mb-1 pt-3">Total Approval</div>
                <div class="h3 text-light text-uppercase font-weight font-xs">{{ $total_approval }}</div>
            </div>
        </div>
    </div>

    <!-- Grafik Barang Masuk dan Barang Keluar -->
    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Grafik Barang Masuk</h5>
                <canvas id="barangChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Grafik Barang Keluar</h5>
                <canvas id="barang_office"></canvas>
            </div>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
    // Data dari controller
    var data_masuk = @json($data_masuk);
    var data_keluar = @json($data_keluar);
    var data_masuk_pemasok = @json($jumlah_masuk_dari_pemasok);
    var data_masuk_office = @json($jumlah_masuk_dari_office);

    var data_masuk_office_front = @json($jumlah_keluar_dari_office_front);
    var data_masuk_office_back = @json($jumlah_keluar_dari_office_back);

    console.log(data_masuk_office_back);
    
    // Format data untuk Chart.js
    var labelsMasuk = data_masuk_pemasok.map(function(item) { return item.date; });
    var valuesMasuk = data_masuk_pemasok.map(function(item) { return parseInt(item.total); });

    
    var labelsMasukOffice = data_masuk_office_front.map(function(item) { return item.date; });
    var valuesMasukOffice = data_masuk_office_front.map(function(item) { return parseInt(item.total); });


    var labelsKeluar = data_keluar.map(function(item) { return item.date; });
    var valuesKeluar = data_keluar.map(function(item) { return item.count; });

    var labelsKeluarOffice = data_masuk_office_back.map(function(item) { return item.date; });
    var valuesKeluarOffice = data_masuk_office_back.map(function(item) { return parseInt(item.total); });


    // Gabungkan data tanggal untuk memastikan grafik berfungsi dengan baik
    var allDates = [...new Set([...labelsMasuk, ...labelsKeluar])].sort();
    var allDatesOffice = [...new Set([...labelsMasukOffice, ...labelsKeluarOffice])].sort();
    
    // Mengisi nilai untuk data_barang_masuk dan data_barang_keluar pada tanggal yang sama
    var valuesMasukMap = {};
    var valuesKeluarMap = {};

    var valuesMasukMapOffice = {};
    var valuesKeluarMapOffice = {};

    labelsMasuk.forEach((date, index) => {
        valuesMasukMap[date] = valuesMasuk[index];
    });

    labelsKeluar.forEach((date, index) => {
        valuesKeluarMap[date] = valuesKeluar[index];
    });


    labelsMasukOffice.forEach((date, index) => {
        valuesMasukOffice[date] = valuesMasukOffice[index];
    });

    labelsKeluar.forEach((date, index) => {
        valuesKeluarMap[date] = valuesKeluar[index];
    });

    labelsKeluarOffice.forEach((date, index) => {
        valuesKeluarOffice[date] = valuesKeluarOffice[index];
    });

    var valuesMasukFinal = allDates.map(date => valuesMasukMap[date] || 0);
    var valuesKeluarFinal = allDates.map(date => valuesKeluarMap[date] || 0);

    var valuesMasukOffice = allDatesOffice.map(date => valuesMasukOffice[date] || 0);
    var valuesKeluarOffice = allDatesOffice.map(date => valuesKeluarOffice[date] || 0);

    // Membuat grafik dengan Chart.js
    var ctx = document.getElementById('barangChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: allDates,
            datasets: [{
                label: 'Jumlah Barang Masuk Manufacture',
                data: valuesMasukFinal,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: false,
                tension: 0.1
            }, {
                label: 'Jumlah Barang Masuk Office',
                data: valuesMasukOffice,
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: false,
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += context.parsed.y;
                            }
                            return label;
                        }
                    }
                },
                datalabels: {
                    color: '#444',
                    anchor: 'end',
                    align: 'top',
                    formatter: function(value, context) {
                        return value;
                    }
                }
            },
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'day',
                        tooltipFormat: 'DD MMM YYYY'
                    },
                    title: {
                        display: true,
                        text: 'Tanggal'
                    },
                    grid: {
                        display: true,
                        drawBorder: false,
                        color: 'rgba(0, 0, 0, 0.1)',
                        lineWidth: 1
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Jumlah'
                    },
                    grid: {
                        borderDash: [5, 5],
                        color: 'rgba(0, 0, 0, 0.1)',
                        lineWidth: 1
                    }
                }
            }
        }
    });




    var ctx = document.getElementById('barang_office').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: allDates,
            datasets: [{
                label: 'Jumlah Barang Keluar Back Office',
                data: valuesMasukOffice,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: false,
                tension: 0.1
            }, 
            {
                label: 'Jumlah Barang Keluar Front Office',
                data: valuesKeluarOffice,
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: false,
                tension: 0.1
            }
        ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += context.parsed.y;
                            }
                            return label;
                        }
                    }
                },
                datalabels: {
                    color: '#444',
                    anchor: 'end',
                    align: 'top',
                    formatter: function(value, context) {
                        return value;
                    }
                }
            },
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'day',
                        tooltipFormat: 'DD MMM YYYY'
                    },
                    title: {
                        display: true,
                        text: 'Tanggal'
                    },
                    grid: {
                        display: true,
                        drawBorder: false,
                        color: 'rgba(0, 0, 0, 0.1)',
                        lineWidth: 1
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Jumlah'
                    },
                    grid: {
                        borderDash: [5, 5],
                        color: 'rgba(0, 0, 0, 0.1)',
                        lineWidth: 1
                    }
                }
            }
        }
    });
</script>

@endsection
