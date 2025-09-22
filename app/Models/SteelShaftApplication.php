<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\{SteelShaftApplicationShaft,Vendor};


class SteelShaftApplication extends Model
{
    use HasFactory;
    protected $guarded = ['id','sequence'];

    protected $casts = [
        'attachments' => 'array' // Автоматическое преобразование JSON ↔ array
    ];

    public function shafts()
    {
        return $this->hasMany(SteelShaftApplicationShaft::class,'steel_shaft_application_id')->orderBy('sequence');
    }

    public function engraver()
    {
        return $this->hasOne(Vendor::class,'id','engraver_id');
    }
}
