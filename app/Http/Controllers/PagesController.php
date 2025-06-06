<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Slider;
use App\Models\RandomPosts;
use App\Models\Booking;
use App\Models\Media;
use App\Models\User;


class PagesController extends Controller
{
    public function home(){
        return Inertia::render('Home', [
            'sliders' => Slider::with('media')->where('status', 1)->get(), 
            'bookings' => Booking::with('media')->where('status', 1)->get(),            
            'welcome' => RandomPosts::with('media')->where('group_key', 'home_welcome')->get(), 
            'mediaLink' => Media::getMediaRootLink(), 
            
        ]);
    }

    public function bookings(){
        return Inertia::render('BookingList', [
            'sliders' => Slider::with('media')->where('status', 1)->get(),             
            'welcome' => RandomPosts::with('media')->where('group_key', 'home_welcome')->first(), 
            'mediaLink' => Media::getMediaRootLink()
        ]);
    }    

    public function searchUser(Request $request) {
        $data = $request->validate([
            'user_name' => 'required|string|min:3|max:255', 
        ]);
        $users = User::orwhere('name', 'like', "%{$data['user_name']}%")->
                        orWhere('email', 'like', "%{$data['user_name']}%")->get();
        return response()->json(['users' => $users]);
    }
}
