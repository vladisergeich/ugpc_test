<?php

namespace App\Models\BDGP;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BDGP\FingerprintParameter;
use App\Models\BDGP\PintingProcessParameter;

class Profile extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function fingerPrintParam()
    {
        return $this->hasMany(FingerprintParameter::class,'profile_id', 'id');
    }

    public function printingProcessParam()
    {
        return $this->hasMany(PintingProcessParameter::class,'profile_id', 'id');
    }

    
}
