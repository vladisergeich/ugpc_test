<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class OkvidNumberCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return substr($value, 0, -2) . '-' . substr($value, -2);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return $value;
    }
}