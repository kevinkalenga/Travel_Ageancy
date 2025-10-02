<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageAmenity extends Model
{
    public function amenity()
    {
        return $this->belongsTo(Amenity::class);
    }
}
