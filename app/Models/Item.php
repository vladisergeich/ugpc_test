<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\{Order,OrderItem,EngravingOrder};

class Item extends Model
{
    use HasFactory;
    protected $guarded = ['id','code'];

    protected $fillable = [
        'code', 'description', 'category', 'customer', 'brand', 'sap_code','subspecies','reduction'
    ];

    public function engravingOrders()
    {
        return $this->hasManyThrough(
            EngravingOrder::class,
            OrderItem::class,
            'item_id', // Внешний ключ в таблице order_items
            'order_id',   // Внешний ключ в таблице engraving_orders
            'id',         // Локальный ключ в таблице products
            'order_id'    // Локальный ключ в таблице order_items
        );
    }
}
