<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\{Parameter};

class PhaseStageParameter extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function parameter()
    {
        return $this->hasOne(Parameter::class,'id','parameter_id');
    }

}
