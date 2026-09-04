<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;

class UploadHelper
{
    /**
     * Simpan file gambar ke public/uploads dan kembalikan path publiknya.
     */
    public static function image(UploadedFile $file): string
    {
        $name = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads'), $name);

        return 'uploads/' . $name;
    }

    /**
     * Hapus file lama jika memang disimpan di public/uploads (bukan file seed).
     */
    public static function deleteIfExists(?string $path): void
    {
        if ($path && str_starts_with($path, 'uploads/') && file_exists(public_path($path))) {
            unlink(public_path($path));
        }
    }
}