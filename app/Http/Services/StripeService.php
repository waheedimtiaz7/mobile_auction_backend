<?php

namespace App\Http\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\SetupIntent;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentMethod;
use Stripe\PaymentIntent;
use Stripe\Charge;

class StripeService{

    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.stripe.secret');
        Stripe::setApiKey($this->apiKey);
    }

    public function createCustomer($email)
    {
        try {
            $customer = Customer::create([
                'email' => $email
            ]);

            return $customer;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getCustomer($customerId)
    {
        try {
            $customer = Customer::retrieve($customerId);
            return response()->json($customer);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getSavedPaymentMethods($customerId)
    {
        try {
            $paymentMethods = PaymentMethod::all([
                'customer' => $customerId,
                'type' => 'card',
            ]);

            return $paymentMethods;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function setupIntent($customerId)
    {
        try {
            $setupIntent = SetupIntent::create([
                'customer' => $customerId
            ]);
            return $setupIntent;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function savePaymentMethod($customerId, $paymentMethodId)
    {
        try {
            $customer = Customer::update($customerId, [
                'payment_method' => $paymentMethodId
            ]);
           return $customer;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getPaymentMethods($user)
    {
        try {
            $methods = PaymentMethod::all(['customer'=>$user->customer_id]);

            return $methods;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function deleteMethod($method_id)
    {
        try {
            $method = PaymentMethod::retrieve($method_id)->detach();

            if($method->id){
                return true;
            }else{
                print_r($method);exit;
            }
        } catch (\Exception $e) {
            print_r($e->getMessage());exit;
            return ['error' => $e->getMessage()];
        }
    }

    public function createCharge($bid)
    {
        try {
           $intent =  PaymentIntent::create([
                'customer' => $bid->user->customer_id,
                'amount' => $bid->bid_amount*100,
                'currency' => 'usd',
                'automatic_payment_methods' => ['enabled' => true, 'allow_redirects' => 'never'],
                'confirm' => true,
                'payment_method' => $bid->user->defaultPaymentMethod->method_id,
            ]);

           return $intent;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
