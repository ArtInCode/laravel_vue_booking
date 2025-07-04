<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne; 
class RandomPosts extends Model
{
    /** @use HasFactory<\Database\Factories\RandomPostsFactory> */
    use HasFactory;

    

    public static $statuses = [
        '1' => 'Enable',
        '0' => 'Disable', 
        
    ];

    protected $fillable = [
        'title',
        'description',
        'link',
        'link_label',
        'image_id',
        'group_key',
        'status'
    ];

    public function media(): HasOne
    {
        return $this->hasOne(Media::class, 'id', 'image_id');        
    }
}
