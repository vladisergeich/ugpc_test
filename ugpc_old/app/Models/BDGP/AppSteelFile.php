<?php

namespace App\Models\BDGP;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSteelFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'path',
    ];
}
