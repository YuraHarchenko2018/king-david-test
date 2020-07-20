<?php

namespace App\Classes;

use App\Interfaces\ImageProcessorInterface;
use Intervention\Image\ImageManagerStatic;

class InterventionProcessorStrategy implements ImageProcessorInterface 
{

    public function cropMobile($img_name, $directory) {
        $original_file_path = storage_path('app/image/') . $directory . "/" . $img_name;
        $cropped_file_path = storage_path('app/image/') . $directory . "/" . 'mobile_' . $img_name;

        $img = ImageManagerStatic::make($original_file_path);
        $img->crop(400, 800);
        $img->save($cropped_file_path, 10);
    }

    public function cropDesktop($img_name, $directory) {
        $original_file_path = storage_path('app/image/') . $directory . "/" . $img_name;
        $cropped_file_path = storage_path('app/image/') . $directory . "/" . 'desktop_' . $img_name;

        $img = ImageManagerStatic::make($original_file_path);
        $img->crop(1000, 1000);
        $img->save($cropped_file_path, 25);
    }

}