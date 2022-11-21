<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    use HasFactory;
    protected $table = 'attributes';
    protected $fillable = [
        'name',
        'slug'
    ];

    # Attribute Values
    public function values(): HasMany
    {
        return $this->hasMany(Value::class, 'attribute_id', 'id');
    }
}
