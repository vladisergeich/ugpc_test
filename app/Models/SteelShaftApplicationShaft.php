<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\{Shaft};

class SteelShaftApplicationShaft extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function shaft()
    {
        return $this->hasOne(Shaft::class,'id','shaft_id');
    }
}
