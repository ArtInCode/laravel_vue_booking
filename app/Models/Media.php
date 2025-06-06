<?php

namespace App\Models;


use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;


class Media extends Model
{
    /** @use HasFactory<\Database\Factories\MediaFactory> */
    use HasFactory;

    protected $fillable = ['name', 'type', 'meta'];

    public static $perPageLimit = 12;

    public static function getMediaRootLink(){
        return str_replace('localhost', 'localhost:5173', Storage::url('media'));
    }

    public static function getFileInfo($image) {         
        return array(
            'width' => $image->width(),
            'height' => $image->height(),       
        );
    }

    public static function saveMediaFile($image) {
       
        $info = array(
            'extension'     => $image->extension(),
            'mime_type'     => $image->getMimeType(),
            'size_kb'       => round($image->getSize() / 1024, 2),
        ); 
        $imageName = time().'-'.$image->getClientOriginalName();
        $destinationPath = storage_path('/media');
        $origin = $image->move($destinationPath, $imageName);        
        
        $img = Image::read($origin);
        $info['width'] = $img->width();
        $info['height'] = $img->height();  
        
        $img->resize(100, 100, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/thumb-'.$imageName);
     
        
        return ['origin' => $imageName, 'thumb' =>  'thumb-'.$imageName, 'info' => $info];
    }
}
