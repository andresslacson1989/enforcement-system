<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

trait ImageUploadTrait
{
    /**
     * Processes and stores an uploaded image, with compression.
     *
     * @param  UploadedFile  $file  The uploaded file from the request.
     * @param  string  $directory  The directory within 'public/storage' to save the file to.
     * @param  int  $quality  The compression quality for images (0-100).
     * @return string The path to the stored file.
     */
    protected function processAndStoreFile(UploadedFile $file, string $directory, int $quality = 75): string
    {
        $file_type = $file->getMimeType();

        // Check if the file is an image
        if (str_starts_with($file_type, 'image/')) {
            // Create an image manager instance with a driver
            $manager = new ImageManager(new Driver);

            // Read the uploaded image
            $image = $manager->read($file);

            // Generate a unique filename with a .jpg extension for consistency
            $filename = uniqid().'.jpg';

            // Encode the image with the specified quality
            $encoded_image = $image->toJpg($quality);

            // Define the storage path
            $path = "{$directory}/{$filename}";

            // Store the image in the public disk
            Storage::disk('public')->put($path, (string) $encoded_image);

            return $path;
        }

        // If it's not an image (e.g., a PDF), store it directly.
        return $file->store($directory, 'public');
    }
}
