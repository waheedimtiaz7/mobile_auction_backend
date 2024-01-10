<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    //
    public function index()
    {
        $devices = Device::latest();
        $devices = $devices->withCount('bids')->paginate(10);

        $data['devices'] = $devices;
        return view('admin.devices.list',$data);
    }

    public function detail($id)
    {
        $device = Device::whereId($id)->with(['user','bids','bids.user','bidder'])->first();
        return view('admin.devices.detail',['device'=>$device]);
    }

    public function updateStatus(Request $request)
    {
        Device::whereId($request['device_id'])->update(['status'=>$request['status']]);
        return redirect()->back()->with('success','Device status updated successfully');
    }
}
