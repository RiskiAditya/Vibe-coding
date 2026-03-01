# TEST CASES - SISTEM PEMINJAMAN ALAT

## KEBUTUHAN UJI COBA

---

## 1. PENGUJIAN AUTENTIKASI & LOGIN

### Test Case 1.1: Login dengan Username dan Password yang Benar
**Kasus:** Login dengan username "admin" dan password "Admin123"  
**Hasil:** Berhasil login dan diarahkan ke Dashboard Admin

### Test Case 1.2: Login dengan Username dan Password yang Salah
**Kasus:** Login dengan username "admin" dan password "salah123"  
**Hasil:** Gagal login, tidak dapat masuk, dan muncul notifikasi error "These credentials do not match our records"

### Test Case 1.3: Login dengan Username Kosong
**Kasus:** Login dengan username kosong dan password "Admin123"  
**Hasil:** Gagal login dan muncul notifikasi "The username field is required"

### Test Case 1.4: Login dengan Password Kosong
**Kasus:** Login dengan username "admin" dan password kosong  
**Hasil:** Gagal login dan muncul notifikasi "The password field is required"

### Test Case 1.5: Login dengan Role Admin
**Kasus:** Login sebagai admin (username: admin, password: Admin123)  
**Hasil:** Berhasil login dan diarahkan ke Dashboard Admin dengan menu lengkap (Users, Equipment, Categories)

### Test Case 1.6: Login dengan Role Staff
**Kasus:** Login sebagai staff (username: staff, password: Staff123)  
**Hasil:** Berhasil login dan diarahkan ke Dashboard Staff dengan menu operasional (Penalties, Damaged Equipment, Reports)

### Test Case 1.7: Login dengan Role Borrower
**Kasus:** Login sebagai borrower (username: borrower, password: Borrower123)  
**Hasil:** Berhasil login dan diarahkan ke Dashboard Borrower dengan menu terbatas (Equipment, My Borrowings)

---

## 2. PENGUJIAN MANAJEMEN PENGGUNA (Admin Only)

### Test Case 2.1: Tambah User dengan Data Valid
**Kasus:** Admin menambah user baru dengan username "user123", email "user@test.com", password "User1234", role "borrower"  
**Hasil:** Berhasil, user baru tersimpan di database dan muncul notifikasi "User created successfully"

### Test Case 2.2: Tambah User dengan Username yang Sudah Ada
**Kasus:** Admin menambah user dengan username "admin" (sudah ada)  
**Hasil:** Gagal, muncul notifikasi error "The username has already been taken"

### Test Case 2.3: Tambah User dengan Email yang Sudah Ada
**Kasus:** Admin menambah user dengan email "admin@example.com" (sudah ada)  
**Hasil:** Gagal, muncul notifikasi error "The email has already been taken"

### Test Case 2.4: Tambah User dengan Password Kurang dari 8 Karakter
**Kasus:** Admin menambah user dengan password "Pass1"  
**Hasil:** Gagal, muncul notifikasi "The password must be at least 8 characters"

### Test Case 2.5: Tambah User dengan Password Tanpa Huruf Besar
**Kasus:** Admin menambah user dengan password "password123"  
**Hasil:** Gagal, muncul notifikasi "Password must contain at least one uppercase letter"

### Test Case 2.6: Edit User dan Ubah Role
**Kasus:** Admin mengubah role user dari "borrower" menjadi "staff"  
**Hasil:** Berhasil, role user berubah dan muncul notifikasi "User updated successfully"

### Test Case 2.7: Hapus User
**Kasus:** Admin menghapus user dengan ID tertentu  
**Hasil:** Berhasil, user terhapus dari database dan muncul notifikasi "User deleted successfully"

---

## 3. PENGUJIAN MANAJEMEN KATEGORI (Admin Only)

### Test Case 3.1: Tambah Kategori dengan Nama Valid
**Kasus:** Admin menambah kategori "Elektronik"  
**Hasil:** Berhasil, kategori tersimpan dan muncul notifikasi "Category created successfully"

### Test Case 3.2: Tambah Kategori dengan Nama yang Sudah Ada
**Kasus:** Admin menambah kategori "Elektronik" (sudah ada)  
**Hasil:** Gagal, muncul notifikasi "The name has already been taken"

### Test Case 3.3: Edit Nama Kategori
**Kasus:** Admin mengubah nama kategori dari "Elektronik" menjadi "Peralatan Elektronik"  
**Hasil:** Berhasil, nama kategori berubah dan muncul notifikasi "Category updated successfully"

### Test Case 3.4: Hapus Kategori yang Tidak Memiliki Alat
**Kasus:** Admin menghapus kategori yang tidak memiliki alat terkait  
**Hasil:** Berhasil, kategori terhapus dan muncul notifikasi "Category deleted successfully"

### Test Case 3.5: Hapus Kategori yang Memiliki Alat
**Kasus:** Admin menghapus kategori yang masih memiliki alat terkait  
**Hasil:** Gagal, muncul notifikasi "Cannot delete category with existing equipment"

---

## 4. PENGUJIAN MANAJEMEN ALAT (Admin Only)

### Test Case 4.1: Tambah Alat dengan Data Valid
**Kasus:** Admin menambah alat "Laptop Dell" dengan kategori "Elektronik", stock 5  
**Hasil:** Berhasil, alat tersimpan dengan status "available" dan available_stock = 5

### Test Case 4.2: Tambah Alat dengan Stock Kurang dari 1
**Kasus:** Admin menambah alat dengan stock 0  
**Hasil:** Gagal, muncul notifikasi "The stock must be at least 1"

### Test Case 4.3: Tambah Alat dengan Upload Gambar (Max 2MB)
**Kasus:** Admin menambah alat dengan upload gambar 1.5MB  
**Hasil:** Berhasil, gambar tersimpan di storage dan path tersimpan di database

### Test Case 4.4: Tambah Alat dengan Upload Gambar Lebih dari 2MB
**Kasus:** Admin menambah alat dengan upload gambar 3MB  
**Hasil:** Gagal, muncul notifikasi "The image may not be greater than 2048 kilobytes"

### Test Case 4.5: Edit Alat dan Ubah Stock
**Kasus:** Admin mengubah stock alat dari 5 menjadi 10  
**Hasil:** Berhasil, stock berubah dan available_stock juga terupdate

### Test Case 4.6: Hapus Alat yang Tidak Memiliki Peminjaman Aktif
**Kasus:** Admin menghapus alat yang tidak sedang dipinjam  
**Hasil:** Berhasil, alat terhapus dan muncul notifikasi "Equipment deleted successfully"

### Test Case 4.7: Hapus Alat yang Memiliki Peminjaman Aktif
**Kasus:** Admin menghapus alat yang sedang dipinjam (status approved)  
**Hasil:** Gagal, muncul notifikasi "Cannot delete equipment with active borrowings"

---

## 5. PENGUJIAN PEMINJAMAN ALAT

### Test Case 5.1: Borrower Request Peminjaman dengan Alat Tersedia
**Kasus:** Borrower meminjam "Laptop Dell" dengan tanggal kembali besok  
**Hasil:** Berhasil, peminjaman tersimpan dengan status "pending"

### Test Case 5.2: Borrower Request Peminjaman dengan Alat Habis (Stock 0)
**Kasus:** Borrower meminjam alat yang available_stock = 0  
**Hasil:** Gagal, tombol "Pinjam" disabled dan muncul notifikasi "Equipment out of stock"

### Test Case 5.3: Borrower Request dengan Tanggal Kembali Hari Ini
**Kasus:** Borrower meminjam dengan tanggal kembali = hari ini  
**Hasil:** Gagal, muncul notifikasi "Return date must be at least tomorrow"

### Test Case 5.4: Staff Approve Peminjaman
**Kasus:** Staff menyetujui peminjaman dengan status "pending"  
**Hasil:** Berhasil, status berubah "approved", available_stock berkurang 1, muncul notifikasi "Borrowing approved"

### Test Case 5.5: Staff Reject Peminjaman
**Kasus:** Staff menolak peminjaman dengan status "pending"  
**Hasil:** Berhasil, status berubah "rejected", available_stock tidak berubah, muncul notifikasi "Borrowing rejected"

### Test Case 5.6: Borrower Tidak Bisa Approve Peminjaman Sendiri
**Kasus:** Borrower mencoba approve peminjaman sendiri  
**Hasil:** Gagal, tombol approve tidak muncul (hanya staff/admin yang bisa)

---

## 6. PENGUJIAN PENGEMBALIAN ALAT

### Test Case 6.1: Staff Proses Pengembalian dengan Kondisi Baik (Tepat Waktu)
**Kasus:** Staff memproses pengembalian dengan kondisi "baik", tanggal kembali sesuai jadwal  
**Hasil:** Berhasil, status "returned", available_stock bertambah 1, late_days = 0, total_penalty = 0

### Test Case 6.2: Staff Proses Pengembalian dengan Kondisi Baik (Terlambat 3 Hari)
**Kasus:** Staff memproses pengembalian dengan kondisi "baik", terlambat 3 hari  
**Hasil:** Berhasil, status "returned", available_stock bertambah 1, late_days = 3, late_fee = Rp 15,000, total_penalty = Rp 15,000

### Test Case 6.3: Staff Proses Pengembalian dengan Kondisi Rusak
**Kasus:** Staff memproses pengembalian dengan kondisi "rusak", biaya perbaikan Rp 50,000, terlambat 2 hari  
**Hasil:** Berhasil, status "returned", available_stock TIDAK bertambah, late_fee = Rp 10,000, repair_cost = Rp 50,000, total_penalty = Rp 60,000

### Test Case 6.4: Staff Proses Pengembalian dengan Kondisi Rusak Tanpa Catatan
**Kasus:** Staff memproses pengembalian dengan kondisi "rusak" tapi tidak mengisi catatan kerusakan  
**Hasil:** Gagal, muncul notifikasi "Damage notes are required when equipment is damaged"

### Test Case 6.5: Staff Proses Pengembalian dengan Kondisi Hilang
**Kasus:** Staff memproses pengembalian dengan kondisi "hilang"  
**Hasil:** Berhasil, status "returned", stock berkurang 1, available_stock TIDAK bertambah, alat masuk list barang hilang

### Test Case 6.6: Borrower Tidak Bisa Proses Pengembalian Sendiri
**Kasus:** Borrower mencoba proses pengembalian sendiri  
**Hasil:** Gagal, halaman return hanya bisa diakses staff/admin

---

## 7. PENGUJIAN MANAJEMEN DENDA (Staff Only)

### Test Case 7.1: Staff Lihat Daftar Denda
**Kasus:** Staff membuka halaman Manajemen Denda  
**Hasil:** Berhasil, muncul tabel denda dengan kolom: Peminjam, Alat, Hari Terlambat, Total Denda, Status Pembayaran

### Test Case 7.2: Staff Mark Denda sebagai Paid
**Kasus:** Staff menandai denda sebagai "Sudah Dibayar"  
**Hasil:** Berhasil, penalty_paid = TRUE, badge berubah dari merah "Belum Dibayar" menjadi hijau "Sudah Dibayar"

### Test Case 7.3: Staff Filter Denda Belum Dibayar
**Kasus:** Staff filter denda dengan status "Belum Dibayar"  
**Hasil:** Berhasil, hanya menampilkan denda dengan penalty_paid = FALSE

### Test Case 7.4: Staff Filter Denda Sudah Dibayar
**Kasus:** Staff filter denda dengan status "Sudah Dibayar"  
**Hasil:** Berhasil, hanya menampilkan denda dengan penalty_paid = TRUE

---

## 8. PENGUJIAN BARANG RUSAK & HILANG (Staff Only)

### Test Case 8.1: Staff Lihat List Barang Rusak
**Kasus:** Staff membuka halaman "List Barang Rusak"  
**Hasil:** Berhasil, muncul tabel barang rusak dengan kolom: Nama Alat, Peminjam, Catatan Kerusakan, Biaya Perbaikan

### Test Case 8.2: Staff Lihat List Barang Hilang
**Kasus:** Staff membuka halaman "List Barang Hilang"  
**Hasil:** Berhasil, muncul tabel barang hilang dengan kolom: Nama Alat, Peminjam, Tanggal Hilang

### Test Case 8.3: Borrower Tidak Bisa Akses List Barang Rusak
**Kasus:** Borrower mencoba akses halaman "List Barang Rusak"  
**Hasil:** Gagal, redirect ke dashboard dengan notifikasi "Unauthorized access"

---

## 9. PENGUJIAN LAPORAN (Staff Only)

### Test Case 9.1: Staff Generate Laporan Peminjaman
**Kasus:** Staff generate laporan peminjaman dengan filter tanggal  
**Hasil:** Berhasil, muncul tabel laporan dengan data peminjaman sesuai filter

### Test Case 9.2: Staff Print Laporan
**Kasus:** Staff klik tombol "Print"  
**Hasil:** Berhasil, muncul print preview dengan format print-friendly

### Test Case 9.3: Staff Export Laporan ke PDF
**Kasus:** Staff klik tombol "Export PDF"  
**Hasil:** Berhasil, file PDF terdownload dengan nama "laporan_peminjaman_[tanggal].pdf"

### Test Case 9.4: Staff Export Laporan ke Excel
**Kasus:** Staff klik tombol "Export Excel"  
**Hasil:** Berhasil, file Excel terdownload dengan nama "laporan_peminjaman_[tanggal].xlsx"

---

## 10. PENGUJIAN RIWAYAT PEMINJAM (Staff Only)

### Test Case 10.1: Staff Lihat Riwayat Peminjam
**Kasus:** Staff membuka halaman "Riwayat Peminjam"  
**Hasil:** Berhasil, muncul daftar semua peminjam dengan statistik

### Test Case 10.2: Staff Lihat Detail Riwayat Per Peminjam
**Kasus:** Staff klik detail peminjam tertentu  
**Hasil:** Berhasil, muncul detail: Total Peminjaman, Peminjaman Aktif, Total Denda, Riwayat Lengkap

---

## 11. PENGUJIAN DASHBOARD

### Test Case 11.1: Admin Lihat Dashboard
**Kasus:** Admin login dan masuk dashboard  
**Hasil:** Berhasil, muncul: Statistik Cards, Quick Actions, Statistik Kategori, Aktivitas Terbaru

### Test Case 11.2: Staff Lihat Dashboard
**Kasus:** Staff login dan masuk dashboard  
**Hasil:** Berhasil, muncul: Statistik Cards, Statistik Denda, 4 Charts (Line, Pie, Bar, Donut), Tabel Utilisasi

### Test Case 11.3: Borrower Lihat Dashboard
**Kasus:** Borrower login dan masuk dashboard  
**Hasil:** Berhasil, muncul: Daftar Alat Tersedia, Peminjaman Saya

### Test Case 11.4: Dashboard Menampilkan Data Real-time
**Kasus:** Admin approve peminjaman, lalu refresh dashboard  
**Hasil:** Berhasil, angka "Peminjaman Aktif" bertambah 1, "Permintaan Tertunda" berkurang 1

---

## 12. PENGUJIAN VALIDASI FORM

### Test Case 12.1: Form Tambah User dengan Field Kosong
**Kasus:** Admin submit form tambah user tanpa mengisi field apapun  
**Hasil:** Gagal, muncul notifikasi error untuk setiap field required

### Test Case 12.2: Form Tambah Alat dengan Nama Lebih dari 255 Karakter
**Kasus:** Admin input nama alat 300 karakter  
**Hasil:** Gagal, muncul notifikasi "The name may not be greater than 255 characters"

### Test Case 12.3: Form Request Peminjaman dengan Catatan Lebih dari 500 Karakter
**Kasus:** Borrower input catatan 600 karakter  
**Hasil:** Gagal, muncul notifikasi "The notes may not be greater than 500 characters"

---

## 13. PENGUJIAN KEAMANAN & AUTHORIZATION

### Test Case 13.1: Borrower Akses Halaman Admin
**Kasus:** Borrower mencoba akses URL "/admin/users"  
**Hasil:** Gagal, redirect ke dashboard dengan notifikasi "Unauthorized"

### Test Case 13.2: Guest (Belum Login) Akses Dashboard
**Kasus:** User belum login mencoba akses "/dashboard"  
**Hasil:** Gagal, redirect ke halaman login

### Test Case 13.3: Staff Akses Halaman Manajemen User
**Kasus:** Staff mencoba akses "/admin/users"  
**Hasil:** Gagal, redirect dengan notifikasi "Unauthorized - Admin only"

---

## 14. PENGUJIAN PERFORMA & UI

### Test Case 14.1: Load Dashboard dengan 1000+ Data
**Kasus:** Dashboard dengan 1000+ peminjaman  
**Hasil:** Berhasil load dalam < 3 detik dengan pagination

### Test Case 14.2: Responsive Design di Mobile
**Kasus:** Buka website di smartphone (< 768px)  
**Hasil:** Berhasil, layout berubah menjadi single column, menu collapse

### Test Case 14.3: Responsive Design di Tablet
**Kasus:** Buka website di tablet (768px - 1024px)  
**Hasil:** Berhasil, layout 2 kolom, semua fitur accessible

---

**Total Test Cases:** 70+ Kasus Uji  
**Coverage:** Authentication, CRUD Operations, Business Logic, Validation, Security, UI/UX  
**Status:** Ready for Testing

