<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title', 'content', 'image', 'slug', 'is_published', 'category_id'
    ];

    public function category()
    {
        /** 
         * Serve per fare $post->category
         */
        return $this->belongsTo('App\Models\Category');
    }

    public function user()
    {
        /** 
         * Serve per fare $post->category
         */
        return $this->belongsTo('App\User');
    }
}
