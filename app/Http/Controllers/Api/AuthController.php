<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\StripeService;
use App\Models\Complaint;
use App\Models\ComplaintReply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:300',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6'
        ]);
        if($validate->fails()){
            return response()->json(['error' => true, 'message' => $validate->errors()->first() ], 200);
        }
        $stripe = new StripeService();
        $customer = $stripe->createCustomer($request['email']);
        if(isset($customer['error'])){
            return response()->json(['error' => true, 'message' => $customer['error'], 'success'=>false ], 200);
        }
        $user = User::create([
            'email' => $request->email,
            'device_token' => $request->device_token,
            'password' => Hash::make($request->password),
            'fname' => $request->first_name,
            'lname' => $request->last_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'type' => 'Customer',
            'status' => 1,
            'customer_id' => $customer->id,
        ]);
        $token = $user->createToken('MyApp')->accessToken;
        $user = User::whereId($user->id)->with(['paymentMethods' => function($q)
        {
            $q->whereIsDefault(1);
        }])->first();
        return response()->json(['token' => $token, 'user'=>$user ], 200);
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
        if($validate->fails()){
            return response()->json(['error' => true, 'message' => $validate->errors()->first() ], 200);
        }
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = User::whereId(Auth::user()->id)->with(['paymentMethods' => function($q)
            {
                $q->whereIsDefault(1);
            }])->first();
            $token = $user->createToken('MyApp')->accessToken;
            return response()->json(['token' => $token, 'user'=> $user, 'success'=>true], 200);
        }

        return response()->json(['error' => true, 'message' => 'Invalid email or password'], 200);
    }

    public function update(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'required|string|email|max:255',
        ]);
        if($validate->fails()){
            return response()->json(['error' => true, 'message' => $validate->errors()->first() ], 200);
        }
        try {
            User::whereId(auth()->user()->id)->update([
                    'email' => $request['email'],
                    'fname' => $request['fname'],
                    'lname' => $request['lname'],
                    'phone' => $request['phone'],
                    'address' => $request['address']
                ]);
            if($request->hasFile('image')){
                $file = $request->file('image');
                $name = 'image'.time().'.'.$file->getClientOriginalExtension();
                $file->move('uploads/profile/'.auth()->user()->id.'/', $name);
                $user = User::find(auth()->user()->id);
                $user->picture = url('/').'/uploads/profile/'.auth()->user()->id.'/'.$name;
                $user->save();
            }
            $user = User::find(auth()->user()->id);
            return response()->json(['success' => true, 'message' => 'Profile updated', 'user'=>$user], 200);
        }catch (\Exception $exception){
            return response()->json(['error' => true, 'message' => $exception->getMessage()], 200);
        }

    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();

        return response()->json(['message' => 'Successfully logged out']);
    }



}
