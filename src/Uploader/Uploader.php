<?php

namespace App\Uploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader implements UploaderInterface {
    
    /**
     * @var string
     */
    private $uploadsAbsoluteDir;

    /**
     * @var string
     */
    private $uploadsRelativeDir;
    
    public function __construct(string $uploadsAbsoluteDir, string $uploadsRelativeDir) {
        $this->uploadsAbsoluteDir = $uploadsAbsoluteDir;
        $this->uploadsRelativeDir = $uploadsRelativeDir;
    }

    public function upload(UploadedFile $file): string {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $newFilename = $safeFilename . '_' . uniqid() . '.' . $file->guessExtension();

        $file->move($this->uploadsAbsoluteDir, $newFilename);

        return $this->uploadsRelativeDir . '/' . $newFilename;
    }

}
