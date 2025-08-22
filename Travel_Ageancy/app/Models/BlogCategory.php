<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    public function posts() 
    {
        // a blog can have multiple posts
        return $this->hasMany(Post::class);
    }
}
