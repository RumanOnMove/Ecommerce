<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'item_quantity',
        'item_sub_total',
        'discount',
        'item_total',
        'status'
    ];

    const Status = [
        'Active' => 1,
        'Processing' => 2,
        'Complete' => 3
    ];

    public function getStatusLabelAttribute(): int|string
    {
        return array_flip(self::Status)[$this->attributes['status']];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function order_master(): BelongsTo
    {
        return $this->belongsTo(OrderMaster::class, 'order_id', 'id');
    }
}
