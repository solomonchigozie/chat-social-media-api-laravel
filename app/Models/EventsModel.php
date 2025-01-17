<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventsModel extends Model
{
    use HasFactory;
    protected $fillable = [
        'eventdate','eventname','eventtime','description','banner','addedby','status'
    ];

    protected $hidden = ['addedby'];
}
