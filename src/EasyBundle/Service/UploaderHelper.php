<?php

namespace App\EasyBundle\Service;

use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class UploaderHelper
{
    private $uploadsPath;

    public function __construct(string $uploadsPath)
    {
        $this->uploadsPath = $uploadsPath;
    }

    public function uploadAndSetImages(Request $request, $entity)
    {
        foreach ($request->files as $files) {
            foreach ($files as $key => $file) {
                $method = sprintf('set%s', ucfirst($key));

                if ($file != null && method_exists($entity, $method)) {
                    $newFilename = $this->uploadFile($file);
                    $entity->$method($newFilename);
                }
            }
        }
    }

    protected function uploadFile(UploadedFile $file)
    {
        $destination = $this->uploadsPath;
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $newFilename = Urlizer::urlize($originalFilename) . '-' . uniqid() . '.' . $file->guessExtension();

        $result = $file->move(
            $destination,
            $newFilename
        );

        return $newFilename;
    }
}
