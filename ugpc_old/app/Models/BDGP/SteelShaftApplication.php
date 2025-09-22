<?php

namespace App\Models\BDGP;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BDGP\Customer;
use App\Models\BDGP\SteelShaftApplicationComposition;

class SteelShaftApplication extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function customer()
    {
        return $this->hasOne(Customer::class,'id', 'customer_id');
    }

    public function firstComposition()
    {
        return $this->hasOne(SteelShaftApplicationComposition::class,'document_number', 'document_number');
    }
}
