<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\{TransferShaft,Warehouse};

class Transfer extends Model
{
    use HasFactory;
    protected $guarded = ['id','sequence'];


    public function shafts()
    {
        return $this->hasMany(TransferShaft::class,'transfer_id')->orderBy('sequence');
    }

    public function sender()
    {
        return $this->hasOne(Warehouse::class,'id','sender_id');
    }

    public function recipient()
    {
        return $this->hasOne(Warehouse::class,'id','recipient_id');
    }
}
