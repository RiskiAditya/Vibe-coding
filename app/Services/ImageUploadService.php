<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageUploadService
{
    /**
     * Upload an equipment image.
     */
    public function uploadImage(UploadedFile $file): string
    {
        if (!$this->validateImage($file)) {
            throw new \Exception('Invalid image file.');
        }

        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('equipment', $filename, 'public');

        return $path;
    }

    /**
     * Delete an equipment image.
     */
    public function deleteImage(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }

    /**
     * Validate image file.
     */
    public function validateImage(UploadedFile $file): bool
    {
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
        $maxSize = 2048 * 1024; // 2MB in bytes

        return in_array($file->getMimeType(), $allowedMimes) && $file->getSize() <= $maxSize;
    }
}
