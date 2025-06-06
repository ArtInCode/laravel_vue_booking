<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use App\Models\Slot;
use App\Models\Booking;

class SlotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $data = $request->validate([
            'weekday' => 'required',
            'start_hour' => 'required',            
            'price' => 'required',
            'sale_price' => '',
            'quantity' => 'required|min:1',            
            'booking_id' => 'required',
            'status' => 'required',
        ]);

        Slot::create($data);

        return response()->json(['success' => true, 'message' => 'Post created!', 'item' => Booking::with('slots')->find($data['booking_id'])]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slot $Slot)
    {
        $data = $request->validate([
            'weekday' => 'required',
            'start_hour' => 'required',            
            'price' => 'required',
            'sale_price' => '',
            'quantity' => 'required|min:1',            
            'booking_id' => 'required',
            'status' => 'required',
        ]);

        $res = $Slot->update($data);

        return response()->json(['success' => true, 'message' => 'Post created!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $request->validate([            
            'id' => 'required|integer'
        ]);
        Slot::destroy($id);
        return response()->json(['success' => true]);
    }

    public function slostForBooking(Request $request) {
        
        $request->validate([            
            'id' => 'required|integer'
        ]);
        $disabledWeekDays = Slot::getWeekdaysForBooking($request['id']);
               
        return response()->json(['weekDays' => $disabledWeekDays]);
    }

    public function slostForDate(Request $request) {
        
        $request->validate([            
            'bookingId' => 'required|integer', 
            'date' => 'required|string', 
        ]);

        $slots = Slot::getSlostForDate($request['bookingId'], $request['date']);
        
        if(count($slots) < 1){
            return throw \Illuminate\Validation\ValidationException::withMessages([
                'error' => 'No slots for '.$request['date'],
            ]);
       }       
        return response()->json(['slots' => $slots, 'weekday' => date('w', strtotime($request['date']))]);
    }
}
