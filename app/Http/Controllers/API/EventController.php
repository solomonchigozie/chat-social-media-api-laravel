<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\EventsModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function addevent(Request $request){
        $request->validate([
            'eventname'=>'required|string',
            'eventdate'=>'required|string',
            'eventtime'=>'required|string',
            'description'=>'required|string',
            'banner'=>'required|mimes:jpg,png,jpeg',
        ]);

        $banner = url('/') .'/uploads/'.time().'.'.$request->banner->extension();
        $request->banner->move(public_path('uploads'), $banner);

        $event = EventsModel::insert([
            'eventname'=>$request->eventname,
            'eventdate'=>$request->eventdate,
            'eventtime'=>$request->eventtime,
            'description'=>$request->description,
            'banner'=>$banner,
            'addedby'=>Auth::user()->id,
            "created_at"=>Carbon::now(),
            "updated_at"=>Carbon::now(),
        ]);

        return response(['data'=>"event added"], 200);
    }

    public function viewevent(Request $request){
        $events = EventsModel::where("status", "active")->latest()->paginate($request->per_page);
        return response(['data'=>$events], 200);
    }

    public function viewmyevents(Request $request){
        $events = EventsModel::where("addedby", Auth::user()->id)
        ->latest()
        ->paginate($request->per_page);
        return response(['data'=>$events], 200);
    }







}
