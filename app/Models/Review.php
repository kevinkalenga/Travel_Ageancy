<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    // each review bolongs to a user and each review belongs to a package
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
    public function reviews()
    {
        return $this->belongsTo(Review::class);
    }
}
