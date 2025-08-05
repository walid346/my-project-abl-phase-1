<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name', 'description', 'slug', 'image'];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'category_id');
    }
}