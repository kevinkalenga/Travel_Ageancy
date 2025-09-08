<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackagePhoto extends Model
{
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
