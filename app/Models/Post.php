<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function blog_category() 
    {
        // each post belongs to a blog category
        return $this->belongsTo(BlogCategory::class);
    }
}
