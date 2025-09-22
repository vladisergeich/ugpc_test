<?php

namespace App\Models\Ugpc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShiftCode extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'datetime:d.m.Y',
    ];
}
