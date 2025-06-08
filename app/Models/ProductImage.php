<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_url',
        'alt_text',
        'is_thumbnail',
        'order',
    ];

    protected $casts = [
        'is_thumbnail' => 'boolean',
        'order' => 'integer',
    ];

    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class, 'image_id');
    }
}