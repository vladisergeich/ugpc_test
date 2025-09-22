<?php

namespace App\Models\Ugpc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ugpc\Parameter;
use App\Models\Ugpc\ParameterOperation;

class Operation extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function params()
    {
        return $this->belongsToMany(Parameter::class,'parameter_operations','operation_id','parameter_id');
    }
}
