#!/bin/bash

echo "=========================================="
echo "WebPBLS4 Deployment Script untuk Niagahoster"
echo "=========================================="
echo ""

# Exit on error
set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Step 1: Navigate to public_html
echo -e "${YELLOW}[1/14]${NC} Navigasi ke folder public_html..."
cd ~/public_html || { echo -e "${RED}Error: Tidak bisa akses ~/public_html${NC}"; exit 1; }
echo -e "${GREEN}✓ Sudah di folder public_html${NC}"
echo ""

# Step 2: Check if .env.production exists
if [ ! -f ".env.production" ]; then
    echo -e "${RED}ERROR: File .env.production tidak ditemukan!${NC}"
    echo "Pastikan Anda sudah upload .env.production ke folder public_html"
    exit 1
fi

# Step 3: Backup existing .env
if [ -f ".env" ]; then
    echo -e "${YELLOW}[2/14]${NC} Backup .env lama..."
    cp .env .env.backup
    echo -e "${GREEN}✓ Backup tersimpan: .env.backup${NC}"
else
    echo -e "${YELLOW}[2/14]${NC} .env tidak ada (skipped backup)${NC}"
fi
echo ""

# Step 4: Copy .env.production -> .env
echo -e "${YELLOW}[3/14]${NC} Copy .env.production -> .env..."
cp .env.production .env
echo -e "${GREEN}✓ .env sudah di-copy${NC}"
echo ""

# Step 5: Install composer dependencies
echo -e "${YELLOW}[4/14]${NC} Install Composer dependencies (production mode)..."
if command -v composer &> /dev/null; then
    composer install --optimize-autoloader --no-dev --no-interaction
    echo -e "${GREEN}✓ Composer install selesai${NC}"
else
    echo -e "${YELLOW}[!] composer tidak ditemukan, skip step ini${NC}"
    echo "    Anda perlu jalankan: composer install --optimize-autoloader --no-dev --no-interaction"
fi
echo ""

# Step 6: Generate APP_KEY
echo -e "${YELLOW}[5/14]${NC} Generate APP_KEY..."
php artisan key:generate --force
echo -e "${GREEN}✓ APP_KEY sudah di-generate${NC}"
echo ""

# Step 7: Create storage link
echo -e "${YELLOW}[6/14]${NC} Create storage link..."
php artisan storage:link 2>/dev/null || echo -e "${YELLOW}[!] Storage link sudah ada atau tidak diperlukan${NC}"
echo -e "${GREEN}✓ Storage link done${NC}"
echo ""

# Step 8: Database migration
echo -e "${YELLOW}[7/14]${NC} Jalankan database migration..."
php artisan migrate --force
echo -e "${GREEN}✓ Migration selesai${NC}"
echo ""

# Step 9: Seed database (optional)
echo -e "${YELLOW}[8/14]${NC} Seed database (optional)..."
read -p "Jalankan db:seed? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan db:seed --force
    echo -e "${GREEN}✓ Seeding selesai${NC}"
else
    echo -e "${YELLOW}[!] Seeding di-skip${NC}"
fi
echo ""

# Step 10: Clear cache
echo -e "${YELLOW}[9/14]${NC} Clear dan cache config, routes, views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo -e "${GREEN}✓ Cache cleared${NC}"
echo ""

# Step 11: Build frontend assets
echo -e "${YELLOW}[10/14]${NC} Build frontend assets (Vite + Tailwind)..."
if command -v npm &> /dev/null; then
    npm install
    npm run build
    echo -e "${GREEN}✓ Frontend build selesai${NC}"
else
    echo -e "${RED}ERROR: npm tidak ditemukan${NC}"
    echo "Pastikan Node.js dan npm sudah ter-install di server"
    exit 1
fi
echo ""

# Step 12: Set permissions - storage
echo -e "${YELLOW}[11/14]${NC} Set permissions folder storage..."
chmod 755 storage bootstrap/cache
find storage bootstrap/cache -type d -exec chmod 755 {} \;
find storage bootstrap/cache -type f -exec chmod 644 {} \;
echo -e "${GREEN}✓ Permissions storage set${NC}"
echo ""

# Step 13: Set permissions - .env
echo -e "${YELLOW}[12/14]${NC} Set permissions file .env..."
chmod 644 .env
echo -e "${GREEN}✓ Permissions .env set${NC}"
echo ""

# Step 14: Queue restart
echo -e "${YELLOW}[13/14]${NC} Restart queue..."
php artisan queue:restart 2>/dev/null || echo -e "${YELLOW}[!] Queue restart (optional)${NC}"
echo -e "${GREEN}✓ Queue restart done${NC}"
echo ""

# Final check
echo -e "${YELLOW}[14/14]${NC} Verifikasi database connection..."
php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connection: OK\n';" || {
    echo -e "${RED}ERROR: Database connection failed!${NC}"
    echo "Periksa kembali DB credentials di .env"
    exit 1
}
echo -e "${GREEN}✓ Database connection OK${NC}"
echo ""

echo "=========================================="
echo -e "${GREEN}✓ DEPLOYMENT SELESAI!${NC}"
echo "=========================================="
echo ""
echo "Langkah selanjutnya:"
echo "1. Buka browser: https://bukitshangrillaasri2.com"
echo "2. Jika ada error, cek log:"
echo "   tail -f storage/logs/laravel.log"
echo ""
echo "Backup .env lama tersimpan di: .env.backup"
echo ""
