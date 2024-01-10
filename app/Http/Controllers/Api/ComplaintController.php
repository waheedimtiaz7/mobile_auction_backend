<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    //
    public function getEmployeeSideComplaints(): \Illuminate\Http\JsonResponse
    {
        $complaints = Complaint::where(function ($q){
            $q->where('Status', 'New');
            $q->orWhere('employee_id', Auth::user()->id);
        })->with('user')->get();

        return response()->json(['complaints' => $complaints, 'success'=>true], 200);
    }
    public function getUserComplaints(): \Illuminate\Http\JsonResponse
    {
        $complaints = Complaint::whereUserId(Auth::user()->id)->get();

        return response()->json(['complaints' => $complaints, 'success'=>true], 200);
    }

    public function getComplaintDetail(Request $request): \Illuminate\Http\JsonResponse
    {
        $complaint = Complaint::whereId($request['complaint_id'])->with('complaint_replies')->first();

        return response()->json(['complaint' => $complaint, 'success'=>true], 200);
    }

    public function createComplaint(Request $request): \Illuminate\Http\JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email'=> 'required',
            'phone'=> 'required',
            'subject'=> 'required',
            'details'=> '',
        ]);
        if($validate->fails()){
            return response()->json(['error' => true, 'message' => $validate->errors()->first() ], 200);
        }
        $complaints = Complaint::create([
            'user_id' => Auth::user()->id,
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'subject' => $request['subject'],
            'details' => $request['details'],
            'status' => 'New'
        ]);

        return response()->json(['complaints' => $complaints, 'success'=>true], 200);
    }

    public function replyOnComplaint(Request $request): \Illuminate\Http\JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'message' => 'required',
        ]);
        if($validate->fails()){
            return response()->json(['error' => true, 'message' => $validate->errors()->first() ], 200);
        }
        $complaint = Complaint::find($request['complaint_id']);
        if(Auth::user()->type=='Employee'){
            $receiver_id = $complaint->user_id;
            if($complaint->status == 'New'){
                $complaint->status = 'Assigned';
                $complaint->employee_id = Auth::user()->id;
            }
        } else {
            $receiver_id = $complaint->employee_id;
        }
        $complaints = ComplaintReply::create([
            'sender_id' => Auth::user()->id,
            'complaint_id' => $request['complaint_id'],
            'receiver_id' => $receiver_id,
            'message' => $request['message']
        ]);

        $complaint = Complaint::whereId($request['id'])->with('complaint_replies')->first();

        return response()->json(['complaint' => $complaint, 'success'=>true], 200);
    }

    public function deleteComplaint(Request $request): \Illuminate\Http\JsonResponse
    {
        $complaint = Complaint::find($request['complaint_id']);
        $complaint->delete();
        $complaints = Complaint::whereUserId(Auth::user()->id)->get();
        return response()->json(['message' => 'Complaint deleted successfully', 'complaints' => $complaints, 'success' => true], 200);
    }
}
