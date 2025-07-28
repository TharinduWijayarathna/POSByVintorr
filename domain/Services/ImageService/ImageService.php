<?php

namespace domain\Services\ImageService;

use App\Models\Image;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

/**
 * ImageService
 * php version 8
 *
 * @category Service
 *
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 *
 * @link     https://emergentspark.com
 * */
class ImageService
{
    protected $image;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->image = new Image;
    }

    /**
     * Convert Image to WebP format and store it
     *
     * @param  mixed  $image
     * @param  int  $quality
     *
     * @throws \Exception
     */
    public function convertImageToWebP($image, $quality = 80): Image
    {
        if (isset($image)) {
            // Store the original image temporarily
            $originalPath = $image->store('tmp');
            $sourcePath = storage_path('app/'.$originalPath);

            // Define the path for the WebP image
            $webpPath = storage_path('app/public/webp/').uniqid().'.webp';

            $info = getimagesize($sourcePath);
            $isConverted = false;

            if ($info['mime'] == 'image/jpeg') {
                $imageResource = imagecreatefromjpeg($sourcePath);
            } elseif ($info['mime'] == 'image/gif') {
                $imageResource = imagecreatefromgif($sourcePath);
            } elseif ($info['mime'] == 'image/png') {
                $imageResource = imagecreatefrompng($sourcePath);
                imagepalettetotruecolor($imageResource);
            } else {
                throw new \Exception('Unsupported image type');
            }

            // Ensure the directory exists
            $destinationDirectory = dirname($webpPath);
            if (! file_exists($destinationDirectory)) {
                mkdir($destinationDirectory, 0755, true);
            }

            // Convert and save the image to WebP format
            if (imagewebp($imageResource, $webpPath, $quality)) {
                $isConverted = true;
            }

            // Free up memory
            imagedestroy($imageResource);

            if ($isConverted) {
                // Call the store method to handle storage
                $file = new File($webpPath);
                $storedImage = $this->store($file);

                // Remove the temporary files
                Storage::delete($originalPath);
                unlink($webpPath);

                return $storedImage;
            } else {
                throw new \Exception('Image conversion failed');
            }
        }

        throw new \Exception('No image provided');
    }

    /**
     * Store
     * Store image data
     *
     * @param  mixed  $image
     */
    public function store($image): Image
    {
        if (isset($image)) {
            // Store the file and get the relative path
            $filePath = Storage::disk('public')->put('images', $image);

            // Generate the full URL path including the domain
            $data['name'] = asset('storage/'.$filePath);

            return $this->image->create($data);
        }

        throw new \Exception('No image provided');
    }

    /**
     * Delete
     * delete image data
     *
     * @param  mixed  $image
     * @return void
     */
    public function delete($image)
    {
        if (isset($image)) {
            Storage::disk('public')->delete('images/'.$image->name);
            $image->delete();
        }
    }
}
