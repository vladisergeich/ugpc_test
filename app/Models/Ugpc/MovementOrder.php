<?php

namespace App\Models\Ugpc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ugpc\RouteMapCard;
use App\Models\BDGP\Order;

class MovementOrder extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function route_map_cards()
    {
        return $this->hasMany(RouteMapCard::class,'okvid_number', 'okvid_number');
    }

    public function designOrder()
    {
        return $this->hasOne(Order::class,'okvid_number', 'okvid_number');
    }
}
