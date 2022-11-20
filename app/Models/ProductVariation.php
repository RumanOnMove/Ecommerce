<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariation extends Model
{
    use HasFactory;
    protected $table = 'product_variations';
    protected $fillable = [
        'product_id',
        'color_id',
        'size_id',
        'price'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function colors(): HasMany
    {
        return $this->hasMany(Color::class, 'color_id', 'id');
    }

    public function sizes(): HasMany
    {
        return $this->hasMany(Size::class, 'size_id', 'id');
    }
}
