<?php

namespace App\Models\Ugpc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ugpc\EngravingOrderStage;
use App\Models\Ugpc\Stage;
use App\Models\Ugpc\PlanParameterLedgerEntry;
use App\Models\Ugpc\OperationLedgerEntry;

class EngravingStage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function stage()
    {
        return $this->hasOne(Stage::class,'id','stage_id');
    }

    public function planParams()
    {
        return $this->hasMany(PlanParameterLedgerEntry::class,'engraving_stage_id','id');
    }

    public function operation()
    {
        return $this->hasOne(OperationLedgerEntry::class,'engraving_stage_id','id');
    }

    public function planDiameter()
    {
        return $this->hasOne(PlanParameterLedgerEntry::class,'engraving_stage_id','id')->where('parameter_id',1);
    }

    public function planCopperPlating()
    {
        return $this->hasOne(PlanParameterLedgerEntry::class,'engraving_stage_id','id')->where('parameter_id',5);
    }

}
