<?php
// src/Service/FileUploader.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file, $upload_destination)
    {
        // Renomme la pièce jointe
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // Ceci est nécessaire pour inclure en toute sécurité le nom du fichier dans l'URL
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        // Déplace le fichier vers le dossier ou les documents sont stockés
        try {
            $file->move($upload_destination, $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        return $fileName;
    }

}
