<?php

namespace App\Uploader;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploaderInterface {
    
    public function upload(UploadedFile $file): string;
    
}
