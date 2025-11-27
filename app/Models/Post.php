<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
     protected $fillable = ['title', 'description', 'status', 'created_user'];

    public function user() :BelongsTo 
    {
        return $this->belongsTo(User::class,'created_user');
    }

    public function categories() :BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_post');
    }
}
