<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PromotionProduct extends Pivot
{
    use HasFactory;

    protected $table = 'promotion_product';


    public $timestamps = false;

    protected $fillable = [
        'promotion_id',
        'product_id',
    ];
    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}