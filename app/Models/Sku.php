<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sku extends Model
{
    use HasFactory;
    protected $table = 'skus';
    protected $fillable = [
        'product_id',
        'name',
        'price'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function product_variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'sku_id', 'id');
    }
}
