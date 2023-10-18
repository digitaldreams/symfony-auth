<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImageUploadService
{
    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function upload(File $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid('user_', true) . '.' . $file->guessExtension();

        // Move the file to the directory where avatar are stored
        $file->move(
            'images',
            $newFilename
        );

        return '/images/' . $newFilename;
    }
}