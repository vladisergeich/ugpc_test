<?php

namespace App\Models\Ugpc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BDGP\ShaftOrder;
use App\Models\BDGP\ShaftResource;
use App\Models\Ugpc\ReEngravingAppFile;

class ReEngravingApplicationShaft extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function shaftOrder()
    {
        return $this->hasOne(ShaftOrder::class,'shaft_id', 'shaft_id')->where('written_off',false)->orderBy('id','desc');
    }

    public function lastOkvid()
    {
        return $this->hasOne(ShaftOrder::class,'shaft_id','shaft_id')->orderBy('writeoff_date','desc')->where('written_off',true);
    }

    public function resourse()
    {
        return $this->hasMany(ShaftResource::class,'shaft_id','shaft_id');
    }

    public function files()
    {
        return $this->hasMany(ReEngravingAppFile::class,'app_shaft_id','id');
    }
}
