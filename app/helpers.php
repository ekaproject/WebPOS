<?php

if (! function_exists('storage_img')) {
    /**
     * Kembalikan URL gambar dari public/storage.
     * Jika file tidak ada, kembalikan URL no-image.svg.
     */
    function storage_img(?string $path): string
    {
        if ($path && file_exists(public_path('storage/' . $path))) {
            return asset('storage/' . $path);
        }

        return asset('images/no-image.svg');
    }
}
