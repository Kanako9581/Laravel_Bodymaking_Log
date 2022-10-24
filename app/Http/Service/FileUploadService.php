<?php
namespace App\Http\Service;
class FileUploadService{
    public function saveImage($image){
         $path = '';
        if(isset($image) === true){
            $path = $image->store('photos', 'public');
            // $images = $request->file('image');
            // foreach($images as $image){
            //     $image_name = $image->getClientOriginalName();
            //     $image->storeAs('', $image_name);
            // }
        }
        return $path;
    }
}