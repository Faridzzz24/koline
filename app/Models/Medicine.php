<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'category', 'brand', 'description', 'usage',
        'side_effects', 'price', 'stock', 'image', 'requires_prescription', 'is_active',
    ];

    protected $casts = [
        'requires_prescription' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function orderItems() { return $this->hasMany(OrderItem::class); }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'obat_bebas' => 'Obat Bebas',
            'obat_keras' => 'Obat Keras',
            'suplemen' => 'Suplemen',
            'vitamin' => 'Vitamin',
            'herbal' => 'Herbal',
            'alat_kesehatan' => 'Alat Kesehatan',
            default => $this->category,
        };
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/medicine-placeholder.png');
    }
}
