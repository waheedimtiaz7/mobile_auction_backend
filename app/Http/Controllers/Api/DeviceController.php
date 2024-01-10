<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\StripeService;
use App\Models\Bid;
use App\Models\Device;
use App\Models\DeviceImage;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DeviceController extends Controller
{
    //
    public function index()
    {
        $devices = Device::select('*')->whereIn('status',['Available'])
            ->with(['bids','highestBid', 'latestBid'])
            ->orderBy('devices.id', 'desc')->groupBy('devices.id')->get();
        return response()->json(['devices' => $devices, 'success'=>true], 200);
    }

    public function getMyDevices()
    {
        $devices = Device::select('*')->where('user_id', Auth::user()->id)
            ->with(['bids','highestBid', 'latestBid'])
            ->withCount(['user as is_owner'=> function ($q){
                $q->whereUserId(Auth::user()->id);
            }])->orderBy('devices.id', 'desc')->groupBy('devices.id')->get();
        return response()->json(['devices' => $devices, 'success'=>true], 200);
    }

    public function create(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'device_name' => 'required',
            'model' => 'required',
            'os' => 'required',
            'ui' => 'required',
            'dimensions' => 'required',
            'weight' => 'required',
            'color' => 'required',
            'sim' => 'required',
            'cpu' => 'required',
            'gpu' => 'required',
            'size' => 'required',
            'resolution' => 'required',
            'ram' => 'required',
            'rom' => 'required',
            'sdcard' => 'required',
            'bluetooth' => 'required',
            'wifi' => 'required',
            'battery' => 'required',
            'price' => 'required',
            'status'  => 'required'
        ]);
        if($validate->fails()){
            return response()->json(['error' =>true,'message'=>$validate->errors()->first() ], 200);
        }
        try {

            $request['user_id'] = Auth::user()->id;
            $device = Device::create($request->all());
            if($request->hasFile('image')){
                $files = $request->file('image');
                foreach ($files as $k=>$file){
                    $name = 'image'.time().$k.'.'.$file->getClientOriginalExtension();
                    $file->move('uploads/'.$device->id.'/', $name);
                    DeviceImage::create([
                        'device_id'=> $device->id,
                        'image_path' => url('/').'/uploads/'.$device->id.'/'.$name
                    ]);
                    if($k==0){
                        Device::whereId($device->id)->update(['picture'=>url('/').'/uploads/'.$device->id.'/'.$name]);
                    }
                }

            }
            $device = Device::whereId($device->id)->with('deviceImages')->first();
            return response()->json(['device' => $device, 'success'=>true], 200);
        }catch (\Exception $exception){
            return response()->json(['error' =>true,'message'=>$exception->getMessage() ], 200);
        }

    }

    public function update(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'device_name' => 'required',
            'model' => 'required',
            'os' => 'required',
            'ui' => 'required',
            'dimensions' => 'required',
            'weight' => 'required',
            'color' => 'required',
            'sim' => 'required',
            'cpu' => 'required',
            'gpu' => 'required',
            'size' => 'required',
            'resolution' => 'required',
            'ram' => 'required',
            'rom' => 'required',
            'sdcard' => 'required',
            'bluetooth' => 'required',
            'wifi' => 'required',
            'battery' => 'required',
            'price' => 'required',
        ]);
        if($validate->fails()){
            return response()->json(['error' =>true,'message'=>$validate->errors()->first() ], 200);
        }
        try {

            $request['user_id'] = Auth::user()->id;
            $device = Device::find($request['device_id'])->fill($request->all())->save();
            if($request->hasFile('image')){
                $files = $request->file('image');
                foreach ($files as $k=>$file){
                    $name = 'image'.time().$k.'.'.$file->getClientOriginalExtension();
                    $file->move('uploads/'.$device->id.'/', $name);
                    DeviceImage::create([
                        'device_id'=> $device->id,
                        'image_path' => url('/').'/uploads/'.$device->id.'/'.$name
                    ]);
                    if($k==0){
                        Device::whereId($device->id)->update(['picture'=>url('/').'/uploads/'.$device->id.'/'.$name]);
                    }
                }

            }
            $device = Device::whereId($request['device_id'])->with('deviceImages')->first();
            return response()->json(['device' => $device, 'success'=>true], 200);
        }catch (\Exception $exception){
            return response()->json(['error' =>true,'message'=>$exception->getMessage() ], 200);
        }

    }
    public function show($device_id)
    {
        $device = Device::whereId($device_id)->with(['bids.user','highestBid.user', 'latestBid.user'])
            ->withCount(['user as is_owner'=> function ($q){
                $q->whereUserId(Auth::user()->id);
            }])->first();
        return response()->json(['device' => $device, 'success'=>true], 200);
    }

    public function delete($device_id)
    {
        $device = Device::find($device_id)->delete();
        return response()->json(['success'=>true], 200);
    }

    public function edit($device_id)
    {
        $device = Device::find($device_id)->delete();
        return response()->json(['success'=>true], 200);
    }

    public function createNewBid(Request $request)
    {
        $device = Device::whereIn('status',['Available'])
            ->with(['bids','highestBid', 'latestBid'])->whereId($request['device_id'])->first();
        if($device){
            $bid = Bid::create([
                'bidder_id' => Auth::user()->id,
                'device_id' => $request['device_id'],
                'bid_amount' => $request['bid_amount'],
                'status' => 'Pending'
            ]);
            $device = Device::whereIn('status',['Available'])
                ->with(['bids','highestBid', 'latestBid'])->whereId($request['device_id'])->first();
            return response()->json(['device' => $device, 'success'=>true], 200);
        }else{
            return response()->json(['message' => 'Device is not available for bid', 'error' => true], 200);
        }

    }

    public function acceptBid(Request $request)
    {
        try {
            $bid = Bid::whereId($request['bid_id'])->with(['device','user','user.defaultPaymentMethod'])->first();
            $stripe = new StripeService();
            $charge = $stripe->createCharge($bid);
            if(!isset($request['error'])){
                Payment::create([
                    'bid_id'=>$bid->id,
                    'amount'=>$bid->bid_amount,
                    'paymentId'=> $charge->id,
                ]);

                $bid->status = 'Accepted';
                $bid->save();

                $device = Device::find($bid->device_id);
                $device->status = 'Sold';
                $device->bidder_id = $bid->user_id;
                $device->save();

                return response()->json(['device' => $device, 'success'=>true ], 200);
            } else {

                $bid->status = 'Payment Failed';
                $bid->save();

                return response()->json(['error' => true, 'message'=> $charge['error']], 200);
            }
        }catch (\Exception $exception){
            return response()->json(['error' => true, 'message'=> $exception->getMessage()], 200);

        }

    }

    public function getOngoingAuctionDevices()
    {
        $devices = Device::select('*')->whereIn('devices.status',['Available'])
            ->with(['bids','highestBid', 'latestBid', 'latestBid.user', 'user'])
            ->whereHas('bids', function ($q){
                $q->whereIn('bids.status', ['Pending']);
            })->get();
        return response()->json(['devices' => $devices, 'success'=>true], 200);
    }


    public function getNewDevices()
    {
        $devices = Device::select('*')->whereIn('status',['Pending'])->with('user')
            ->get();
        return response()->json(['devices' => $devices, 'success'=>true], 200);
    }

    public function getSoldDevices()
    {
        $devices = Device::select('*')->whereIn('devices.status',['Sold','In Transit', 'Received By Buyer'])
            ->with(['user','bidder', 'acceptedBid'])
            ->orderBy('devices.id', 'desc')
            ->groupBy('devices.id')->get();
        return response()->json(['devices' => $devices, 'success'=>true], 200);
    }
    public function getAllBidDevices()
    {
        $devices = Device::select('*')->whereIn('devices.status',['Available','Sold','In Transit', 'Received By Buyer'])
            ->with(['bids','highestBid', 'latestBid',  'latestBid.user', 'user','bidder', 'acceptedBid','acceptedBid.user'])
            ->orderBy('devices.id', 'desc')
            ->whereHas('bids', function ($q){
                $q->whereIn('bids.status', ['Pending', 'Accepted']);
            })->groupBy('devices.id')->get();
        return response()->json(['devices' => $devices, 'success'=>true], 200);
    }

    public function getActivePendingDevices()
    {
        $devices = Device::select('*')->whereIn('devices.status',['Pending','Available'])
            ->with(['bids','highestBid', 'latestBid',  'latestBid.user', 'user','bidder'])
            ->orderBy('devices.id', 'desc')->groupBy('devices.id')->get();
        return response()->json(['devices' => $devices, 'success'=>true], 200);
    }

    public function updateDevicesStatus(Request $request)
    {
        $device = Device::find($request['device_id']);
        if($device){
            $device->status = $request['status'];
            $device->suggest_price = $request['suggest_price'];
            $device->save();
            return response()->json(['message' => 'Status updated successfully', 'success'=>true], 200);
        }else{
            return response()->json(['message' => 'Device not available', 'error'=>true], 200);
        }
    }
}
