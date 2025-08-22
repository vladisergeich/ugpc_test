<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileFingerprintColor extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $fillable = [
        'profile_id', 'sequence', 'color','pigment_paste','technical_lacquer','base_lacquer','lineature','corner','cutter','note'
    ];
}
