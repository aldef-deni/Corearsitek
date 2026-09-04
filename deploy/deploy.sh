#!/usr/bin/env bash
# ================================================================
# CoreArsitek — skrip deploy untuk corearsitek.aldeftech.com
#
# Server rumahchiara memakai aaPanel; lihat deploy/README.md untuk detailnya.
#
# Pemakaian di server:
#   cd /www/wwwroot/corearsitek.aldeftech.com && sudo bash deploy/deploy.sh
#
# Aman dijalankan berulang: dipakai juga untuk deploy berikutnya
# (tarik perubahan terbaru lalu jalankan lagi skrip ini).
#
# .env yang sudah ada TIDAK PERNAH ditimpa.
# ================================================================

set -euo pipefail

APP_DIR="${APP_DIR:-/www/wwwroot/corearsitek.aldeftech.com}"
WEB_USER="${WEB_USER:-www}"
BRANCH="${BRANCH:-main}"

say() { printf '\n\033[1;31m==>\033[0m %s\n' "$1"; }

cd "$APP_DIR"

# ---------------- 1. Ambil kode terbaru ----------------
say "Menarik kode terbaru dari branch $BRANCH"
git fetch --all --prune
git checkout "$BRANCH"
git pull --ff-only origin "$BRANCH"

# ---------------- 2. Dependensi PHP ----------------
say "Memasang dependensi composer (tanpa dev)"
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# ---------------- 3. Berkas .env ----------------
if [ ! -f .env ]; then
    say ".env belum ada — menyalin dari .env.production.example"
    cp .env.production.example .env
    echo
    echo "  !! Isi dulu kredensial di $APP_DIR/.env (database, mail), lalu"
    echo "     jalankan ulang skrip ini."
    echo
fi

# APP_KEY dibuat sekali saja; kalau sudah terisi jangan diganti
# (mengganti APP_KEY membuat semua sesi & data terenkripsi lama tidak terbaca).
if ! grep -qE '^APP_KEY=base64:' .env; then
    say "Membuat APP_KEY"
    php artisan key:generate --force
fi

# ---------------- 4. Database ----------------
# Baca satu nilai dari .env: ambil baris pertama yang cocok, buang komentar
# di belakang nilai dan tanda kutip pembungkusnya.
env_get() {
    sed -n "s/^[[:space:]]*$1=//p" .env \
        | head -n 1 \
        | sed -e 's/[[:space:]]\{1,\}#.*$//' -e 's/^[[:space:]]*//' -e 's/[[:space:]]*$//' \
              -e 's/^"\(.*\)"$/\1/' -e "s/^'\(.*\)'$/\1/"
}

DB_CONN="$(env_get DB_CONNECTION)"

if [ "$DB_CONN" = "sqlite" ]; then
    DB_PATH="$(env_get DB_DATABASE)"
    [ -n "$DB_PATH" ] || DB_PATH="$APP_DIR/database/database.sqlite"
    if [ ! -f "$DB_PATH" ]; then
        say "Membuat berkas SQLite di $DB_PATH"
        mkdir -p "$(dirname "$DB_PATH")"
        touch "$DB_PATH"
    fi
fi

say "Menjalankan migrasi"
php artisan migrate --force

# Seeder hanya untuk pemasangan pertama, supaya konten yang sudah
# diubah lewat dashboard admin tidak tertimpa nilai bawaan.
NEEDS_SEED="$(php artisan tinker --execute='echo \App\Models\SiteContent::count();' 2>/dev/null | tr -dc '0-9' || echo 0)"
if [ "${NEEDS_SEED:-0}" = "0" ]; then
    say "Database masih kosong — menjalankan seeder"
    php artisan db:seed --force
else
    say "Konten sudah ada ($NEEDS_SEED baris) — seeder dilewati"
fi

# ---------------- 5. Storage & izin berkas ----------------
say "Menyiapkan storage dan izin berkas"
php artisan storage:link || true
mkdir -p public/uploads storage/framework/{cache,sessions,views} storage/logs bootstrap/cache

if id -u "$WEB_USER" >/dev/null 2>&1; then
    # 2>/dev/null: .user.ini milik aaPanel diproteksi immutable, lewati saja.
    sudo chown -R "$WEB_USER":"$WEB_USER" storage bootstrap/cache public/uploads 2>/dev/null || true
    sudo find storage bootstrap/cache -type d -exec chmod 775 {} \;
    sudo find storage bootstrap/cache -type f -exec chmod 664 {} \;
fi

# ---------------- 6. Cache produksi ----------------
say "Membangun ulang cache konfigurasi, rute, dan view"
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ---------------- 7. Muat ulang PHP-FPM ----------------
if command -v systemctl >/dev/null 2>&1; then
    FPM_SERVICE="$(systemctl list-units --type=service --no-legend 'php*-fpm.service' 2>/dev/null | awk 'NR==1{print $1}')"
    if [ -n "${FPM_SERVICE:-}" ]; then
        say "Memuat ulang $FPM_SERVICE"
        sudo systemctl reload "$FPM_SERVICE"
    fi
fi

say "Deploy selesai — https://corearsitek.aldeftech.com"
