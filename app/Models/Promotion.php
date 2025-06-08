<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'discount_type',
        'discount_value',
        'max_discount_amount',
        'min_order_value',
        'usage_limit_per_voucher',
        'usage_limit_per_user',
        'times_used',
        'start_date',
        'end_date',
        'is_active',
        'applies_to',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'min_order_value' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'discount_type' => 'string', // Enum
        'applies_to' => 'string', // Enum
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'promotion_product', 'promotion_id', 'product_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'promotion_category', 'promotion_id', 'category_id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_promotion')->withPivot('discount_applied');
    }
}