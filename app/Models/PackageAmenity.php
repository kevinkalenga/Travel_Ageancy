<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageAmenity extends Model
{
    public function amenity()
    {
        return $this->belongsTo(Amenity::class);
    }
    public function package()
    {
        // each packageAminity must belong to a package and a package can have multiple aminities
        return $this->belongsTo(Package::class);
    }
}
