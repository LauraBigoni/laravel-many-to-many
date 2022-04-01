<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function author()
    {
        /** 
         * Serve per fare $post->user
         */
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag');
    }

    public function getUpdatedAt($date = 'd-m-Y H:i:s')
    {
        return Carbon::create($this->updated_at)->format($date);
    }
}
