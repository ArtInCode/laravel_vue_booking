<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppointmentItem;
use App\Events\AppointmentFollowUp;
use App\Models\Appointment;


class AppointmentItemController extends Controller
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
            'appointment_id' => 'required|string',
            'unit_price'=> 'required|string',       
            'quantity'=> 'required|string',
            'booking_id'=> 'required|string',
            'start_date'=> 'required|string',
            'start_hour'=> 'required|string',            
        ]);

        $data['total'] = $data['quantity'] * $data['unit_price'];
        AppointmentItem::create($data);
        event(new AppointmentFollowUp(Appointment::with(['app_item'])->where('id', $data['appointment_id'])->get()));
        return response()->json(['data' => $data]);
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
    public function update(Request $request, string $id)
    {
        
        $data = $request->validate([
            'appointment_id' => 'required|string',
            'unit_price'=> 'required|string',       
            'quantity'=> 'required|string',
            'booking_id'=> 'required|string',
            'start_date'=> 'required|string',
            'start_hour'=> 'required|string',            
        ]);
        $data['total'] = $data['quantity'] * $data['unit_price'];
        
        $AppointmentItem = AppointmentItem::findOrFail($id)->first();
        $AppointmentItem->update($data);

        event(new AppointmentFollowUp(Appointment::with(['app_item'])->find($data['appointment_id'])->get()));
       
        return response()->json(['data' => $data]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $request->validate([            
            'id' => 'required|integer'
        ]);
        AppointmentItem::destroy($id);
        return response()->json(['success' => true]);
    }
}
