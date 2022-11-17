<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'status'
    ];

    const Status = [
        'Active' => 1,
        'Inactive' => 2
    ];

    public function getStatusLabelAttribute(): int|string
    {
        return array_flip(self::Status)[$this->attributes['status']];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,  'role_id',  'íd');
    }
}
