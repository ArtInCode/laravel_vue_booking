<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Booking;

class AppointmentItem extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentItemFactory> */
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'unit_price',       
        'quantity',
        'booking_id',
        'start_date',
        'start_hour',
        'total'        
    ];

    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class, 'id', 'booking_id');        
    }

    
}
