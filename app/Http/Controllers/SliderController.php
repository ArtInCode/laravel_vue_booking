<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;
use App\Models\Media;
use App\Models\User;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {        
        $results = Slider::with('media')->orderBy('id', 'desc')->paginate(10);
        return Inertia::render('Admin/Slider/SliderList', [
            'sliders' => $results,             
            'link' => $results->links(), 
            'mediaLink' => Media::getMediaRootLink()
        ]);
    }
    
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
        return Inertia::render('Admin/Slider/SliderForm', [
            'statuses' => Slider::$statuses, 
            'mediaLink' =>  Media::getMediaRootLink(), 
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
            'link' => 'max:555',
            'image_id' => 'required|integer|max:555',
            'group_key' => 'required|string|max:555',
            'status' => 'required|string|max:555', 
        ]);

        Slider::create($data);

        return redirect()->route('slider.index')->with('success', 'Post created!');
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
        $results = Slider::with('media')->find($id);  

        return Inertia::render('Admin/Slider/SliderForm', [
            'statuses' => Slider::$statuses, 
            'mediaLink' =>  Media::getMediaRootLink(),
            'sliderItem' =>  $results,
            'create' => false, 
            'slider_id' => $id
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slider $slider)
    {
         $data = $request->validate([
            'title' => 'required|string|min:5|max:255',
            'description' => 'max:2555',
            'link' => 'max:555',
            'image_id' => 'required|integer|max:555',
            'group_key' => 'required|string|max:555',
            'status' => 'required|string|max:555', 
        ]);

        $slider->update($data);

        return redirect()->route('slider.index')->with('success', 'Post created!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $request->validate([            
            'id' => 'required|integer'
        ]);

        Slider::destroy($id);
        
        return response()->json(['success' => true]);
    }
}
