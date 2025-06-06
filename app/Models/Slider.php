<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Media;

class Slider extends Model
{
    /** @use HasFactory<\Database\Factories\SliderFactory> */
    use HasFactory;

    public static $statuses = [
        1 => 'Enable',
        0 => 'Disable', 
        
    ];

    protected $fillable = [
        'title',
        'description',
        'link',
        'image_id',
        'group_key',
        'status',
        'fileName'
    ];

    public function media(): HasOne
    {
        return $this->hasOne(Media::class, 'id', 'image_id');        
    }
    
}
