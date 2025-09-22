<?php

namespace App\Models\BDGP;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ugpc\EngravingOrder;
use App\Models\Ugpc\MovementOrder;
use App\Models\BDGP\MacroOrder;
use App\Models\BDGP\OrderStream;
use App\Models\BDGP\ShaftOrder;
use App\Models\Ugpc\ShaftRequestComposition;

class Order extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function engravingOrders()
    {
        return $this->hasMany(EngravingOrder::class,'design_order_id', 'id');
    }

    public function finishedShafts()
    {
        return $this->hasMany(EngravingOrder::class,'design_order_id', 'id')->where('status','завершен');
    }

    public function macroOrder()
    {
        return $this->hasOne(MacroOrder::class,'macro_id', 'upak_order');
    }

    public function streams()
    {
        return $this->hasMany(OrderStream::class,'okvid_number', 'okvid_number');
    }

    public function requestShaft()
    {
        return $this->hasOne(ShaftRequestComposition::class,'okvid_number', 'okvid_number')->orderBy('id','desc');
    }

    public function shafts()
    {
        return $this->hasMany(ShaftOrder::class,'okvid_number', 'okvid_number')->where('written_off',false)->where('shaft_id','<>',0);
    }

    public function movementOrder()
    {
        return $this->hasOne(MovementOrder::class,'okvid_number', 'okvid_number');
    }

    /*
    protected $casts = [
        'order_accepted_date' => 'date:d.m.Y',
        'jpeg_sent_approval' => 'date:d.m.Y',
        'layout_planned_date_approval' => 'date:d.m.Y',
        'layout_agreed_text' => 'date:d.m.Y',
        'date_arrival_shaft' => 'date:d.m.Y',
        'color_proof_agreed' => 'date:d.m.Y',
        'application_manufacture_bases' => 'date:d.m.Y',
        'request_install_date' => 'date:d.m.Y',
        'request_engraving_date' => 'date:d.m.Y',
        'color_proof_sent_date' => 'date:d.m.Y',
        'receipt_request' => 'date:d.m.Y',
        'shipment_request' => 'date:d.m.Y',
        'transfer_request' => 'date:d.m.Y',
        'state_update' => 'date:d.m.Y',
    ];
    */
    
    
}
