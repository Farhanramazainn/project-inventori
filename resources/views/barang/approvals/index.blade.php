@extends('layout.main')

@section('approval', 'active')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong class="card-title">Daftar Request Barang Keluar</strong>
            </div>
            <div class="card-body">
                <table id="bootstrap-data-table" class="table table-striped table-bordered" data-ordering="false">
                    <thead>
                        <tr>
                            <th>Kode Barang</th>
                            <th>Nama Divisi</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($approvals as $item)
                        <tr>
                            <td>{{ $item->kode_bk }}</td>
                            <td>{{ $item->divisi->nama_divisi }}</td>
                            <td>{{ $item->barang->nama }}</td>
                            <td>{{ $item->jumlah }} {{ $item->satuan }}</td>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td>
                                @if ($item->status === 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif ($item->status === 'approved')
                                    <span class="badge badge-success">Approved</span>
                                @else
                                    <span class="badge badge-danger">Rejected</span>
                                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#approvalDetailsModal{{ $item->id }}">
                                        <i class="fa fa-info-circle"></i> Details
                                    </button>

                                    <!-- Modal untuk menampilkan keterangan approval -->
                                    <div class="modal fade" id="approvalDetailsModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="approvalDetailsModalLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="approvalDetailsModalLabel{{ $item->id }}">Approval Details</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Rejection Reason:</strong> {{ $item->keterangan_approval }}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if ($item->status === 'pending')
                                    <form action="{{ route('approvals.approve', $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to approve this request?')">
                                            <i class="fa fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#rejectModal{{ $item->id }}">
                                        <i class="fa fa-times"></i> Reject
                                    </button>

                                    <!-- Modal untuk keterangan penolakan -->
                                    <div class="modal fade" id="rejectModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="rejectModalLabel{{ $item->id }}">Reject Request</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('approvals.reject', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="keterangan_approval">Rejection Reason</label>
                                                            <textarea id="keterangan_approval" name="keterangan_approval" class="form-control" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-danger">Reject</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($item->status !== 'pending')
                                    <form action="{{ route('approvals.destroy', $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this request?')">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('table')
<script type="text/javascript">
    $(document).ready(function () {
        $('#bootstrap-data-table').DataTable();
    });
</script>
@endsection