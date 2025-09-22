<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{EngravingOrderShaft,EngravingOrder, Customer};

class MacroOrder extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function engravingOrders()
    {
        return $this->hasMany(EngravingOrder::class,'macro_order_id')->orderBy('sequence_number');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function shaftsInWork()
    {
        return $this->hasManyThrough(
            EngravingOrderShaft::class,
            EngravingOrder::class,
            'macro_order_id', // Foreign key на EngravingOrder
            'engraving_order_id', // Foreign key на EngravingOrderShaft
            'id', // Local key на MacroOrder
            'id' // Local key на EngravingOrder
        )->where('status', 'in_progress')->orderBy('sequence_number');
    }
}
