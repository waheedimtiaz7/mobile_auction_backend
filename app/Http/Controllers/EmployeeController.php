<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request){
        $users = User::latest();
        $users = $users->whereType('Employee')->paginate(10);

        $data['users'] = $users;
        return view('admin.employee.list',$data);


    }

    public function create()
    {
        return view('admin.employee.create');
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'email' => 'required|unique:users',
            'fname' => 'required',
            'lname' => 'required',
            'phone' => 'required',
            'address' => 'required'
        ]);
        if($validate->fails()){
            return redirect()->to('admin/employee/create')->withErrors($validate)
                ->withInput();
        } else {
            User::create([
                'email' => $request['email'],
                'fname' => $request['email'],
                'lname' => $request['lname'],
                'phone' => $request['phone'],
                'address' => $request['address'],
                'password' => Hash::make($request['password']),
                'type' => 'Employee',
                'status' => 1,
            ]);
            return redirect()->to('admin/employees')->with('success','Employee added successfully');
        }
    }

    public function edit($id)
    {
        $employee = User::find($id);
        return view('admin.employee.edit',['employee'=>$employee]);
    }

    public function update(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'email' => [
                'required',
                Rule::unique('users')->ignore($request['employee_id']),
            ],
            'fname' => 'required',
            'lname' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'employee_id' => 'required',
        ]);
        if($validate->fails()){
            return redirect()->to('admin/employee/create')->withErrors($validate)
                ->withInput();
        } else {
            User::whereId($request['employee_id'])->update([
                'email' => $request['email'],
                'fname' => $request['email'],
                'lname' => $request['lname'],
                'phone' => $request['phone'],
                'address' => $request['address'],
                'password' => Hash::make($request['password']),
                'type' => 'Employee',
                'status' => 1,
            ]);
            return redirect()->to('admin/employees')->with('success','Employee updated successfully');
        }
    }
    public function detail($user_id,){
        $user = User::find($user_id);
        $data['user'] = $user;
        return view('admin.employee.detail',$data);
    }

    public function delete($id)
    {
        User::whereId($id)->delete();
        return redirect()->to('admin/employees')->with('success','Employee deleted successfully');
    }
}
