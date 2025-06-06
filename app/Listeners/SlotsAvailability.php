<?php

namespace App\Listeners;

use App\Events\AppointmentFollowUp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
//use Illuminate\Support\Facades\Redis;
use App\Models\Slot;
class SlotsAvailability
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AppointmentFollowUp $event): void
    {           
        $p = $event->Appointment;
        Slot::updateAvailability(  $p->app_item->appointment_id,
                                        $p->app_item->start_date,
                                        $p->app_item->start_hour,
                                        $p->app_item->booking_id,                            
                                        $p->app_item->quantity,
                                        in_array($p->status,  ['cancelled', 'failed']) ? 'remove' :'add'
                                    );
    }    
}
