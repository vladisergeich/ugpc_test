<?php

namespace App\Models\Ugpc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BDGP\Order;
use App\Models\BDGP\Shaft;
use App\Models\Ugpc\EngravingOrderStage;
use App\Models\Ugpc\OperationLedgerEntry;

class EngravingOrder extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function shaft()
    {
        return $this->hasOne(Shaft::class,'id','shaft_id');
    }

    public function design_order()
    {
        return $this->hasOne(Order::class,'id','design_order_id');
    }

    public function activeEngravingOrderStage()
    {
        return $this->hasOne(EngravingOrderStage::class,'engraving_order_id','id')->where('status','в работе');
    }

    public function lastEngravingOrderStage()
    {
        return $this->hasOne(EngravingOrderStage::class,'engraving_order_id','id')->orderBy('id','desc');
    }

    public function engravingOrderStages()
    {
        return $this->hasMany(EngravingOrderStage::class,'engraving_order_id','id')->orderBy('stage_number');
    }

    public function operations()
    {
        return $this->hasMany(OperationLedgerEntry::class,'engraving_order_id','id');
    }
}
