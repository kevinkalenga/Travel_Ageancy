<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    public function photos()
    {
        return $this->hasMany(DestinationPhoto::class);
    }
}
