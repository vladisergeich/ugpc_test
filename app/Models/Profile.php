<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Vendor, Material,ProfileFingerprintColor,ProfilePrintProcessColor};

class Profile extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'fingerprint_parameters' => 'array',
        'print_process_parameters' => 'array',
    ];

    public function vendor()
    {
        return $this->hasOne(Vendor::class,'id','engraver_id');
    }
    public function primary()
    {
        return $this->hasOne(Material::class,'id','primary_material_id');
    }
    public function secondary()
    {
        return $this->hasOne(Material::class,'id','secondary_material_id');
    }
    
    public function fingerPrintColors()
    {
        return $this->hasMany(ProfileFingerprintColor::class,'profile_id')->orderBy('sequence');
    }

    public function printProcessColors()
    {
        return $this->hasMany(ProfilePrintProcessColor::class,'profile_id')->orderBy('sequence');
    }
}
