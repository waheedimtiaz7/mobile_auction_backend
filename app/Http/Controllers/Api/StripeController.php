<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\StripeService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use App\Models\PaymentMethod as UserPaymentMethod;

class StripeController extends Controller
{
    public function createCustomer(Request $request)
    {
        try {
            $customer = Customer::create([
                'name' => $request->input('name'),
                'email' => $request->input('email')
            ]);

            return response()->json(['customer' => $customer, 'message' => 'Customer created successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCustomer($customerId)
    {
        try {
            $customer = Customer::retrieve($customerId);

            return response()->json($customer);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getSavedPaymentMethods($customerId)
    {
        try {
            $paymentMethods = PaymentMethod::all([
                'customer' => $customerId,
                'type' => 'card',
            ]);

            return response()->json($paymentMethods->data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function futureUseIntent()
    {
        $user = User::find(\auth()->user()->id);
        if($user->customer_id==''){
            $stripe = new StripeService();
            $customer = $stripe->createCustomer($user->email);
            User::find($user->id)->update(['customer_id'=>$customer->id]);
            $customerId = $customer->id;
        } else {
            $customerId = $user->customer_id;
        }
        $stripe = new StripeService();
        $setupIntent = $stripe->setupIntent($customerId);
        $clientSecret = $setupIntent->client_secret;

        return response()->json([
            'setupIntent' => $setupIntent,
            'clientSecret' => $clientSecret,
            'success'=>true
        ]);
    }
    public function savePaymentMethod(Request $request)
    {
        try {
            $user = User::find(\auth()->user()->id);
            if($user->customer_id==''){
                $stripe = new StripeService();
                $customer = $stripe->createCustomer($user->email);
                User::find($user->id)->update(['customer_id'=>$customer->id]);
                $customerId = $customer->id;
            } else {
                $customerId = $user->customer_id;
            }
            $stripe = new StripeService();
            $setupIntent = $stripe->savePaymentMethod($customerId, $request->input('paymentMethodId'));
            $userMethods = UserPaymentMethod::whereUserId($user->id)->first();
            if(!$userMethods){
               $isDefault = 1;
            }else if($request['isDefault']){
                UserPaymentMethod::whereUserId($user->id)->update(['is_default' => 0]);
                $isDefault = 1;
            }else{
                $isDefault = 0;
            }

            UserPaymentMethod::create([
                'user_id' => $user->id,
                'method_id' => $request->input('paymentMethodId'),
                'is_default' => $isDefault
            ]);

            $user = User::whereId($user->id)->with(['paymentMethods' => function($q)
            {
                $q->whereIsDefault(1);
            }])->first();
            return response()->json(['message' => 'Payment method saved successfully', 'success' => true, 'user'=>$user]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createPaymentIntent(Request $request)
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->input('amount'),
                'currency' => 'usd',
            ]);

            return response()->json($paymentIntent);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getPaymentMethods()
    {
        try {
            $user = User::find(\auth()->user()->id);
            $stripe = new StripeService();
            $methods = $stripe->getPaymentMethods($user);
            $payment_methods = [];
            if($methods->data){
                foreach ($methods->data as $k=>$method){
                    $payment_method = UserPaymentMethod::where('method_id',$method['id'])->first();
                    if($payment_method){
                        $payment_method['exp_month'] = $method['card']->exp_month;
                        $payment_method['exp_year'] = $method['card']->exp_year;
                        $payment_method['brand'] = $method['card']->brand;
                        $payment_method['last4'] = $method['card']->last4;
                        $payment_methods[] = $payment_method;
                    }


                }
                return response()->json(['methods' => $payment_methods, 'success' => true, 'user'=>$user]);
            } else if(isset($methods['error'])) {
                return response()->json(['error' => true, 'message' => $methods['error']], 500);
            }else{
                return response()->json(['methods' => $payment_methods, 'success' => true, 'user'=>$user]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function setDefaultMethod(Request $request)
    {
        try {
            $user = User::find(\auth()->user()->id);
            UserPaymentMethod::whereUserId($user->id)->update(['is_default' => 0]);
            $user_method = UserPaymentMethod::whereId($request['method_id'])->first();
            if($user_method){
                $user_method->is_default = 1;
                $user_method->save();
            }
            $stripe = new StripeService();
            $methods = $stripe->getPaymentMethods($user);
            $payment_methods = [];
            if($methods->data){
                foreach ($methods->data as $k=>$method){
                    $payment_method = UserPaymentMethod::where('method_id',$method['id'])->first();
                    if($payment_method){
                        $payment_method['exp_month'] = $method['card']->exp_month;
                        $payment_method['exp_year'] = $method['card']->exp_year;
                        $payment_method['brand'] = $method['card']->brand;
                        $payment_method['last4'] = $method['card']->last4;
                        $payment_methods[] = $payment_method;
                    }


                }
                return response()->json(['methods' => $payment_methods, 'success' => true, 'message'=>'Payment set to default']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteMethod(Request $request)
    {
        try {
            $user = User::find(\auth()->user()->id);
            $user_method = UserPaymentMethod::whereId($request['method_id'])->first();

            $stripe = new StripeService();
            $method = $stripe->deleteMethod($user_method->method_id);
            if($method){
                $user_method->delete();
            }else{
                return response()->json(['error' => true, 'message'=>'Something went wrong'], 500);
            }


            $stripe = new StripeService();
            $methods = $stripe->getPaymentMethods($user);
            $payment_methods = [];
            if($methods->data){
                foreach ($methods->data as $k=>$method){
                    $payment_method = UserPaymentMethod::where('method_id',$method['id'])->first();
                    if($payment_method){
                        $payment_method['exp_month'] = $method['card']->exp_month;
                        $payment_method['exp_year'] = $method['card']->exp_year;
                        $payment_method['brand'] = $method['card']->brand;
                        $payment_method['last4'] = $method['card']->last4;
                        $payment_methods[] = $payment_method;
                    }


                }
                return response()->json(['methods' => $payment_methods, 'success' => true, 'message'=>'Payment method deleted']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message'=>$e->getMessage()], 500);
        }
    }
}
