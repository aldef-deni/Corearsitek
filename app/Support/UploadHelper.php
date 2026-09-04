<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;

class UploadHelper
{
    /** Batas ukuran unggahan dalam kilobyte (15 MB). */
    public const MAX_UPLOAD_KB = 15360;

    /** Format yang boleh diunggah. */
    public const ALLOWED_MIMES = 'jpg,jpeg,png';

    /** Banyak berkas maksimal dalam satu kali unggah beruntun. */
    public const MAX_BATCH = 8;

    /**
     * Batas total satu permintaan, disisakan di bawah client_max_body_size
     * nginx (128 MB) dan post_max_size PHP. Kalau terlampaui, nginx menolak
     * dengan halaman 413 mentah sebelum Laravel sempat menampilkan pesan,
     * jadi batas ini juga diperiksa di sisi peramban.
     */
    public const MAX_TOTAL_KB = 117760;

    /** Sisi terpanjang gambar setelah dikompres. */
    private const MAX_EDGE = 2200;

    private const WEBP_QUALITY = 82;
    private const JPEG_QUALITY = 82;

    /**
     * Batas jumlah piksel yang masih aman diproses GD. Di atas ini gambar
     * disimpan apa adanya, lebih baik berkas besar daripada proses gagal.
     */
    private const MAX_PIXELS = 60000000;

    /**
     * Aturan validasi unggahan gambar, dipakai seragam di semua controller.
     */
    public static function rules(bool $required = false): array
    {
        return [
            $required ? 'required' : 'nullable',
            'image',
            'mimes:' . self::ALLOWED_MIMES,
            'max:' . self::MAX_UPLOAD_KB,
        ];
    }

    /** Keterangan singkat untuk ditampilkan di bawah kolom unggah. */
    public static function hint(): string
    {
        return 'Format JPG, JPEG, atau PNG. Maksimal ' . (int) (self::MAX_UPLOAD_KB / 1024)
            . ' MB per berkas. Gambar otomatis dikompres agar halaman tetap ringan.';
    }

    /** Keterangan tambahan untuk kolom unggah banyak berkas sekaligus. */
    public static function batchHint(): string
    {
        return 'Maksimal ' . self::MAX_BATCH . ' foto sekali unggah, total '
            . (int) (self::MAX_TOTAL_KB / 1024) . ' MB. ' . self::hint();
    }

    /**
     * Simpan gambar ke public/uploads dalam bentuk terkompresi,
     * lalu kembalikan path publiknya.
     */
    public static function image(UploadedFile $file): string
    {
        $dir = public_path('uploads');

        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $base = time() . '_' . uniqid();
        $compressed = self::compress($file, $dir, $base);

        if ($compressed !== null) {
            return 'uploads/' . $compressed;
        }

        // Kompresi tidak memungkinkan (format tak dikenal, gambar terlalu
        // besar, atau GD gagal) — simpan berkas aslinya supaya unggahan
        // tetap berhasil.
        $name = $base . '.' . strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $file->move($dir, $name);

        return 'uploads/' . $name;
    }

    /**
     * Kompres gambar dengan GD. Mengembalikan nama berkas hasil,
     * atau null bila gambar sebaiknya disimpan apa adanya.
     */
    private static function compress(UploadedFile $file, string $dir, string $base): ?string
    {
        if (! function_exists('imagecreatefromjpeg')) {
            return null;
        }

        $path = $file->getRealPath();

        if (! $path || ! is_readable($path)) {
            return null;
        }

        $info = @getimagesize($path);

        if (! $info) {
            return null;
        }

        [$width, $height] = $info;
        $type = $info[2];

        if ($width < 1 || $height < 1 || $width * $height > self::MAX_PIXELS) {
            return null;
        }

        $restoreMemory = self::raiseMemoryFor($width, $height);

        try {
            $src = match ($type) {
                IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
                IMAGETYPE_PNG => @imagecreatefrompng($path),
                default => null,
            };

            if (! $src) {
                return null;
            }

            $src = self::applyExifOrientation($src, $path, $type);
            $src = self::downscale($src, self::MAX_EDGE);

            $hasAlpha = $type === IMAGETYPE_PNG && self::hasAlpha($src);

            // WebP jauh lebih ringan dan tetap mendukung transparansi.
            if (function_exists('imagewebp')) {
                $name = $base . '.webp';
                imagepalettetotruecolor($src);
                imagealphablending($src, false);
                imagesavealpha($src, true);
                $ok = imagewebp($src, $dir . '/' . $name, self::WEBP_QUALITY);
            } elseif ($hasAlpha) {
                // Tanpa WebP, gambar bertransparansi harus tetap PNG;
                // JPEG akan menghitamkan bagian transparannya.
                $name = $base . '.png';
                imagealphablending($src, false);
                imagesavealpha($src, true);
                $ok = imagepng($src, $dir . '/' . $name, 8);
            } else {
                $name = $base . '.jpg';
                $ok = imagejpeg($src, $dir . '/' . $name, self::JPEG_QUALITY);
            }

            imagedestroy($src);

            return $ok ? $name : null;
        } catch (\Throwable) {
            return null;
        } finally {
            $restoreMemory();
        }
    }

    /**
     * Perkecil gambar bila sisi terpanjangnya melebihi $maxEdge.
     */
    private static function downscale(\GdImage $src, int $maxEdge): \GdImage
    {
        $width = imagesx($src);
        $height = imagesy($src);
        $longest = max($width, $height);

        if ($longest <= $maxEdge) {
            return $src;
        }

        $ratio = $maxEdge / $longest;
        $newWidth = max(1, (int) round($width * $ratio));
        $newHeight = max(1, (int) round($height * $ratio));

        $dst = imagecreatetruecolor($newWidth, $newHeight);

        // Jaga transparansi selama proses perkecilan.
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 0, 0, 0, 127));

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($src);

        return $dst;
    }

    /**
     * Putar gambar sesuai tag EXIF Orientation. GD membuang metadata EXIF
     * saat menyimpan ulang, jadi rotasinya harus dibakukan lebih dulu —
     * kalau tidak, foto dari ponsel bisa tampil miring setelah dikompres.
     * Dilewati bila ekstensi exif tidak terpasang.
     */
    private static function applyExifOrientation(\GdImage $src, string $path, int $type): \GdImage
    {
        if ($type !== IMAGETYPE_JPEG || ! function_exists('exif_read_data')) {
            return $src;
        }

        $exif = @exif_read_data($path);
        $orientation = (int) ($exif['Orientation'] ?? 0);

        $rotation = match ($orientation) {
            3 => 180,
            6 => -90,
            8 => 90,
            default => 0,
        };

        if ($rotation === 0) {
            return $src;
        }

        $rotated = @imagerotate($src, $rotation, 0);

        if (! $rotated) {
            return $src;
        }

        imagedestroy($src);

        return $rotated;
    }

    private static function hasAlpha(\GdImage $src): bool
    {
        $width = imagesx($src);
        $height = imagesy($src);

        // Cukup memindai kisi jarang; memeriksa tiap piksel terlalu mahal
        // untuk gambar besar dan tidak sepadan hasilnya.
        $stepX = max(1, (int) ($width / 40));
        $stepY = max(1, (int) ($height / 40));

        for ($x = 0; $x < $width; $x += $stepX) {
            for ($y = 0; $y < $height; $y += $stepY) {
                if (((imagecolorat($src, $x, $y) >> 24) & 0x7F) > 0) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Naikkan memory_limit sementara sesuai perkiraan kebutuhan GD,
     * lalu kembalikan closure untuk memulihkannya.
     */
    private static function raiseMemoryFor(int $width, int $height): \Closure
    {
        $original = ini_get('memory_limit');

        // GD memegang gambar sumber dan hasil sekaligus, masing-masing
        // 4 byte per piksel, ditambah kelonggaran untuk framework.
        $needBytes = (int) ($width * $height * 4 * 2.4) + 96 * 1024 * 1024;
        $needMb = (int) ceil($needBytes / 1048576);

        if ($original !== '-1' && $needMb > self::toMegabytes($original)) {
            @ini_set('memory_limit', $needMb . 'M');
        }

        return function () use ($original) {
            @ini_set('memory_limit', $original);
        };
    }

    private static function toMegabytes(string $value): int
    {
        $value = trim($value);
        $unit = strtolower(substr($value, -1));
        $number = (int) $value;

        return match ($unit) {
            'g' => $number * 1024,
            'm' => $number,
            'k' => (int) ceil($number / 1024),
            default => (int) ceil($number / 1048576),
        };
    }

    /**
     * Hapus berkas lama bila memang tersimpan di public/uploads
     * (bukan berkas bawaan seed).
     */
    public static function deleteIfExists(?string $path): void
    {
        if ($path && str_starts_with($path, 'uploads/') && file_exists(public_path($path))) {
            unlink(public_path($path));
        }
    }
}
