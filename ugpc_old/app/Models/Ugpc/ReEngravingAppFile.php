<?php

namespace App\Models\Ugpc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReEngravingAppFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'path',
    ];
}
