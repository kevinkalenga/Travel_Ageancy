<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    public function package_amenities()
    {
        return $this->hasMany(PackageAmenity::class);
    }
}
