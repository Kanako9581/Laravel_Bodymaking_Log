<?php
namespace App\Service;
class FileUploadService{
    public function saveImage($image){
         $path = '';
        if(isset($image) === true){
            $path = $image->store('photos', 'public');
        }
        return $path;
    }
}