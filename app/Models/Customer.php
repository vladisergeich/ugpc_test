<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $connection = 'mdm';

    protected $table = 'system_navision_customers';
}
