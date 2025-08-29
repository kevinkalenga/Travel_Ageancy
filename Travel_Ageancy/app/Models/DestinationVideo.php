<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinationVideo extends Model
{
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
