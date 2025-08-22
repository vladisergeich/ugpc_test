<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilePrintProcessColor extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'inhibitor' => 'array',
        'optical_density' => 'array',
        'raster_tone_50' => 'array',
        'raster_tone_15' => 'array',
        'raster_tone_5' => 'array',
    ];

    protected $fillable = [
        'profile_id', 'sequence', 'color','viscosity','solvent','inhibitor','coordinates_lab','eltex_value','raquel','optical_density','raster_tone_50','raster_tone_15','raster_tone_5'
    ];
}
