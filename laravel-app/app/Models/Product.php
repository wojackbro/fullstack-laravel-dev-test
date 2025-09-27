<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        "title",
        "price", 
        "description",
        "category",
        "crawled_at"
    ];

    protected $casts = [
        "price" => "decimal:2",
        "crawled_at" => "datetime"
    ];

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }
}
