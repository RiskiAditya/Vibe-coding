# 📚 DOKUMENTASI LENGKAP SISTEM PEMINJAMAN ALAT

## 🔐 HALAMAN LOGIN

### Form Login
**Field Username:**
- Label: "Username" dengan icon user
- Type: Text input
- Batasan: Maksimal 25 karakter
- Required: Ya
- Autofocus: Ya
- Placeholder: "Masukkan username Anda"

**Field Password:**
- Label: "Password" dengan icon lock  
- Type: Password (dapat ditampilkan/disembunyikan)
- Batasan: Maksimal 20 karakter
- Required: Ya
- Autocomplete: current-password
- Placeholder: "Masukkan password Anda"
- Fitur: Toggle visibility dengan icon mata

**Tombol Login:**
- Text: "Masuk" dengan icon sign-in
- Warna: Gradient ungu-biru
- Efek: Hover lift & shadow

### Akun Demo yang Tersedia
1. **Admin**: admin / Admin123
2. **Staff**: staff / Staff123  
3. **Borrower**: borrower / Borrower123

---

## 👥 ROLE & HAK AKSES

### 1. ADMIN
**Akses Penuh:**
- Dashboard (dengan Aktivitas Terbaru)
- Manajemen Pengguna (CRUD)
- Manajemen Alat (CRUD)
- Manajemen Kategori (CRUD)
- Peminjaman (View, Approve/Reject)
- Pengembalian (View, Process)

### 2. STAFF (PETUGAS)
**Akses Operasional:**
- Dashboard (dengan Charts & Analytics)
- Peminjaman (View, Approve/Reject)
- Pengembalian (Process)
- Manajemen Denda (CRUD, Update Payment)
- List Barang Rusak (View, Manage)
- List Barang Hilang (View, Manage)
- Riwayat Peminjam (View Detail)
- Laporan (Generate, Print, Export)

### 3. BORROWER (PEMINJAM)
**Akses Terbatas:**
- Dashboard (View Available Equipment)
- Alat (View, Request Borrow)
- Peminjaman Saya (View Status)

---

## 📊 DASHBOARD

### Dashboard Admin
**Statistik Cards (4):**
1. Total Pengguna - Icon users, border biru
2. Total Alat - Icon box, border hijau
3. Peminjaman Aktif - Icon clipboard, border kuning
4. Permintaan Tertunda - Icon exclamation, border merah

**Quick Actions (3):**
1. Kelola Pengguna - Gradient biru-indigo
2. Kelola Alat - Gradient hijau-emerald
3. Kelola Kategori - Gradient ungu-pink

**Statistik Kategori:**
- Grid 3 kolom (responsive)
- Warna dinamis per kategori
- Menampilkan jumlah alat per kategori

**Aktivitas Terbaru:**
- 10 aktivitas terakhir
- Timeline design dengan avatar
- Timestamp relative & absolute

### Dashboard Staff
**Statistik Cards (4):** Sama dengan Admin

**Statistik Denda:**
- Background gradient merah
- 4 metrics: Total, Belum Dibayar, Sudah Dibayar, Tingkat Pembayaran
- Link ke halaman Kelola Denda

**Charts (4):**
1. Tren Peminjaman Bulanan (Line Chart)
2. Alat per Kategori (Pie Chart)
3. 10 Alat Paling Sering Dipinjam (Bar Chart)
4. Distribusi Status Peminjaman (Donut Chart)

**Tabel Utilisasi Alat:**
- Kolom: Kategori, Total Alat, Sedang Dipinjam, Tingkat Utilisasi
- Progress bar dengan warna (hijau <50%, kuning 50-75%, merah >75%)

---

## 👤 MANAJEMEN PENGGUNA (Admin Only)

### Validasi Create User
**Username:**
- Required: Ya
- Min: 3 karakter
- Max: 50 karakter
- Format: Hanya huruf, angka, underscore (a-zA-Z0-9_)
- Unique: Ya
- Error: "Username sudah digunakan"

**Email:**
- Required: Ya
- Format: Valid email
- Max: 255 karakter
- Unique: Ya
- Error: "Email sudah terdaftar"

**Password:**
- Required: Ya (Create), Optional (Update)
- Min: 8 karakter
- Format: Minimal 1 huruf besar, 1 huruf kecil, 1 angka
- Regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/
- Error: "Password harus mengandung huruf besar, kecil, dan angka"

**Role:**
- Required: Ya
- Options: admin, staff, borrower
- Error: "Role tidak valid"

### Fitur
- Tambah Pengguna Baru
- Edit Pengguna (password optional)
- Hapus Pengguna (dengan konfirmasi)
- Search & Filter
- Pagination

---

## 📦 MANAJEMEN ALAT

### Validasi Equipment
**Nama Alat:**
- Required: Ya
- Type: Text
- Max: 255 karakter

**Kategori:**
- Required: Ya
- Type: Select dropdown
- Source: Dari tabel categories

**Stock:**
- Required: Ya
- Type: Number
- Min: 1
- Default: 1
- Info: Jumlah total alat

**Available Stock:**
- Auto-calculated
- Formula: stock - (jumlah yang sedang dipinjam)
- Read-only

**Status:**
- Fixed: "available" (Tersedia)
- Tidak dapat diubah manual
- Selalu "Tersedia" untuk semua alat

**Gambar:**
- Optional
- Format: JPG, PNG, GIF
- Max size: 2MB
- Upload ke: storage/app/public/equipment

**Deskripsi:**
- Optional
- Type: Textarea
- Max: 1000 karakter

### Fitur
- Tambah Alat Baru
- Edit Alat
- Hapus Alat (jika tidak ada peminjaman aktif)
- Upload/Update Gambar
- View Detail dengan statistik peminjaman

---

## 🏷️ MANAJEMEN KATEGORI (Admin Only)

### Validasi Category
**Nama Kategori:**
- Required: Ya
- Type: Text
- Max: 100 karakter
- Unique: Ya

### Fitur
- Tambah Kategori (Modal)
- Edit Kategori (Modal)
- Hapus Kategori (jika tidak ada alat terkait)
- Menampilkan jumlah alat per kategori

---

## 📝 PEMINJAMAN

### Proses Peminjaman
1. **Borrower** memilih alat dan request
2. **Staff/Admin** approve/reject
3. Status berubah menjadi "approved"
4. Available stock berkurang

### Validasi Borrowing
**Tanggal Pengembalian:**
- Required: Ya
- Type: Date
- Min: Besok (H+1)
- Format: Y-m-d

**Catatan:**
- Optional
- Type: Textarea
- Max: 500 karakter

### Status Peminjaman
1. **pending** - Menunggu persetujuan
2. **approved** - Disetujui, sedang dipinjam
3. **returned** - Sudah dikembalikan
4. **rejected** - Ditolak

### Fitur
- Request Peminjaman (Borrower)
- Approve/Reject (Staff/Admin)
- View Status Real-time
- Filter by Status
- Search by User/Equipment

---

## 🔄 PENGEMBALIAN

### Form Pengembalian
**Kondisi Alat:**
- Required: Ya
- Type: Radio button
- Options:
  1. **Baik** - Icon check hijau
  2. **Rusak** - Icon tools merah
  3. **Hilang** - Icon question oranye

**Catatan Kerusakan:**
- Required: Jika kondisi "Rusak"
- Type: Textarea
- Max: 500 karakter

**Biaya Perbaikan:**
- Required: Jika kondisi "Rusak"
- Type: Number
- Min: 0
- Format: Rupiah

### Perhitungan Denda
**Denda Keterlambatan:**
- Rate: Rp 5.000 per hari
- Formula: (Tanggal Kembali - Tanggal Jatuh Tempo) × 5000
- Auto-calculated

**Total Denda:**
- Formula: Denda Keterlambatan + Biaya Perbaikan
- Ditampilkan dalam format Rupiah

### Proses
1. Staff memproses pengembalian
2. Pilih kondisi alat
3. Jika rusak: masukkan catatan & biaya
4. Jika hilang: alat masuk list barang hilang
5. Sistem hitung denda otomatis
6. Available stock bertambah (jika kondisi baik)

---

## 💰 MANAJEMEN DENDA (Staff Only)

### Informasi Denda
**Kolom Tabel:**
- Peminjam (username)
- Alat
- Tanggal Pinjam
- Tanggal Kembali
- Hari Terlambat
- Denda Keterlambatan (Rp 5.000/hari)
- Biaya Perbaikan
- Total Denda
- Status Pembayaran
- Aksi

### Status Pembayaran
1. **Belum Dibayar** - Badge merah
2. **Sudah Dibayar** - Badge hijau

### Fitur
- View All Penalties
- Filter by Status (Paid/Unpaid)
- Mark as Paid
- View Payment History
- Export to Excel/PDF

---

## 🔧 BARANG RUSAK & HILANG (Staff Only)

### List Barang Rusak
**Informasi:**
- Nama Alat
- Kategori
- Peminjam
- Tanggal Dikembalikan
- Catatan Kerusakan
- Biaya Perbaikan
- Status Perbaikan

**Status Perbaikan:**
- Menunggu Perbaikan
- Sedang Diperbaiki
- Selesai Diperbaiki

### List Barang Hilang
**Informasi:**
- Nama Alat
- Kategori
- Peminjam
- Tanggal Hilang
- Nilai Alat
- Status Penggantian

**Status Penggantian:**
- Belum Diganti
- Sedang Diproses
- Sudah Diganti

---

## 📜 RIWAYAT PEMINJAM (Staff Only)

### Fitur
- View semua peminjam
- Detail riwayat per peminjam
- Statistik peminjaman
- Filter by Date Range
- Export History

### Detail Peminjam
- Total Peminjaman
- Peminjaman Aktif
- Total Denda
- Denda Belum Dibayar
- Riwayat Lengkap

---

## 📊 LAPORAN (Staff Only)

### Jenis Laporan
1. **Laporan Peminjaman**
   - Filter: Tanggal, Status, User
   - Data: Semua transaksi peminjaman

2. **Laporan Pengembalian**
   - Filter: Tanggal, Kondisi
   - Data: Semua pengembalian

3. **Laporan Denda**
   - Filter: Tanggal, Status Pembayaran
   - Data: Semua denda

4. **Laporan Alat**
   - Filter: Kategori, Status
   - Data: Inventaris alat

### Format Export
- **Print**: Tampilan print-friendly
- **PDF**: Generate PDF
- **Excel**: Export ke .xlsx

---

## ✅ VALIDASI & BATASAN LENGKAP

### Login
- Username: Max 25 karakter
- Password: Max 20 karakter

### User Management
- Username: 3-50 karakter, alphanumeric + underscore
- Email: Max 255 karakter, format email valid
- Password: Min 8 karakter, 1 uppercase, 1 lowercase, 1 digit

### Equipment
- Nama: Max 255 karakter
- Stock: Min 1
- Deskripsi: Max 1000 karakter
- Gambar: Max 2MB, JPG/PNG/GIF

### Category
- Nama: Max 100 karakter, unique

### Borrowing
- Tanggal Kembali: Min H+1 dari hari ini
- Catatan: Max 500 karakter

### Return
- Catatan Kerusakan: Max 500 karakter (required jika rusak)
- Biaya Perbaikan: Min 0 (required jika rusak)

### Penalty
- Denda Keterlambatan: Rp 5.000 per hari
- Auto-calculated

---

## 🎨 DESAIN & UI

### Warna Utama
- Primary: Blue (#3b82f6, #2563EB)
- Success: Green (#10b981, #059669)
- Warning: Yellow/Amber (#f59e0b, #ea580c)
- Danger: Red (#ef4444, #dc2626)
- Accent: Orange (#F97316, #EA580C)

### Typography
- Body: Inter (sans-serif)
- Heading: Poppins (sans-serif)

### Komponen
- Cards: rounded-xl, shadow-md
- Buttons: rounded-lg, gradient backgrounds
- Inputs: rounded-md, border-2
- Modals: backdrop blur, glass effect

### Responsive
- Mobile: < 768px (single column)
- Tablet: 768px - 1024px (2 columns)
- Desktop: > 1024px (3-4 columns)

---

## 📱 APLIKASI DESKTOP

### Teknologi
- Framework: Electron
- Bahasa: JavaScript/Node.js
- Wrapper untuk: Laravel Web App

### Fitur Desktop
- Native window controls
- Keyboard shortcuts (Ctrl+R reload, F11 fullscreen)
- Menu bar
- Auto-connect ke backend
- Error handling

### Build
- Windows: .exe (NSIS installer)
- macOS: .dmg
- Linux: .AppImage, .deb

---

## 🔒 KEAMANAN

### Authentication
- Laravel Sanctum
- Session-based
- CSRF Protection
- Password Hashing (bcrypt)

### Authorization
- Role-based Access Control (RBAC)
- Middleware: auth, role
- Policy-based permissions

### Validation
- Server-side validation (Laravel)
- Client-side validation (HTML5)
- XSS Protection
- SQL Injection Protection

---

## 📝 CATATAN PENTING

1. **Status Alat**: Selalu "Tersedia", tidak ada status lain
2. **Barang Rusak/Hilang**: Dikelola di halaman terpisah, bukan via status
3. **Denda**: Auto-calculated, Rp 5.000/hari keterlambatan
4. **Available Stock**: Auto-update saat peminjaman/pengembalian
5. **Aktivitas Terbaru**: Hanya di Dashboard Admin
6. **Charts & Analytics**: Hanya di Dashboard Staff
7. **Desktop App**: Memerlukan backend Laravel running

---

**Versi**: 1.0.0  
**Terakhir Diupdate**: 2026  
**Developer**: Equipment Lending System Team
