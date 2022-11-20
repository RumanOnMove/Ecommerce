<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductVariation extends Model
{
    use HasFactory;
    protected $table = 'product_variations';
    protected $fillable = [
        'product_id',
        'color_id',
        'size_id',
        'quantity',
        'low_stock',
        'price'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function color(): HasOne
    {
        return $this->hasOne(Color::class, 'id');
    }

    public function size(): HasOne
    {
        return $this->hasOne(Size::class, 'id');
    }
}
