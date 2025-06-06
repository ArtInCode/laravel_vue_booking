<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use App\Models\Booking;
use App\Models\User;
use App\Models\Appointment;
use App\Models\AppointmentItem;
use App\Models\Slot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Events\AppointmentFollowUp;


class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $results = Appointment::with(['app_item', 'user' ])->orderBy('id', 'desc')->paginate(10);
        return Inertia::render('Admin/Appointments/AppointmentsList', [
            'appointments' => !empty($results) ? $results : [],            
            'link' => $results->links(), 
            'statuses' => Appointment::$statuses,            
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Appointments/AppointmentsForm', [
            'statuses' => Appointment::$statuses, 
            'userTypes' => Appointment::$userTypes,
            'create' => true
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeAdminAppointment(Request $request)
    {
         $data = $request->validate([                   
            'status' => 'required',            
        ]);

        Appointment::create($data);
        return redirect()->route('appointments.index')->with('success', 'Post created!');
    }

    public function createEmpty() {
        $Appointment = Appointment::create([
            'status' => 'pending',
            'total'  => 0,
            'sub_total' => 0, 
            'user_id' => 0
        ]);
        return redirect()->route('appointments.edit', ['id' => $Appointment->id])->with('success', 'Post created!');
        
    }

     /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'new_user_email' => 'email',            
            'status' => 'required',
        ]);

        $Appointment = Appointment::create($data);
        return redirect()->route('appointments.edit', ['id' => $Appointment->id])->with('success', 'Post created!');
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
        
        $item = Appointment::with(['app_item', 'user' ])->where('id', $id)->first();
        $weekDays = [];
        if(!empty( $item->app_item) AND !empty( $item-> app_item->booking_id)){
            $weekDays = Slot::getWeekdaysForBooking($item-> app_item->booking_id); 
        }
        return Inertia::render('Admin/Appointments/AppointmentsForm', [
            'statuses' => Appointment::$statuses,    
            'item' =>  $item, 
            'bookings' => Booking::orderBy('id', 'desc')->get(),
            'create' => false, 
            'itemId' => $id,
            'userTypes' => Appointment::$userTypes,
            'weekDays' => $weekDays,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $Appointment = Appointment::find($id);
        $data = $request->validate([
            'status' => 'required|string', 
            'sub_total' => 'min:0',
            'total' => 'min:0',           
            'user_id'=> 'required|integer|min:0',
                
        ]);
        if(!empty($request->input('newUserType')) AND $request->input('newUserType') === '1' AND !empty($request->input('new_user_email'))){
            $_email = $request->input('new_user_email');
            $data['user_id'] = User::createOrFindUser($_email);           
        }
       
        $Appointment->update($data); 
        $app = Appointment::with('app_item')->where('id', $Appointment->id)->first();
        
        event(new AppointmentFollowUp($app));
        return redirect()->route('appointments.edit', ['id' => $Appointment->id])->with('success', 'Post created!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $request->validate([            
            'id' => 'required|integer'
        ]);
        Appointment::destroy($id);
        return response()->json(['success' => true]);
    }

    public function saveUserAppointment(Request $request) {
        
        $data = $request->validate([
            'quantity'=> 'required',            
            'start_date'=> 'required|string',
            'start_hour'=> 'required|string', 
            'bookingId' => 'required',           
        ]);

        $Slot = Slot::find($data['bookingId'])->get();
        $appData = [
            'status' => 'pending',
            'sub_total' => 0,
            'total'  => 0,
            'notes' => $request->input('notes'), 
            'user_id' => $request->input('userId'),
        ]; 
        if(!empty($request->input('user_email'))){
            $appData['user_id'] = User::createOrFindUser($request->input('user_email'), $request->input('password'), $login = 1);
        }
         
        $foundSlot = 0;
        if(!empty($Slot)){
            foreach($Slot as $item){                
                if($item->start_hour === $data['start_hour']){
                   $appData['total'] = $appData['sub_total'] =  $item->unit_price *  $data['quantity'];
                   $foundSlot = $item; 
                }
            }             
        }

        if($foundSlot){
            $Appointment = Appointment::create($appData);
            
            $data = [
                'appointment_id' => $Appointment->id,
                'unit_price'=> !empty($foundSlot->sale_price) ? $foundSlot->sale_price : $foundSlot->price,       
                'quantity'=> $data['quantity'],
                'booking_id'=> $data['bookingId'],
                'start_date'=>$data['start_date'],
                'start_hour'=> $data['start_hour'],                
            ];
            $data['total'] = $data['quantity'] * $data['unit_price'];
            $appItemId = AppointmentItem::create($data);

            event(new AppointmentFollowUp(Appointment::with(['app_item'])->where('id', $data['appointment_id'])->first()));
            return redirect()->route('appointments.myAppointments')->with('success', 'Post created!');
            
        }        
    }

    public function myAppointments() {
        return Inertia::render('MyAccount/Appointments', [
            'statuses' => Appointment::$statuses,
            'appointments' => Appointment::with('app_item.booking')->where('user_id', auth()->user()->id)->orderBy('id', 'desc')->paginate(10),           
        ]);
    }

    public function myAppointmentCancel(Request $request, string $id) {
        $Appointment = Appointment::with(['app_item'])->where('user_id', auth()->user()->id)->where('id', $id)->first();
        if(!empty($Appointment)){           
            $Appointment->update(['status' => 'cancelled']);
            return response()->json(['success' => true]);
        }
        
        return response()->json(['failed' => true]);
    }
}
