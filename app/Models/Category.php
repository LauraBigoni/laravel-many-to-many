<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    /** 
     * Serve per fare $category->posts
     */
    public function posts()
    {
        return $this->hasMany('App\Models\Post');
    }

    protected $fillable = ['label', 'color'];
}
