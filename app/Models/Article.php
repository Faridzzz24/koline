<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id', 'title', 'slug', 'excerpt', 'content', 'image',
        'category', 'tags', 'is_published', 'published_at', 'views',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function author() { return $this->belongsTo(User::class, 'author_id'); }

    public function scopePublished($query) { return $query->where('is_published', true); }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'kesehatan_umum' => 'Kesehatan Umum',
            'gizi' => 'Gizi & Nutrisi',
            'olahraga' => 'Olahraga',
            'mental_health' => 'Kesehatan Mental',
            'tips_dokter' => 'Tips Dokter',
            'penyakit' => 'Penyakit',
            'ibu_anak' => 'Ibu & Anak',
            default => $this->category,
        };
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/article-placeholder.png');
    }
}
