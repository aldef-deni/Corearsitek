<?php

/**
 * Pesan validasi bahasa Indonesia.
 *
 * Hanya memuat aturan yang benar-benar dipakai dashboard ini. Aturan lain
 * tetap memakai pesan bawaan Laravel dalam bahasa Inggris.
 */
return [
    'required' => ':attribute wajib diisi.',
    'string' => ':attribute harus berupa teks.',
    'integer' => ':attribute harus berupa angka bulat.',
    'boolean' => ':attribute harus bernilai ya atau tidak.',
    'email' => ':attribute harus berupa alamat email yang sah.',
    'image' => ':attribute harus berupa gambar.',
    'mimes' => ':attribute harus berformat: :values.',
    'mimetypes' => ':attribute harus berformat: :values.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'unique' => ':attribute sudah dipakai.',
    'array' => ':attribute harus berupa daftar.',
    'in' => ':attribute yang dipilih tidak sah.',
    'url' => ':attribute harus berupa tautan yang sah.',
    'numeric' => ':attribute harus berupa angka.',

    'min' => [
        'numeric' => ':attribute minimal :min.',
        'file' => ':attribute minimal berukuran :min kilobyte.',
        'string' => ':attribute minimal :min karakter.',
        'array' => ':attribute minimal berisi :min item.',
    ],

    'max' => [
        'numeric' => ':attribute maksimal :max.',
        'file' => 'Ukuran :attribute maksimal :max kilobyte.',
        'string' => ':attribute maksimal :max karakter.',
        'array' => ':attribute maksimal berisi :max item.',
    ],

    /**
     * Nama kolom agar pesannya terbaca wajar, bukan "image" atau "sort_order".
     */
    'attributes' => [
        'image' => 'Gambar',
        'avatar' => 'Foto',
        'title' => 'Judul',
        'subtitle' => 'Subjudul',
        'badge_text' => 'Teks badge',
        'button_text' => 'Teks tombol',
        'button_url' => 'Link tombol',
        'page' => 'Halaman',
        'sort_order' => 'Urutan',
        'name' => 'Nama',
        'email' => 'Email',
        'phone' => 'Telepon',
        'position' => 'Jabatan',
        'bio' => 'Bio',
        'password' => 'Password',
        'current_password' => 'Password saat ini',
        'password_confirmation' => 'Konfirmasi password',
        'quote' => 'Testimoni',
        'role' => 'Keterangan',
        'icon' => 'Ikon',
        'description' => 'Keterangan',
        'label' => 'Label',
        'subtitle_text' => 'Subjudul',
    ],
];
