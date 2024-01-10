@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Devices</h1>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        List
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    @if(session()->has('error'))
                        <p class="error text-danger">{{ session()->get('error') }}</p>
                    @endif
                    @if(session()->has('success'))
                        <div class="alert alert-success">
                            <p>{{ session()->get('success') }}</p>
                        </div>
                    @endif
                    <table class="table table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th>Owner Name</th>
                            <th>Device Name</th>
                            <th>OS</th>
                            <th>Price</th>
                            <th>Suggested Price</th>
                            <th>Bids Count</th>
                            <th>Created Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if ($devices->isNotEmpty())
                            @foreach ($devices as $device)
                                <tr>
                                    <td>{{ $device->user->fname. ' '.$device->user->lname }}</td>
                                    <td><a href="{{route('device.detail',$device->id)}}">{{ $device->device_name }}</a></td>
                                    <td>{{ $device->os }}</td>
                                    <td>{{ $device->price }}</td>
                                    <td>{{ $device->suggested_price }}</td>
                                    <td>{{ $device->bids_count }}</td>
                                    <td>{{ date('d M, Y H:i:s',strtotime($device->created_at)) }}</td>
                                    <td>{{ $device->status }}</td>
                                    <td>
                                        <a href="{{ url('admin/device/delete/'.$device->id) }}" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach

                        @else
                            <tr>
                                <td colspan="5">Records Not Found</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $devices->links() }}

                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customjs')

@endsection
