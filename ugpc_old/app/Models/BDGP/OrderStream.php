<?php

namespace App\Models\BDGP;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BDGP\ProductType;

class OrderStream extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function productType()
    {
        return $this->hasOne(ProductType::class,'gp_code', 'gp_code');
    }
}
