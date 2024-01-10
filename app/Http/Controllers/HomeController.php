<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(){

        return view('admin.dashboard');
    }
    public function logout() {
        Auth::logout();
        return redirect()->route('admin.login');
       }
}
