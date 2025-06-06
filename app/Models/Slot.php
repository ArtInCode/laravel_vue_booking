<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;
//use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
class Slot extends Model
{
    /** @use HasFactory<\Database\Factories\SlotFactory> */
    use HasFactory;

    public static $statuses = [
        1 => 'Enable',
        0 => 'Disable', 
        
    ];

    public static $weekDays = array(			
			1 => 'Monday', 
			2 => 'Tuesday', 
			3 => 'Wednesday', 
			4 => 'Thursday',
			5 => 'Friday', 
			6 => 'Saturday', 
			7 => 'Sunday', 
    );

    protected $fillable = [
        'weekday',
        'start_hour',       
        'price',
        'sale_price',
        'quantity',
        'booking_id',
        'status'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public static function updateAvailability($app_id, $date, $slot, $booking_id,  $quantity, $actionType = 'add'){
        
        $data =  Cache::get('booking:'.$booking_id);
        $_date = str_replace('-', '', $date);
        if(empty($data)){
             $data = array(); 
        } else {
            $data = json_decode($data, true);
            
            foreach($data as $key => $d){
                if( $key < $date){
                    unset($data[$key]); //remove passed days
                }
            }    
        }               
        
        if($actionType === 'add'){
            $data[$_date][$app_id][date('w', strtotime($date))][$slot] = $quantity;
        } else {
            $data[$_date][$app_id][date('w', strtotime($date))][$slot] = 0;
        }

        Cache::forever('booking:'.$booking_id, json_encode($data));
    }

    public static function getWeekdaysForBooking($id) {
        $disabledWeekDays = Slot::select('weekday')->where('booking_id', $id)->distinct()->pluck('weekday');
        array_values( (array) $disabledWeekDays);
        return $disabledWeekDays;
    }

    public static function getSlostForDate($bookingId, $date) {
        $_date = str_replace('-', '', $date);
        $slots = Slot::where('booking_id', $bookingId )
                      ->where('weekday', date('w', strtotime($date)))
                      ->where('status', '1')->get();
        $weekDay = date('w', strtotime($date));
        $data = Cache::get('booking:'.$bookingId);
        if(!empty($data)){
            $data = json_decode($data, true);
        }
        
        if(!empty($data[$_date])){
            foreach($slots as $sKey =>  $slot){
                foreach($data[$_date] as $booking_id => $s){                                 
                    if(!empty($s[$weekDay]) AND !empty($s[$weekDay][$slot->start_hour])){                         
                        $slots[$sKey]->quantity = $slots[$sKey]->quantity - $s[$weekDay][$slot->start_hour];
                        if($slots[$sKey]->quantity < 1){
                            unset($slots[$sKey]);
                        }                        
                    }
                }
            }            
        }
       
        return $slots;
    }
}
