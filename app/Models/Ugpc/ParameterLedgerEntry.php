<?php

namespace App\Models\Ugpc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParameterLedgerEntry extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function parameter()
    {
        return $this->hasOne(Parameter::class,'id','parameter_id');
    }
}
