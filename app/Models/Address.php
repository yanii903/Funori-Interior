<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'receiver_name',
        'receiver_phone',
        'street_address',
        'address_type',
        'is_default',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}