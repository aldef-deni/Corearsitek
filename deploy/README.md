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
