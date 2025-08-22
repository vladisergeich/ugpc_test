<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShaftResource extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $fillable = [
        'engraving_order_shaft_id', 'sequence', 'order_number', 'print_date', 'footage',
    ];
}
