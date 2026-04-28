# PANDUAN DEPLOYMENT WEBPBLS4 KE NIAGAHOSTER

## File yang Perlu Anda Upload

Sebelum menjalankan script, pastikan file ini sudah ada di `public_html` Niagahoster:

1. **deploy-niagahoster.sh** ← Script otomatis (saya yang buat)
2. **.env.production** ← File konfigurasi produksi
3. **Seluruh project code** (via Git atau FTP)

---

## LANGKAH-LANGKAH DEPLOYMENT

### A. Upload File ke Niagahoster

#### Opsi 1: Via cPanel File Manager (Paling Mudah)
1. Login cPanel: https://bukitshangrillaasri2.com:2083
2. File Manager → Navigate ke `public_html`
3. Upload file:
   - `deploy-niagahoster.sh`
   - `.env.production`
4. Upload seluruh project (via zip atau folder structure)

#### Opsi 2: Via FTP (FileZilla / WinSCP)
1. Connect ke FTP Niagahoster
2. Upload ke folder `public_html`:
   - Deploy script
   - .env.production
   - Seluruh project

#### Opsi 3: Via Git (Recommended)
```bash
cd ~/public_html
git clone https://github.com/your-username/WebPBLS4-master.git .
# (pastikan .env.production sudah di repo atau upload manual)
```

---

### B. SSH dan Jalankan Script

1. **Buka terminal dan SSH ke Niagahoster:**
```bash
ssh username@bukitshangrillaasri2.com
# Ganti 'username' dengan username cPanel Anda
```

2. **Masuk ke folder public_html:**
```bash
cd ~/public_html
```

3. **Buat script executable:**
```bash
chmod +x deploy-niagahoster.sh
```

4. **Jalankan script deployment:**
```bash
./deploy-niagahoster.sh
```

5. **Script akan:**
   - ✓ Copy .env.production → .env
   - ✓ Install Composer dependencies
   - ✓ Generate APP_KEY
   - ✓ Setup storage link
   - ✓ Migrate database
   - ✓ Build frontend assets (Vite)
   - ✓ Set permissions folder
   - ✓ Verify database connection
   - ✓ Done!

---

### C. Verifikasi Aplikasi

Setelah script selesai:

1. **Buka browser:**
   ```
   https://bukitshangrillaasri2.com
   ```

2. **Jika ada error, cek log:**
   ```bash
   ssh username@bukitshangrillaasri2.com
   tail -f ~/public_html/storage/logs/laravel.log
   ```

3. **Cek database connection:**
   ```bash
   php artisan tinker
   >>> DB::connection()->getPdo()
   # Harus tidak throw error
   ```

---

## TROUBLESHOOTING

### Error: Permission denied
```bash
chmod +x deploy-niagahoster.sh
```

### Error: .env.production not found
- Pastikan .env.production sudah ter-upload ke `public_html`
- Upload manual via cPanel File Manager jika belum ada

### Error: composer not found
- Jalankan manual:
```bash
cd ~/public_html
composer install --optimize-autoloader --no-dev --no-interaction
php artisan migrate --force
```

### Error: npm not found
- npm harus ter-install di server Niagahoster
- Hubungi support Niagahoster untuk install Node.js

### Error: Database connection failed
- Periksa .env - pastikan credentials benar:
  - DB_HOST: localhost
  - DB_DATABASE: u936347492_db_pos
  - DB_USERNAME: u936347492_db_pos
  - DB_PASSWORD: Kel1ils123.
- Test connection via SSH:
```bash
mysql -h localhost -u u936347492_db_pos -p'Kel1ils123.' u936347492_db_pos
```

### Error: 403 Forbidden
- Pastikan DocumentRoot di Apache mengarah ke `public` folder
- Atau hubungi support Niagahoster untuk config virtual host

---

## FILE BACKUP

Script otomatis membuat backup:
- **.env.backup** ← Backup .env lama (jika ada)

Jika ada masalah, restore dengan:
```bash
cp .env.backup .env
php artisan cache:clear
```

---

## NEXT STEPS (OPTIONAL)

### 1. Setup Email (SMTP)
Edit .env di server:
```bash
nano ~/public_html/.env
```

Ubah MAIL section:
```env
MAIL_MAILER=smtp
MAIL_HOST=mail.bukitshangrillaasri2.com
MAIL_PORT=587
MAIL_USERNAME=noreply@bukitshangrillaasri2.com
MAIL_PASSWORD=<password_email_Anda>
MAIL_ENCRYPTION=tls
```

### 2. Setup SSL Certificate
- cPanel → AutoSSL → Generate untuk bukitshangrillaasri2.com
- (Let's Encrypt, gratis)

### 3. Monitor Aplikasi
```bash
# Real-time log monitoring
tail -f ~/public_html/storage/logs/laravel.log

# Restart queue worker (jika ada background jobs)
php artisan queue:work
```

---

## QUESTIONS?

Jika ada error atau pertanyaan saat deployment, beri tahu saya:
1. Error message lengkap
2. Output dari script
3. SSH log jika ada

Good luck! 🚀
