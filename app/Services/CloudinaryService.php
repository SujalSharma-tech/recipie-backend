<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;

class CloudinaryService
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key' => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
        ]);
    }

    public function uploadImage(UploadedFile $file, string $folder = 'recipes')
    {
        $response = $this->cloudinary->uploadApi()->upload(
            $file->getRealPath(),
            ['folder' => $folder]
        );

        return $response['secure_url'];
    }
}
