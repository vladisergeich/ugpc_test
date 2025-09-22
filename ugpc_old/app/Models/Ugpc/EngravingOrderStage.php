<?php

namespace App\Models\Ugpc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ugpc\EngravingStage;
use App\Models\Ugpc\EngravingOrder;
use Illuminate\Database\Eloquent\Casts\Attribute;

class EngravingOrderStage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['engraving_stages_by_stage_id'];

    public function activeEngravingStage()
    {
        return $this->hasOne(EngravingStage::class,'engraving_order_stage_id','id')->where('open',true);
    }

    public function defectEngravingStage()
    {
        return $this->hasOne(EngravingStage::class,'engraving_order_stage_id','id')->where('defect',true);
    }

    public function engravingStages()
    {
        return $this->hasMany(EngravingStage::class,'engraving_order_stage_id','id')->orderBy('stage_number');
    }

    public function grindingEditionStage()
    {
        return $this->hasOne(EngravingStage::class,'engraving_order_stage_id','id')->where('stage_id',7);
    }

    public function copperPlatingEditionStage()
    {
        return $this->hasOne(EngravingStage::class,'engraving_order_stage_id','id')->where('stage_id',12);
    }

    public function grindingBase()
    {
        return $this->hasOne(EngravingStage::class,'engraving_order_stage_id','id')->where('stage_id',3);
    }

    public function engravingOrder()
    {
        return $this->hasOne(EngravingOrder::class,'id','engraving_order_id');
    }

    public function engravingStagesByStageId():Attribute
    {
        return Attribute::make(get:fn()=>$this->engravingStages->keyBy('stage_id'));
    }

    
}
