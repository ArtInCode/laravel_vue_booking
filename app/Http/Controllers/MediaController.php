<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use App\Models\Media;


class MediaController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|array',
            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:12048'
        ]);       
        
        foreach ($request->file('image') as $image) {
            $file = Media::saveMediaFile($image);                    
            $media = Media::create([
                'name' => $file['origin'],
                'type' => $file['info']['mime_type'], 
                'meta' => json_encode($file['info'])
            ]);
        }

        return response()->json(['success' => true, ]);
    }

    public function filesList()
    {
        $data =  Media::orderBy('created_at', 'desc')->paginate(50);       
        return response()->json(['path'=> Media::getMediaRootLink(), 'items' => $data]);
    }

    public function deleteItems(Request $request) {
        $request->validate([
            'delItems' => 'required|array',
            'delItems.*' => 'required|integer'
        ]);
        foreach($request->delItems as $imageID){            
            Media::destroy($imageID);
        }
        return response()->json(['success' => true]);
    }
}
