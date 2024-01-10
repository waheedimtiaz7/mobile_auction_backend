@extends('admin.layouts.app')
@section('content')

    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6 ">
                    <h1>Device: #{{ $device->id }}</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('devices.index')}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            @if(session()->has('error'))
                <p class="error text-danger">{{ session()->get('error') }}</p>
            @endif
            @if(session()->has('success'))
                <div class="alert alert-success">
                    <p>{{ session()->get('success') }}</p>
                </div>
            @endif
            <div class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header pt-3">
                            <div class="row invoice-info justify-content-center text-center">
                                <div class="col-sm-4 invoice-col text-center">
                                    <h1 class="h5 mb-3"><strong> Device Detail</strong></h1>

                                    <div class="profile">
                                        <img src="{{ asset($device->picture)}}" alt="logo" class="brand-image img-circle elevation-4" style="height: 5cm "><br>
                                        <br><span class="brand-text font-weight-dark "><strong>{{ $device->name}}</strong></span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-3">

                            <table class="table table-striped">
                                <tbody>
                                <tr>
                                    <td><strong>Seller Name</strong></td>
                                    <td>{{ $device->user->fname }} {{ $device->user->lname }}</td>
                                </tr>
                                <tr>
                                    <td><strong>OS</strong></td>
                                    <td>{{ $device->os }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Ui</strong></td>
                                    <td>{{ $device->ui }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Weight:</strong></td>
                                    <td>{{ $device->weight }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Size:</strong></td>
                                    <td>{{ $device->size }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Color:</strong></td>
                                    <td>{{ $device->color }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Sim:</strong></td>
                                    <td>{{ $device->sim }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Cpu:</strong></td>
                                    <td>{{ $device->cpu }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Gpu:</strong></td>
                                    <td>{{ $device->gpu }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Resolution:</strong></td>
                                    <td>{{ $device->resolution }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Ram:</strong></td>
                                    <td>{{ $device->ram }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Rom:</strong></td>
                                    <td>{{ $device->rom }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Sdcard:</strong></td>
                                    <td>{{ $device->sdcard }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Bluetooth:</strong></td>
                                    <td>{{ $device->bluetooth }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Wifi:</strong></td>
                                    <td>{{ $device->wifi }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Price:</strong></td>
                                    <td>{{ $device->price }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Battery:</strong></td>
                                    <td>{{ $device->battery }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Suggest Price:</strong></td>
                                    <td>{{ $device->suggest_price }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>{{ $device->status }}</td>
                                </tr>



                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="{{ route('device.status_update') }}">
                                <input name="device_id" type="hidden" value="{{ $device->id }}">
                            <h2 class="h4 mb-3">Device Status</h2>
                            <div class="mb-3">
                                <select name="status" id="status" class="form-control">
                                    <option {{ ($device->status == 'Pending') ? 'selected' : ''}} value="Pending">Pending</option>
                                    <option {{ ($device->status == 'Available') ? 'selected' : ''}} value="Available">Available</option>
                                    <option {{ ($device->status == 'Sold') ? 'selected' : ''}} value="Sold">Sold</option>
                                    <option {{ ($device->status == 'Rejected') ? 'selected' : ''}} value="Rejected">Rejected</option>
                                    <option {{ ($device->status == 'Closed') ? 'selected' : ''}} value="Closed">Closed</option>
                                </select>
                            </div>
                                @csrf
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Bids</h2>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Bidder Name</th>
                                        <th>Bid Price</th>
                                        <th>Bid Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($device->bids as $bid)
                                    <tr>
                                        <th>{{ $bid->user->fname.' '.$bid->user->lname }}</th>
                                        <th>{{ $bid->bid_amount }}</th>
                                        <th>{{ $bid->status }}</th>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>


@endsection
