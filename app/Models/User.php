<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'phone_number',
        'avatar_url',
        'account_status',
        'role',
        'remember_token',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime', 
        'password' => 'hashed',
        'account_status' => 'string', 
        'role' => 'string',
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlist()
    {
        return $this->hasOne(Wishlist::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class, 'author_id');
    }

    public function contactSubmissionsRepliedBy()
    {
        return $this->hasMany(ContactSubmission::class, 'replied_by');
    }
}