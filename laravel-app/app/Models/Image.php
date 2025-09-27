<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    protected $fillable = [
        "product_id",
        "image_url",
        "alt_text", 
        "sort_order"
    ];

    protected $casts = [
        "sort_order" => "integer"
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
