@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Employee</h1>
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
                        Create
                    </div>
                </div>
                <form method="post" action="{{ route('employees.store') }}">
                    <div class="card-body table-responsive">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fname">First Name</label>
                                    <input type="text" class="form-control" id="fname" name="fname" placeholder="First name" value="{{ old('fname') }}">
                                    @error('fname')
                                    <p class="d-block invalid-feedback">{{ $message }} </p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">Email address</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="{{ old('email') }}">
                                    @error('email')
                                    <p class="d-block invalid-feedback">{{ $message }} </p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone number" value="{{ old('phone') }}">
                                    @error('phone')
                                    <p class="d-block invalid-feedback">{{ $message }} </p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lname">Last Name</label>
                                    <input type="text" class="form-control" id="lname" name="lname" placeholder="Last name" value="{{ old('lname') }}">
                                    @error('lname')
                                    <p class="d-block invalid-feedback">{{ $message }} </p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" placeholder="Password">
                                    @error('password')
                                    <p class="d-block invalid-feedback">{{ $message }} </p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="{{ old('address') }}">
                                    @error('address')
                                    <p class="d-block invalid-feedback">{{ $message }} </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                    @csrf
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            </div>

        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection
