<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\{EngravingOrderShaft};

class ShaftConsumption extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function engravingOrderShaft()
    {
        return $this->belongsTo(EngravingOrderShaft::class, 'engraving_order_shaft_id');
    }
}
