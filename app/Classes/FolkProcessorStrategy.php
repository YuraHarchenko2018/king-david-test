<?php

namespace App\Classes;

use App\Interfaces\ImageProcessorInterface;
use Folklore\Image\Facades\Image as FolkImage;

class FolkProcessorStrategy implements ImageProcessorInterface 
{

    private $mobile_settings = [
        'width' => 400,
        'height' => 800,
        'crop' => true
    ];

    private $desktop_settings = [
        'width' => 1000,
        'height' => 1000,
        'crop' => true
    ];

    public function cropMobile($img_name, $directory) {
        $original_file_path = storage_path('app/image/') . $directory . "/" . $img_name;
        $cropped_file_path = storage_path('app/image/') . $directory . "/" . 'mobile_' . $img_name;

        $img = FolkImage::make($original_file_path, $this->mobile_settings);
        $img->save($cropped_file_path);
    }

    public function cropDesktop($img_name, $directory) {
        $original_file_path = storage_path('app/image/') . $directory . "/" . $img_name;
        $cropped_file_path = storage_path('app/image/') . $directory . "/" . 'desktop_' . $img_name;

        $img = FolkImage::make($original_file_path, $this->desktop_settings);
        $img->save($cropped_file_path);
    }

}