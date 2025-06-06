<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
use App\Models\AppointmentItem;
use App\Models\User;
use App\Models\Booking;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory;

    public static $statuses = [
        'pending' => 'Pending', 
        'processing' => 'Processing',
        'paid' => 'Paid',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'failed' => 'Failed',
        
    ];

    public static $userTypes = [
        1 => 'New',
        2 => 'Existing',
    ];

    protected $fillable = [
        'total',
        'sub_total',       
        'user_id',
        'notes',
        'status',        
    ];

    public function app_item(): HasOne
    {
        return $this->hasOne(AppointmentItem::class, 'appointment_id', 'id');
        // Media::class (related model), 'id' (media.id), 'image' (slider.image)
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
        // Media::class (related model), 'id' (media.id), 'image' (slider.image)
    }


    public function item()
    {
        return $this->belongsTo(AppointmentItem::class);
    }
    
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
