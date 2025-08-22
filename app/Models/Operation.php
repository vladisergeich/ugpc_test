<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\{OperationParameter};

class Operation extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function parameters()
    {
        return $this->hasMany(OperationParameter::class,'operation_id','id');
    }
}
