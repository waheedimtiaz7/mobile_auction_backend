<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request){
        $users = User::latest();
        if (!empty($request->get('keyword'))) {
            $users = $users->where('fname', 'like', '%' . $request->get('keyword') . '%');
        }
        $users = $users->whereType('Customer')->paginate(10);

        $data['users'] = $users;
        return view('admin.users.list',$data);


    }

    public function detail($user_id,){
        $user = User::find($user_id);
        $data['user'] = $user;
        return view('admin.users.detail',$data);
    }
}
