<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait FilesTrait
{
    /**
     * upload any type of files
     */
    public function uploadFile($file = null, $folderName = '', $withName = false)
    {
        if ($file !== null && File::isFile($file)) {
            if (!File::isDirectory('storage/uploads/' . $folderName)) {
                File::makeDirectory('storage/uploads/' . $folderName, 0777, true, true);
            }
            $extension = $file->getClientOriginalExtension();
            $name = time() . '_' . rand(1, 99999) . '.' . $extension;
            $destinationPath = public_path('storage/uploads/' . $folderName);
            $filePath = $destinationPath;
            $file->move($filePath, $name);
            if ($withName == false) {
                return $name;
            } else {
                return ['name' => $name, 'originalName' => $file->getClientOriginalName()];
            }
        }
    }

    /**
     * get the full path of the uploaded file
     */
    public function getFile($fileName = null, $folderName = '')
    {
        if (!$fileName || empty($fileName)) {
            return asset('defaults') . '/default.png';
        }

        if (stripos($fileName, 'http') === 0) {
            return $fileName;
        }

        $cleanFileName = ltrim($fileName, '/');
        $cleanFileName = str_replace(['storage/', 'public/'], '', $cleanFileName);
        $cleanFileName = ltrim($cleanFileName, '/');

        $folders = [
            'storage/avatar/',
            'storage/uploads/',
            'storage/',
            'avatar/',
            'uploads/',
            '',
        ];

        foreach ($folders as $folder) {
            $pathVariations = [];
            if ($folderName) {
                $pathVariations[] = public_path($folder . $folderName . '/' . $cleanFileName);
            }
            $pathVariations[] = public_path($folder . $cleanFileName);

            foreach ($pathVariations as $path) {
                $normalizedPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
                if (file_exists($normalizedPath)) {
                    $relativePath = str_replace(public_path(), '', $normalizedPath);
                    return asset(ltrim($relativePath, DIRECTORY_SEPARATOR));
                }
            }
        }

        if ($folderName && file_exists(public_path('defaults/' . $folderName . '/default.png'))) {
            return asset('defaults/' . $folderName) . '/default.png';
        }

        return asset('defaults') . '/default.png';
    }

    /**
     * delete the uploaded file
     */
    public function deleteFile($fileName = null, $folderName = '')
    {
        if ($fileName !== null && $fileName !== 'default.png' && File::exists(public_path('storage/uploads/' . $folderName) . '/' . $fileName)) {
            File::delete(public_path('storage/uploads/' . $folderName) . '/' . $fileName);
        }
    }

    /**
     * saveAvatar (Requires Laravolt/Avatar package)
     * Note: Commented out to prevent errors if package is missing.
     */
    // public function saveAvatar($userName, $directory, $id) { ... }

    public static function getImage($name, $directory)
    {
        return asset("storage/uploads/$directory/" . $name);
    }
}
