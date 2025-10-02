<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function blog_category() 
    {
        // this post belongs to blog category
        return $this->belongsTo(BlogCategory::class);
    }
}
