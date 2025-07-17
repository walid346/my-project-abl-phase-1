<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    protected $table = 'articles';
    protected $fillable = [
        'title', 'content', 'excerpt', 'slug', 'image',
        'published_at', 'status', 'admin_id', 'category_id'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tags', 'article_id', 'tag_id')
                    ->withPivot('assigned_at')
                    ->withTimestamps();
    }

    public function visiteurs(): HasMany
    {
        return $this->hasMany(Visiteur::class, 'article_id');
    }

    /**
     * Obtient l'image de l'article ou celle de sa catégorie par défaut
     */
    public function getImageUrl(): ?string
    {
        // Si l'article a une image spécifique, l'utiliser
        if ($this->image) {
            return \Storage::url($this->image);
        }

        // Sinon, utiliser l'image de la catégorie si elle existe
        if ($this->category && $this->category->image) {
            return \Storage::url($this->category->image);
        }

        // Aucune image disponible
        return null;
    }

    /**
     * Vérifie si l'article a une image (propre ou de catégorie)
     */
    public function hasImage(): bool
    {
        return $this->image || ($this->category && $this->category->image);
    }
}