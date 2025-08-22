<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\{Vendor,EngravingOrderShaft,Warehouse};

class Shaft extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $fillable = ['status','state','code','ff','diameter','thickness','type','warehouse_id','note','warehouse_place','format'];

    protected $appends = ['status_text', 'state_text'];

    const STATUS_IN_NORMAL = 'normal';
    const STATUS_REJECTED = 'rejected';

    const STATE_IN_FREE = 'free';
    const STATE_ENGRAVING = 'engraving';
    const STATE_AGREEMENT = 'agreement';
    const STATE_REFUSE = 'refuse';
    const STATE_REPAIR = 'repair';

    public static function statuses(): array
    {
        return [
            self::STATUS_IN_NORMAL => 'Норма',
            self::STATUS_REJECTED => 'Брак',
        ];
    }

    public static function states(): array
    {
        return [
            self::STATE_IN_FREE => 'Свободен',
            self::STATE_ENGRAVING => 'Гравировка',
            self::STATE_AGREEMENT => 'На согласовании',
            self::STATE_REFUSE => 'Утилизирован',
            self::STATE_REPAIR => 'Ремонт',
        ];
    }

    public function engravingOrdersShafts()
    {
        return $this->hasMany(EngravingOrderShaft::class,'shaft_id','id');
    }  

    public function getStatusTextAttribute()
    {
        return $this->getStatusShaft();
    }

    public function getStateTextAttribute()
    {
        return $this->getStateShaft();
    }

    public function getStatusShaft(): string
    {
        return self::statuses()[$this->status] ?? 'Неизвестный статус';
    }

    public function getStateShaft(): string
    {
        return self::states()[$this->state] ?? 'Неизвестный статус';
    }

    public function formats()
    {
        return $this->hasMany(EngravingOrderShaft::class,'shaft_id','id');
    }  

    public function vendor()
    {
        return $this->hasOne(Vendor::class,'id','vendor_id');
    }

    public function warehouse()
    {
        return $this->hasOne(Warehouse::class,'id','warehouse_id');
    }
}
