<?php

namespace App\Models\Ugpc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ugpc\OperationParam;
use App\Models\Ugpc\OperationType;

class RouteMapStage extends Model
{
    use HasFactory;

    public function parameters()
    {
        return $this->hasMany(OperationParam::class,'operation','description');
    }

    public function operationtype()
    {
        return $this->hasOne(OperationType::class,'description','description');
    }
}
