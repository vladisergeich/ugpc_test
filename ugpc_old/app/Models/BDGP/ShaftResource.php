<?php

namespace App\Models\BDGP;

use Illuminate\Database\Eloquent\Model;

class ShaftResource extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'batch_date' => 'datetime:d.m.Y',
    ];
}
