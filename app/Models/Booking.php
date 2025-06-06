<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\Slot;
use App\Models\Media;

class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory;

    public static $statuses = [
        1 => 'Enable',
        0 => 'Disable', 
        
    ];

    protected $fillable = [
        'title',
        'description',       
        'image_id',
        'duration',
        'status',
        'enableSale'
    ];

    public function media(): HasOne
    {
        return $this->hasOne(Media::class, 'id', 'image_id');        
    }
    public function slots(): HasMany
    {
        return $this->hasMany(Slot::class, 'booking_id', 'id');        
    }
}
