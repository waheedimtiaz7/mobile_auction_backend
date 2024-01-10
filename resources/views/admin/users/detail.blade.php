@extends('admin.layouts.app')
@section('content')

<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6 ">
                <h1>User: #{{ $user->id }}</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('users.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header pt-3">
                        <div class="row invoice-info justify-content-center text-center">
                            <div class="col-sm-4 invoice-col text-center">
                            <h1 class="h5 mb-3"><strong> User Detail</strong></h1>

                                <div class="profile">
                                    <img src="{{ asset('admin-assets/img/logo.png')}}" alt="logo" class="brand-image img-circle elevation-4" style="height: 5cm "><br><br>
                                    <br><span class="brand-text font-weight-dark "><strong>{{ $user->fname}} {{ $user->lname}}</strong></span>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-3">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td><strong>User Name</strong></td>
                                    <td>{{ $user->fname }} {{ $user->lname }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone Number</strong></td>
                                    <td>{{ $user->phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Address:</strong></td>
                                    <td>{{ $user->address }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    @if ($user->status =='1')
                                    <td><span class="badge bg-success">Active</span></td>
                                    @else
                                    <td><span class="badge bg-danger">Blocked</span></td>

                                    @endif
                                </tr>



                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h2 class="h4 mb-3">User Status</h2>
                        <div class="mb-3">
                            <select name="status" id="status" class="form-control">
                                <option {{ ($user->status == 1) ? 'selected' : ''}} value="1">Active</option>
                                <option {{ ($user->status == 0) ? 'selected' : ''}} value="0">Blocked</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>


@endsection
