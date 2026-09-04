# Deploy CoreArsitek ke corearsitek.aldeftech.com

Target: VM GCP **rumahchiara** (`asia-southeast2-b`, IP publik `34.50.78.9`).
DNS `corearsitek.aldeftech.com` sudah mengarah ke IP tersebut.

## Sekali di awal

```bash
# 1. Ambil kode
sudo mkdir -p /var/www && sudo chown "$USER":"$USER" /var/www
git clone https://github.com/aldef-deni/Corearsitek.git /var/www/corearsitek
cd /var/www/corearsitek

# 2. Siapkan .env lalu isi kredensialnya
cp .env.production.example .env
nano .env          # isi DB_USERNAME, DB_PASSWORD, MAIL_*

# 3. Kalau pakai MySQL, buat dulu databasenya
sudo mysql -e "CREATE DATABASE IF NOT EXISTS corearsitek CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 4. Jalankan deploy (composer, key:generate, migrate, seed, cache, izin berkas)
bash deploy/deploy.sh

# 5. Pasang vhost nginx
sudo cp deploy/nginx-corearsitek.conf /etc/nginx/sites-available/corearsitek.aldeftech.com
sudo ln -sfn /etc/nginx/sites-available/corearsitek.aldeftech.com \
             /etc/nginx/sites-enabled/corearsitek.aldeftech.com
sudo nginx -t && sudo systemctl reload nginx

# 6. Pasang sertifikat HTTPS
sudo certbot --nginx -d corearsitek.aldeftech.com
```

Sesuaikan `fastcgi_pass` di vhost dengan versi PHP-FPM yang terpasang:

```bash
ls /run/php/           # mis. php8.3-fpm.sock
```

## Deploy berikutnya

```bash
cd /var/www/corearsitek && bash deploy/deploy.sh
```

Skrip menarik perubahan dari branch `main`, memasang dependensi, menjalankan
migrasi, membangun ulang cache, lalu memuat ulang PHP-FPM. `.env` yang sudah ada
tidak pernah ditimpa, dan seeder hanya jalan saat tabel konten masih kosong —
jadi konten yang sudah diubah lewat dashboard admin tetap aman.

## Login admin pertama

Seeder membuat akun bawaan:

- Email: `admin@corearsitek.com`
- Password: `admin123`

**Segera ganti password** lewat `/admin/password` setelah login pertama.

## Kebutuhan di server

- PHP 8.2+ beserta ekstensi `mbstring`, `xml`, `curl`, `zip`, `gd`,
  dan `pdo_mysql` (atau `pdo_sqlite`)
- Composer 2
- nginx + PHP-FPM
- certbot (untuk HTTPS)

Tidak perlu Node.js: seluruh CSS dan JS frontend berupa berkas statis di
`public/css` dan `public/js`, bukan hasil build Vite.
