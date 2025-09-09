<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Download an image from a URL and store it locally.
     *
     * @param string $url The external image URL
     * @param string $filenamePrefix Prefix for the filename (e.g., product slug)
     * @param string $directory Directory to store the image (relative to public disk)
     * @param string $storage disk (switch to img for render purpose)
     * @return string The local URL to the stored image
     */
    public static function downloadAndStoreImage(string $url, string $filenamePrefix, string $directory = 'images', $disk = 'local'): string
    {
        try {
            // Fetch the image using Laravel's HTTP client

            // Determine if in development mode
            $isDevMode = app()->environment('local', 'development');

            // Configure HTTP client with retry and conditional SSL verification
            $httpClient = Http::retry(3, 1000); // Retry 3 times with 1-second delay
            if ($isDevMode) {
                $httpClient = $httpClient->withOptions(['verify' => false]);
            }

            // Fetch the image
            $response = $httpClient->get($url);

            if ($response->successful()) {
                // Generate a unique filename based on the prefix and timestamp
                $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
                // $filename = $directory . '/' . Str::slug($filenamePrefix) . '-' . time() . '.' . ($extension ?: 'jpg');
                $filename = $directory . '/' . Str::slug($filenamePrefix) . '-' . '.' . ($extension ?: 'jpg');

                // Store the image in the public disk
                Storage::disk($disk)->put($filename, $response->body());

                // Return the local URL
                return $filename;
            } else {
                // Log error and return default image
                Log::warning("Failed to download image from {$url}. Status: {$response->status()}");
                return asset('img/placeholder.svg');
            }
        } catch (\Exception $e) {
            // Log error and return default image
            Log::error("Error downloading image from {$url}: {$e->getMessage()}");
            return asset('img/placeholder.svg');
        }
    }
}
