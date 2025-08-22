<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\{Transfer,Shaft,EngravingOrder};

class TransferShaft extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $fillable = [
        'transfer_id', 'engraving_order_id', 'shaft_id','sequence','type',
    ];

    public function transfer()
    {
        return $this->belongsTo(Transfer::class, 'transfer_id');
    }

    public function shaft()
    {
        return $this->hasOne(Shaft::class,'id','shaft_id');
    }

    public function engravingOrder()
    {
        return $this->belongsTo(EngravingOrder::class, 'engraving_order_id');
    }
}
