# Deploy CoreArsitek ke corearsitek.aldeftech.com

Server: VM GCP **rumahchiara** (`aldef-tech`, zona `asia-southeast2-b`, IP `34.50.78.9`),
Ubuntu 26.04, dikelola **aaPanel**.

Karena servernya pakai aaPanel, nginx/PHP/MySQL diurus panel — bukan
`/etc/nginx/sites-available` dan bukan certbot manual.

| Komponen | Lokasi / versi |
|---|---|
| Folder aplikasi | `/www/wwwroot/corearsitek.aldeftech.com` |
| Docroot nginx | `/www/wwwroot/corearsitek.aldeftech.com/public` |
| vhost | `/www/server/panel/vhost/nginx/corearsitek.aldeftech.com.conf` |
| Rewrite rule | `/www/server/panel/vhost/rewrite/corearsitek.aldeftech.com.conf` |
| Sertifikat SSL | `/www/server/panel/vhost/cert/corearsitek.aldeftech.com/` (dikelola aaPanel) |
| PHP | 8.4 (`include enable-php-84.conf`) |
| Database | MySQL 8.0, `sql_corearsitek_aldeftech_com` |
| User sistem | `www` |

## Masuk ke server

```bash
gcloud compute ssh rumahchiara --zone=asia-southeast2-b
```

## Deploy rutin

```bash
cd /www/wwwroot/corearsitek.aldeftech.com
sudo bash deploy/deploy.sh
```

Skrip menarik perubahan dari `main`, memasang dependensi, menjalankan migrasi,
membangun ulang cache, memperbaiki izin berkas, lalu memuat ulang PHP-FPM.
`.env` yang sudah ada tidak pernah ditimpa, dan seeder hanya jalan saat tabel
konten masih kosong — konten yang sudah diubah lewat dashboard admin tetap aman.

## Catatan pemasangan awal (sudah dikerjakan)

Disimpan sebagai rujukan kalau perlu memasang ulang dari nol.

1. Repo di-`git init` langsung di folder situs yang sudah dibuat aaPanel, lalu
   `git reset --hard origin/main`. Berkas bawaan panel yang dipertahankan:
   `.user.ini`, `.well-known/`, `404.html`, `502.html`. `index.html` dihapus
   supaya tidak menutupi Laravel.
2. `.env` disalin dari `.env.production.example`, kredensial database diisi,
   lalu `php artisan key:generate`.
3. Docroot vhost diubah satu baris — ini satu-satunya suntingan pada berkas
   yang dikelola aaPanel:

   ```nginx
   # dari
   root /www/wwwroot/corearsitek.aldeftech.com;
   # menjadi
   root /www/wwwroot/corearsitek.aldeftech.com/public;
   ```

   Cadangan sebelum diubah ada di
   `/www/server/panel/vhost/nginx/corearsitek.aldeftech.com.conf.bak-prelaravel`.

   Rewrite rule bawaan panel (`try_files $uri $uri/ /index.php$is_args$query_string`)
   kebetulan sudah cocok untuk Laravel, jadi tidak diubah.

4. `sudo nginx -t && sudo nginx -s reload`.

**Jangan** menimpa vhost itu dengan berkas dari repo — konfigurasi SSL, HTTP/3,
dan error page bawaan aaPanel ada di dalamnya. Kalau perlu ubah, sunting satu
baris yang bersangkutan saja, atau lewat UI aaPanel.

`.user.ini` diproteksi immutable oleh panel, jadi `chown -R` akan gagal di berkas
itu. Skrip deploy sudah mengabaikannya.

## Seeder

Jangan jalankan `php artisan db:seed` polos di server. `DatabaseSeeder` memakai
`updateOrCreate` untuk konten situs, sehingga teks, gambar, dan info kontak akan
kembali ke nilai bawaan dan perubahan yang dibuat lewat dashboard hilang.

Untuk mengisi tabel baru, panggil kelas seeder-nya saja:

```bash
sudo php artisan db:seed --class=BannerSeeder --force
sudo php artisan db:seed --class=ProcessStepSeeder --force
sudo php artisan db:seed --class=TestimonialSeeder --force
```

## Batas ukuran unggahan

Rantai batasnya ada tiga lapis dan **harus naik bersamaan**; menaikkan satu
saja tidak menyelesaikan masalah:

| Lapis | Nilai | Lokasi |
|---|---|---|
| nginx `client_max_body_size` | 128m | vhost situs ini saja (situs lain tetap 50m dari nginx.conf) |
| PHP `post_max_size` | 128M | `/www/server/php/84/etc/php.ini` |
| PHP `upload_max_filesize` | 50M | idem, batas per berkas |
| Aplikasi | 15 MB/berkas, 8 berkas, total 115 MB | `App\Support\UploadHelper` |

Kalau batas nginx lebih kecil dari yang dikirim, muncul halaman **413 Request
Entity Too Large** mentah sebelum Laravel sempat menampilkan pesan apa pun.
Kalau `post_max_size` PHP lebih kecil dari batas nginx, PHP membuang isi POST
diam-diam sehingga formulir tampak terkirim padahal datanya kosong — ini lebih
membingungkan lagi karena tidak ada pesan kesalahan sama sekali.

Batas aplikasi sengaja disisakan di bawah batas server, dan `public/js/admin.js`
memeriksanya di peramban sebelum formulir dikirim supaya admin mendapat pesan
yang jelas beserta nama berkasnya.

Menaikkan lagi: sunting `client_max_body_size` di vhost, `post_max_size` di
php.ini, lalu `MAX_UPLOAD_KB` / `MAX_BATCH` / `MAX_TOTAL_KB` di UploadHelper.
Setelahnya `sudo nginx -s reload` dan `sudo /etc/init.d/php-fpm-84 reload`.

## Ekstensi PHP exif

Dipasang manual pada 2026-09-04 karena tidak ikut bawaan aaPanel. Tanpa exif,
GD membuang tag rotasi saat gambar disimpan ulang sehingga foto dari ponsel
bisa tampil miring setelah dikompres.

Kalau PHP 8.4 di panel pernah di-upgrade atau dipasang ulang, ekstensi ini
kemungkinan hilang dan perlu dibangun lagi:

```bash
P=/www/server/php/84
cd $P/src/ext/exif
sudo $P/bin/phpize
sudo ./configure --with-php-config=$P/bin/php-config
sudo make -j$(nproc) && sudo make install

# Daftarkan di kedua berkas ini: php.ini dipakai FPM, php-cli.ini dipakai artisan
for f in php.ini php-cli.ini; do
  sudo sed -i 's|^extension = zip.so|extension = zip.so
extension = exif.so|' $P/etc/$f
done

sudo /etc/init.d/php-fpm-84 reload
```

Cek hasilnya dengan `php -m | grep exif`. PHP 8.4 dipakai bersama seluruh situs
di VM ini, jadi lakukan `reload` (bukan `restart`) supaya situs lain tidak putus.

## Email pemberitahuan pengajuan

Formulir pengajuan di halaman `/kontak` menyimpan datanya ke tabel `submissions`
(menu **Data Pengajuan** di dashboard) lalu mengirim pemberitahuan email.

Selama `.env` masih berisi `MAIL_MAILER=log`, **emailnya tidak dikirim ke mana pun**
— hanya ditulis ke `storage/logs/laravel.log`. Pengajuannya tetap masuk dashboard,
dan pada halaman detailnya akan tampak keterangan status pengiriman.

Untuk benar-benar mengirim lewat Gmail:

1. Aktifkan verifikasi dua langkah pada akun pengirim.
2. Buat App Password 16 karakter di <https://myaccount.google.com/apppasswords>.
3. Sunting `/www/wwwroot/corearsitek.aldeftech.com/.env`:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_SCHEME=smtps
MAIL_USERNAME=corearsitek@gmail.com
MAIL_PASSWORD="app-password-16-karakter"
MAIL_FROM_ADDRESS="corearsitek@gmail.com"
MAIL_FROM_NAME="CoreArsitek"
```

`MAIL_SCHEME` hanya menerima `smtp` atau `smtps`. Nilai `tls` atau `ssl` ditolak
dengan pesan *The "tls" scheme is not supported*. Kombinasi yang benar: port 465
dengan `MAIL_SCHEME=smtps`, atau port 587 dengan `MAIL_SCHEME=null` (STARTTLS).

4. Muat ulang cache konfigurasi: `sudo -u www php artisan config:cache`.

`MAIL_FROM_ADDRESS` harus sama dengan `MAIL_USERNAME`, kalau berbeda Gmail menolak
pengirimannya. Alamat **penerima** pengajuan tidak diatur di `.env`, melainkan di
dashboard: **Konten Situs → Halaman Kontak & Pengajuan → Email Penerima Pengajuan**
(bawaannya `corearsitek@gmail.com`).

Uji cepat dari server:

```bash
cd /www/wwwroot/corearsitek.aldeftech.com
sudo -u www php artisan tinker --execute="Mail::raw('uji kirim', fn(\$m) => \$m->to('corearsitek@gmail.com')->subject('Uji CoreArsitek'));"
```

## Akun admin

Seeder **tidak lagi** memuat password bawaan (repo ini publik). Saat akun admin
pertama dibuat:

- kalau `ADMIN_PASSWORD` diisi di `.env`, password itu yang dipakai;
- kalau kosong, seeder membuat password acak dan menampilkannya sekali di
  terminal — catat saat itu juga.

Ganti password kapan saja lewat `/admin/password` setelah login.

## Kebutuhan di server

PHP 8.2+ dengan `mbstring`, `xml`, `curl`, `zip`, `gd`, `intl`, `pdo_mysql`;
Composer 2; nginx + PHP-FPM; MySQL 8.

Tidak perlu Node.js: seluruh CSS dan JS frontend berupa berkas statis di
`public/css` dan `public/js`, bukan hasil build Vite.
