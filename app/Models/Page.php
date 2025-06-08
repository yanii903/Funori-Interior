<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', // Tiêu đề trang
        'slug',     
        'content', // Nội dung trang
        'author_id', // Khóa ngoại đến users id
        'page_type', // Loại trang 
        'status', // trạng thái
        'meta_title', // Thẻ meta title SEO
        'meta_description', // Thẻ meta description SEO
        'featured_image_url', // Ảnh đại diện cho bài blog
        'published_at', // Thời gian xuất bản
    ];

    protected $casts = [
        'page_type' => 'string', 
        'status' => 'string', 
        'published_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}