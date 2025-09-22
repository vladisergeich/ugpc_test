<?php

namespace App\Models\Ugpc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shaftState extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    
    protected $casts = [
        'data' => 'array',
    ];
}
