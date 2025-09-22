<?php

namespace App\Models\Portal\Link;

use Illuminate\Database\Eloquent\Model;
use App\Models\Portal\Role\Role;

class Link extends Model
{
    protected $connection = 'mainportal2';

    public function child()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function role()
    {   
        return $this->belongsTo(Role::class);
    }
}
