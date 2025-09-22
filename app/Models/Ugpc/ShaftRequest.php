<?php

namespace App\Models\Ugpc;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class ShaftRequest extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime:d.m.Y',
    ];
    

}


