<?php

namespace App\Models\BDGP;

use Illuminate\Database\Eloquent\Model;
use App\Models\BDGP\Order;
use App\Models\BDGP\Shaft;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShaftOrder extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'writeoff_date' => 'datetime:d.m.Y',
    ];

    public function order()
    {
        return $this->hasOne(Order::class,'okvid_number', 'okvid_number');
    }

    public function shaft()
    {
        return $this->hasOne(Shaft::class,'shaft_id', 'shaft_id');
    }
    

}
