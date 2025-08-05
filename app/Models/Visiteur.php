<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visiteur extends Model
{
    protected $table = 'visiteurs';
    protected $fillable = ['ip_address', 'session_id', 'visit_date', 'user_agent', 'article_id'];

    protected $casts = [
        'visit_date' => 'datetime',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}