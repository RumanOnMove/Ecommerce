<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderMaster extends Model
{
    use HasFactory;
    protected $table = 'order_masters';
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'total_price',
        'status'
    ];

    const Status = [
        'Active' => 1,
        'Inactive' => 2
    ];


    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class,  'product_id', 'id');
    }
}
