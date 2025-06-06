<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use App\Models\Booking;
use App\Models\Media;
use App\Models\Slot;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $results = Booking::with('media')->orderBy('id', 'desc')->paginate(10);
        return Inertia::render('Admin/Bookings/BookingsList', [
            'bookings' => !empty($results) ? $results : [],            
            'link' => $results->links(), 
            'mediaLink' => Media::getMediaRootLink()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Bookings/BookingsForm', [
            'statuses' => Booking::$statuses, 
            'mediaLink' =>  Media::getMediaRootLink(), 
            'weekDays' => Slot::$weekDays, 
            'create' => true
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $data = $request->validate([
            'title' => 'required|string|min:5|max:255',
            'description' => 'max:2555',            
            'image_id' => 'string|max:555',
            'duration' => 'required|integer|max:1000',
            'status' => 'required|string|max:1',            
            'enableSale' => 'max:1',
        ]);

        $Booking = Booking::create($data);

        return redirect()->route('bookings.edit', $Booking->id)->with('success', 'Post created!');
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
        return Inertia::render('Admin/Bookings/BookingsForm', [
            'statuses' => Booking::$statuses, 
            'mediaLink' =>  Media::getMediaRootLink(),
            'item' =>  Booking::with(['media', 'slots'])->find($id),
            'weekDays' => Slot::$weekDays, 
            'create' => false, 
            'item_id' => $id
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $Booking = Booking::where('id', $id)->first();
        $data = $request->validate([
            'title' => 'required|string|min:5|max:255',
            'description' => 'max:2555',            
            'image_id' => 'required',
            'duration' => 'required|integer|max:1000',
            'status' => 'required|integer|max:1',            
            'enableSale' => 'max:1',
        ]);
        
        $res = $Booking->update($data);
       //dd(['id' =>  $id, 'bid' => $Booking->id, 'data' => $data]);
        return redirect()->route('bookings.index')->with('success', 'Post created!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $request->validate([            
            'id' => 'required|integer'
        ]);
        Booking::destroy($id);
        return response()->json(['success' => true]);
    }

    public function bookings_item($slug, $id) {
        $disabledWeekDays = Slot::getWeekdaysForBooking($id);        
        return Inertia::render('BookingSingle', [            
            'mediaLink' =>  Media::getMediaRootLink(),
            'item' =>  Booking::with(['media', 'slots'])->find($id),
            'bookings' => Booking::with('media')->where('status', 1)->where('id', '!=', $id)->get(),     
            'disabledWeekDays' => $disabledWeekDays, 
            'itemId' => $id
        ]);
    }
}
