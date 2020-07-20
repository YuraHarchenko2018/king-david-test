<?php

namespace App\Interfaces;

interface ImageProcessorInterface {

    public function cropMobile($img_name, $directory);

    public function cropDesktop($img, $directory);

}