<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostsModel extends Model
{
    use HasFactory;
    protected $fillable = [
        'post','userid','media','addedby','status'
    ];


    protected $hidden = ['addedby'];
}
