<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use App\Models\RandomPosts;
use App\Models\Media;

class RandomPostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {      
        $results = RandomPosts::with('media')->orderBy('id', 'desc')->paginate(10);
        //dd($results);
        return Inertia::render('Admin/RandomPosts/RandomPostsList', [
            'posts' => $results  ,            
            'link' => $results->links(), 
            'mediaLink' => Media::getMediaRootLink()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/RandomPosts/RandomPostsForm', [
            'statuses' => RandomPosts::$statuses, 
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
            'description' => 'max:1111112555',
            'link' => 'max:555',
            'link_label' => 'max:555',
            'image_id' => 'string|max:555',
            'group_key' => 'required|string|max:555',
            'status' => 'required|string|max:555',            
            
        ]);

        RandomPosts::create($data);

        return redirect()->route('random-post.index')->with('success', 'Post created!');
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
        $results = RandomPosts::with('media')->find($id);        
        return Inertia::render('Admin/RandomPosts/RandomPostsForm', [
            'statuses' => RandomPosts::$statuses, 
            'mediaLink' =>  Media::getMediaRootLink(),
            'postItem' =>  $results,
            'create' => false, 
            'slider_id' => $id
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RandomPosts $RandomPosts)
    {
        $data = $request->validate([
            'title' => 'required|string|min:5|max:255',
            'description' => 'max:1111112555',
            'link' => 'max:555',
            'link_label' => 'max:555',
            'image_id' => 'integer|max:555',
            'group_key' => 'required|string|max:555',
            'status' => 'required|max:5',     
        ]);

        $RandomPosts->update($data);

        return redirect()->route('random-post.index')->with('success', 'Post created!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $request->validate([            
            'id' => 'required|integer'
        ]);
        RandomPosts::destroy($id);
        return response()->json(['success' => true]);
    }
}
