<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PostsModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    public function addposts(Request $request){
        $request->validate([
            'post'=>'required|string',
            'media'=>'mimes:jpg,png,jpeg',
        ]);

        if(!empty($request->media)){
            $media = url('/') .'/uploads/'.time().'.'.$request->media->extension();
            $request->media->move(public_path('uploads'), $media);

            $posts = PostsModel::insert([
                'post'=>$request->post,
                'media'=>$media,
                'addedby'=>Auth::user()->id,
                'userid'=>Auth::user()->id,
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now(),
            ]);
        }else{
            $posts = PostsModel::insert([
                'post'=>$request->post,
                'addedby'=>Auth::user()->id,
                'userid'=>Auth::user()->id,
                "created_at"=>Carbon::now(),
                "updated_at"=>Carbon::now(),
            ]);
        }

        return response(['data'=>"posts added"], 200);
    }

    public function viewposts(Request $request){
        $posts = PostsModel::join('users','posts_models.addedby','=','users.id')
        ->select('posts_models.*','users.firstname  as firstname','users.lastname  as lastname', 'users.picture as picture')
        ->where("status", "active")
        ->latest()
        ->paginate($request->per_page);

        return response(['data'=>$posts], 200);
    }   


    public function viewmyposts(Request $request){
        $posts = PostsModel::where("addedby", Auth::user()->id)
        ->latest()
        ->paginate($request->per_page);
        return response(['data'=>$posts], 200);
    }
}
