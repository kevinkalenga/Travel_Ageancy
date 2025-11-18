<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    //
      protected $fillable = [
        'destination_id',
        'featured_photo',
        'banner',
        'name',
        'slug',
        'description',
        'map',
        'price',
        'total_rating',
        'total_score',
        'old_price',
    ];

    // Relation avec Destination
    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }
    public function package_itineraries()
    {
        return $this->hasMany(PackageItinerary::class);
    }
    public function package_photos()
    {
        return $this->hasMany(PackagePhoto::class);
    }
    public function package_videos()
    {
        return $this->hasMany(PackageVideo::class);
    }
    public function package_faqs()
    {
        return $this->hasMany(PackageFaqs::class);
    }
    public function tours()
    {
        return $this->hasMany(Tour::class);
    }

    public function reviews()
    {
        return $this->belongsTo(Review::class);
    }
    
}
