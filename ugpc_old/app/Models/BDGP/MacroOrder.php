<?php

namespace App\Models\BDGP;

use Illuminate\Database\Eloquent\Model;
use App\Models\BDGP\Order;
use App\Models\BDGP\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MacroOrder extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function lastOrders()
    {
        return $this->hasOne(Order::class,'upak_order', 'macro_id')->where('prod_order','<>',null)->orderBy('id','desc');
    }

    public function orders()
    {
        return $this->hasMany(Order::class,'upak_order', 'macro_id')->where('prod_order','<>',null);
    }

    public function shafts()
    {
        return $this->hasMany(Order::class,'upak_order', 'macro_id');
    }

    public function customerId()
    {
        return $this->hasOne(Customer::class,'description', 'customer');
    }
}
