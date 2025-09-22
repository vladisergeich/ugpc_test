<?php
namespace App\Models\Ugpc;

use Illuminate\Database\Eloquent\Model;
use App\Models\BDGP\Shaft;

class ShaftRequestComposition extends Model
{
    protected $guarded = ['id'];
    
    public function shaft()
    {
        return $this->hasOne(Shaft::class,'shaft_id','shaft_id');
    }
}
