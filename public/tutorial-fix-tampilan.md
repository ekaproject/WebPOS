# Tutorial: Memperbaiki Tampilan Putih di Laravel + Vite + Tailwind v4

## Penyebab Masalah

Project ini menggunakan **Tailwind CSS v4** dengan **Vite**. Tampilannya putih karena:

1. Folder `public/build/` belum ada (belum di-build)
2. Fallback di `resources/views/partials/vite-assets.blade.php` memuat **CDN Tailwind v3**,
   tapi CSS project pakai syntax **Tailwind v4** (`@import "tailwindcss"`, `@theme`) yang tidak dikenali v3
3. Akibatnya: **0 styling** yang ter-apply → halaman putih polos

---

## Cara Memperbaiki

### 1. Pastikan `node_modules` sudah ter-install

```bash
npm install
```

### 2. Build assets untuk production

```bash
npm run build
```

Ini akan menghasilkan folder `public/build/` berisi file CSS & JS yang sudah di-compile.

### 3. Atau jalankan dev server (untuk development)

```bash
npm run dev
```

Ini membuat file `public/hot` yang memberitahu Laravel untuk memuat assets dari Vite dev server (dengan hot reload).

---

## Kenapa Bisa Terjadi?

File `resources/views/partials/vite-assets.blade.php` mengecek:

```php
@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@else
    <script src="https://cdn.tailwindcss.com"></script>  {{-- Fallback Tailwind v3 --}}
@endif
```

- Jika `build/manifest.json` atau `hot` **ada** → pakai Vite (Tailwind v4 ter-compile dengan benar)
- Jika **tidak ada** → pakai CDN Tailwind **v3** yang tidak kompatibel dengan syntax v4

---

## Tips

- Setiap kali **clone** atau **fresh install** project, selalu jalankan `npm install` lalu `npm run build`
- Saat **development**, gunakan `npm run dev` agar perubahan CSS/JS langsung ter-update otomatis
- Jangan commit folder `public/build/` dan `node_modules/` ke Git (sudah di-ignore default)

---

## Langkah Setup Lengkap (Fresh Install)

```bash
# 1. Install PHP dependencies
composer install

# 2. Copy .env dan generate key
cp .env.example .env
php artisan key:generate

# 3. Konfigurasi database di .env
#    DB_CONNECTION=mysql
#    DB_HOST=127.0.0.1
#    DB_PORT=3306
#    DB_DATABASE=webpos
#    DB_USERNAME=root
#    DB_PASSWORD=

# 4. Buat database
mysql -u root -e "CREATE DATABASE IF NOT EXISTS webpos"

# 5. Jalankan migrasi dan seeder
php artisan migrate
php artisan db:seed

# 6. Install frontend dependencies dan build
npm install
npm run build

# 7. Jalankan server
php artisan serve
```

---

_Tutorial ini dibuat untuk project WebPOS - Laravel 12 + Vite + Tailwind CSS v4_
