<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    public function store()
{
    return $this->hasOne(Store::class);
}
}
