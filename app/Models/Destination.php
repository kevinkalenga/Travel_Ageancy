<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    public function photos()
    {
        return $this->hasMany(DestinationPhoto::class);
    }
    public function videos()
    {
        return $this->hasMany(DestinationVideo::class);
    }
    
    public function packages()
    {
        return $this->hasMany(Package::class);
    }
}
