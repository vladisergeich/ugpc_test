<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\{OrderItem,EngravingOrder};

class Order extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $fillable = [
        'order_number',
        'customer_code',
        'customer_name',
        'status',
        'support_manager_code',
        'support_manager_name',
        'print_machine_center',
        'winding_option',
        'customer_shipment_date',
        'previous_order_number',
        'current_stage_approval',
        'next_order_number',
        'edit_design_parameters',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class,'order_id','id');
    }

    public function engravingOrder()
    {
        return $this->hasOne(EngravingOrder::class, 'order_id','id');
    }
}
