# Testing Guide: Perbaikan Penyimpanan Gambar Barang Masuk

## Ringkasan Perbaikan

Masalah: Gambar tidak tersimpan saat form "Input Barang Masuk" di-submit, meskipun preview gambar muncul di form.

### Perbaikan yang Dilakukan:

1. **Tambah Preview Visual untuk Gambar Upload**
   - Menambahkan container preview yang muncul saat user memilih gambar
   - Preview menggunakan FileReader API untuk display real-time
   - Container tersembunyi saat tidak ada gambar atau file invalid

2. **Perbaiki Controller Logic**
   - Fixed broken `store()` method yang memiliki return statement di tengah method
   - Tambahkan proper try-catch untuk file upload
   - Tambahkan rollback file jika penyimpanan data gagal
   - Tambahkan logging untuk error diagnosis

3. **Verifikasi Folder Permissions**
   - Folder `public/storage/inbound-products` sudah memiliki permissions yang correct
   - Directory auto-created jika belum ada

## Testing Steps

### 1. Akses Form Input Barang Masuk
```
1. Buka: Admin Console > Barang Masuk & QC > Input Barang Masuk
2. Atau akses langsung: /admin/inbound-items/create
```

### 2. Test Preview Gambar
```
1. Pilih "Distributor"
2. Pilih "Produk" dari dropdown master products
3. Scroll ke bagian "Foto Produk Barang Masuk (Opsional)"
4. Klik "Choose File" dan pilih gambar (.jpg, .png, .webp, dll)
5. ✓ Seharusnya muncul preview gambar di bawah input file
   - Preview container akan muncul dengan label "Preview Foto Barang Masuk"
   - Gambar akan ditampilkan dengan ukuran yang sesuai
```

### 3. Test Penyimpanan Gambar
```
1. Lengkapi form dengan data:
   - Distributor: Pilih salah satu
   - Produk: Pilih dari dropdown
   - Kemasan Beli: Pilih satuan
   - Isi per Kemasan: Input angka (misal: 24)
   - Jumlah Kemasan: Input angka (misal: 2)
   - Harga Beli: Input harga
   - Harga Jual: Input harga
   - Tanggal Masuk: Pilih tanggal
   - Expired Date: Pilih tanggal
   - Foto Produk: Upload gambar ✓
2. Klik "Simpan Barang Masuk"
3. ✓ Seharusnya berhasil tersimpan dengan pesan: 
   "Barang masuk berhasil ditambahkan. Silakan lakukan proses QC."
```

### 4. Verifikasi Gambar Tersimpan
```
Method 1 - Via UI:
1. Setelah submit, akan diarahkan ke halaman detail barang masuk
2. ✓ Gambar harus tampil di bagian "Foto Produk"
3. Gambar ditampilkan dengan ukuran sesuai

Method 2 - Via File System:
1. Buka folder: c:\laragon\www\WebPBLS4-master\public\storage\inbound-products
2. ✓ Seharusnya ada file gambar dengan nama format: `{timestamp}_{nama-file-asli}`
   - Contoh: 1715420123_pocari_sweat.jpg
3. File bisa dibuka dan menampilkan gambar yang benar

Method 3 - Via Database:
1. Buka tabel: inbound_items di database
2. ✓ Kolom 'product_photo' harus terisi dengan path:
   - Contoh: inbound-products/1715420123_pocari_sweat.jpg
```

## Error Handling

Jika terjadi error, aplikasi akan menampilkan pesan error yang jelas:

- **"Gagal menyimpan foto produk"** → Ada masalah saat upload file
  - Solusi: Pastikan gambar berformat valid (JPG, PNG, WebP, dll)
  - Pastikan ukuran file < 2MB
  - Pastikan folder `public/storage/inbound-products` writable

- **"Gagal menyimpan data barang masuk"** → Ada masalah saat menyimpan ke database
  - Solusi: Periksa error log di `storage/logs/`
  - File yang sudah terupload akan otomatis dihapus (rollback)

## Debugging

Jika masih ada masalah, periksa:

1. **Log File:**
   - File: `storage/logs/laravel-{date}.log`
   - Cari error terbaru untuk "Inbound product photo" atau "Inbound item creation"

2. **Folder Permissions:**
   ```powershell
   # Check folder permissions
   Get-Acl "c:\laragon\www\WebPBLS4-master\public\storage\inbound-products"
   
   # Set correct permissions if needed
   icacls "c:\laragon\www\WebPBLS4-master\public\storage" /grant Everyone:F /T
   ```

3. **File System Check:**
   ```powershell
   # Check if folder exists and has files
   Get-ChildItem "c:\laragon\www\WebPBLS4-master\public\storage\inbound-products" -Force
   ```

## File yang Diubah

1. `resources/views/admin/inbound-items/create.blade.php`
   - Tambah preview container HTML
   - Tambah JavaScript untuk preview gambar

2. `app/Http/Controllers/Admin/InboundItemController.php`
   - Fixed broken `store()` method
   - Tambah error handling dan logging
   - Tambah file rollback logic

---

**Catatan:** Sebelumnya, form hanya menampilkan preview browser default saat file dipilih (sementara). Sekarang preview akan persistent di form dengan visual yang lebih jelas, dan error handling lebih baik untuk diagnosis masalah.
