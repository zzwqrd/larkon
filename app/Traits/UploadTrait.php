<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait UploadTrait
{
    /**
     * uploadAllTyps (Handles images and files)
     * Ported from perfume_admin.
     */
    public function uploadAllTyps($file, $directory, $width = null, $height = null)
    {
        if (!File::isDirectory('storage/images/' . $directory)) {
            File::makeDirectory('storage/images/' . $directory, 0777, true, true);
        }

        $fileMimeType = $file->getClientMimeType();
        $allowedImagesMimeTypes = ['image/jpeg', 'application/octet-stream', 'image/jpg', 'image/png'];

        // If it's an image and Intervention Image is NOT installed, fallback to standard upload
        if (in_array($fileMimeType, $allowedImagesMimeTypes)) {
            if (class_exists('Intervention\Image\Facades\Image') || class_exists('Image')) {
                return $this->uploadeImage($file, $directory, $width, $height);
            }
        }

        return $this->uploadFile($file, $directory);
    }

    public function uploadFile($file, $directory)
    {
        $filename = time() . rand(1000000, 9999999) . '.' . $file->getClientOriginalExtension();
        $path = 'images/' . $directory;
        $file->storeAs($path, $filename);
        return $filename;
    }

    /**
     * uploadeImage (Requires Intervention/Image)
     */
    public function uploadeImage($file, $directory, $width = null, $height = null)
    {
        // Safe check for Intervention Image
        if (class_exists('Image')) {
            $img = \Image::make($file)->orientate();
            $thumbsPath = public_path('storage/images/' . $directory);
            $name = time() . '_' . rand(1111, 9999) . '.' . $file->getClientOriginalExtension();

            if (null != $width && null != $height) {
                $img->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            if (!File::isDirectory($thumbsPath)) {
                File::makeDirectory($thumbsPath, 0777, true, true);
            }

            $img->save($thumbsPath . '/' . $name);
            return (string) $name;
        }

        // Fallback if class not found
        return $this->uploadFile($file, $directory);
    }

    public function deleteFile($file_name, $directory = 'unknown'): void
    {
        if ($file_name && $file_name != 'default.png' && file_exists(public_path("storage/images/$directory/$file_name"))) {
            unlink(public_path("storage/images/$directory/$file_name"));
        }
    }

    public function defaultImage($directory)
    {
        return asset("/storage/images/$directory/default.png");
    }

    public static function getImage($name, $directory)
    {
        return asset("storage/images/$directory/" . $name);
    }
}
