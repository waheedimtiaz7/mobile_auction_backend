@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Employees</h1>
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
                <div class="card-tools">
                    <a href="{{ route('employees.create') }}" class="btn btn-primary">Add Employee</a>
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
                                <th width="60">UserID#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th width="100">Address</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($users->isNotEmpty())
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td><a href="{{route('employees.detail',$user->id)}}">{{ $user->fname }} {{ $user->lname }}</a></td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->address }}</td>
                                        <td><a href="{{ url('admin/employee/edit/'.$user->id) }}"><i class="fa fa-edit"></i></a>
                                        <a href="{{ url('admin/employee/delete/'.$user->id) }}" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash"></i></a>
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
                    {{ $users->links() }}

                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customjs')

@endsection
