<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function login()
    {
        return view ('admin.login');
    }

    public function authenticate(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required',
        ]);
        if($validate->fails()){
            return redirect()->back()->withErrors($validate)
                ->withInput();
        } else{
            if(Auth::attempt(['email' => $request->get('email'), 'password' => $request['password']])){
                return redirect()->route('dashboard');
            }else{
                return redirect()->back()->with('error', 'Invalid username or password');
            }
        }

    }
}
