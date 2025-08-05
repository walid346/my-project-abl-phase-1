<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ArticleTag extends Pivot
{
    protected $table = 'article_tag';
    public $timestamps = false;

    protected $fillable = ['idArticle', 'idTag', 'assignedAt'];
}