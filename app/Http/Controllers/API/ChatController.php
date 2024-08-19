<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ChatModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function startchat(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            // 'message' => 'string',
            'file'=>'mimes:jpg,jpeg,png,docx,pdf,doc',
        ]);

        //double check if receiver exists
        if( User::where('id', $request->receiver_id)->first() == null ){
            return response(['data'=>"User does not exist"], 400);
        }

        //check if user is sending a message to themself
        if( Auth::user()->id == $request->receiver_id ){
            return response(['data'=>"Invalid parameter sent"], 400);
        }

        //check if file and text field is empty
        if($request->file == null && $request->message == null){
            return response(['data'=>"add a message or file to send"], 400);
        }

        //check if an file was sent
        $file ='';
        if($request->file !=null){
            $file = url('/') .'/uploads/'.time().'.'.$request->file->extension();
            $request->file->move(public_path('uploads'), $file);
        }

        //send/create chat
        $chat = ChatModel::create([
            'sender_id' => Auth::user()->id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'file'=>$file
        ]);

        return response(['data'=>$chat], 201);
    }

    public function getsingleconversation(Request $request, $receiver_id)
    {
        $request->user()->id; // Sender ID

        //check if user is fetching message to themself
        if($request->user()->id == $receiver_id){
            return response(['data'=> null], 400);
        }

        $messages = ChatModel::where(function ($query) use ($request, $receiver_id) {
            $query->where('sender_id', $request->user()->id)
                ->where('receiver_id', $receiver_id);
        })
        ->orWhere(function ($query) use ($request, $receiver_id) {
            $query->where('sender_id', $receiver_id)
                ->where('receiver_id', $request->user()->id);
        })
        ->with(['sender', 'receiver'])
        ->get();

        return response(['data'=>$messages], 201);
    }

    public function getallconversations(Request $request)
    {
        $userId = $request->user()->id;

        $conversations = ChatModel::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->get()
            ->groupBy(function ($item) use ($userId) {
                return $item->sender_id === $userId ? $item->receiver_id : $item->sender_id;
            });

        return response()->json($conversations);
    }


}
