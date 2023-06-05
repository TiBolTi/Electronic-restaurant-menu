<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    public static function upload(UploadedFile $file, $dir, $prefix)
    {
        if (!file_exists(storage_path('app/public/files/'))) {
            mkdir(storage_path('app/public/files/'), 0777);
        }
        if (!file_exists(storage_path('app/public/files/' . $dir))) {
            mkdir(storage_path('app/public/files/' . $dir), 0777);
        }
        $filename = $dir . '/' . uniqid($prefix . '_') . '.' . mb_strtolower($file->getClientOriginalExtension());
        move_uploaded_file($file, storage_path('app/public/files/'.$filename));

        return $filename;
    }
}
