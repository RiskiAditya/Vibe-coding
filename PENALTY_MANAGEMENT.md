# Manajemen Denda - Equipment Lending System

## Overview
Fitur Manajemen Denda adalah sistem komprehensif untuk mengelola dan melacak pembayaran denda peminjaman alat. Fitur ini dirancang khusus untuk **Admin** karena admin yang bertanggung jawab atas pengelolaan keuangan sistem.

## Fitur Utama

### 1. Dashboard Statistik Denda
- **Total Denda**: Jumlah keseluruhan denda yang dihasilkan dari semua peminjaman
- **Denda Belum Dibayar**: Jumlah denda yang masih outstanding
- **Denda Sudah Dibayar**: Jumlah denda yang telah dilunasi
- **Tingkat Pembayaran**: Persentase denda yang sudah dibayar

### 2. Manajemen Denda
Lokasi: **Menu Sidebar Admin → Manajemen Denda**

#### Fitur Filter & Pencarian:
- **Filter Status**: 
  - Belum Dibayar (default)
  - Sudah Dibayar
  - Semua
- **Pencarian**: Cari berdasarkan nama peminjam atau nama alat

#### Informasi yang Ditampilkan:
- Data peminjam (nama, email)
- Alat yang dipinjam
- Tanggal pengembalian
- Jumlah hari keterlambatan
- Denda keterlambatan (Rp 5.000/hari)
- Biaya perbaikan (jika ada kerusakan)
- Total denda
- Status pembayaran
- Tanggal pembayaran (jika sudah dibayar)

#### Aksi yang Tersedia:
- **Tandai Lunas**: Menandai denda sebagai sudah dibayar
- **Batalkan**: Mengubah status kembali menjadi belum dibayar

### 3. Integrasi dengan Dashboard Admin
Card statistik denda ditampilkan di dashboard admin dengan desain yang menarik dan informatif, memberikan overview cepat tentang kondisi pembayaran denda.

## Perhitungan Denda

### Denda Keterlambatan
- **Tarif**: Rp 5.000 per hari
- **Perhitungan**: Otomatis berdasarkan selisih tanggal pengembalian aktual dengan tanggal yang diminta

### Biaya Perbaikan
- **Kondisi Baik**: Rp 0
- **Rusak Ringan**: Ditentukan saat pengembalian
- **Rusak Berat**: Ditentukan saat pengembalian
- **Hilang**: Ditentukan saat pengembalian

### Total Penalti
```
Total Penalti = Denda Keterlambatan + Biaya Perbaikan
```

## Alur Kerja

1. **Peminjam mengembalikan alat** → Staff/Admin memproses pengembalian
2. **Sistem menghitung denda** → Otomatis berdasarkan keterlambatan dan kondisi
3. **Admin melihat denda di Manajemen Denda** → Filter "Belum Dibayar"
4. **Peminjam membayar denda** → Admin menerima pembayaran
5. **Admin tandai sebagai lunas** → Klik "Tandai Lunas"
6. **Denda tercatat sebagai dibayar** → Pindah ke kategori "Sudah Dibayar"

## Akses & Keamanan

### Role yang Dapat Mengakses:
- **Admin Only**: Hanya admin yang dapat mengakses fitur Manajemen Denda
- **Middleware**: `role:admin` memastikan keamanan akses

### Alasan Admin Only:
1. **Tanggung Jawab Keuangan**: Admin bertanggung jawab atas pengelolaan keuangan
2. **Keamanan Data**: Data keuangan sensitif harus dibatasi aksesnya
3. **Audit Trail**: Satu role yang bertanggung jawab memudahkan audit
4. **Integritas Data**: Mencegah manipulasi data pembayaran

## Database Schema

### Tabel: borrowings
Kolom tambahan untuk manajemen denda:
- `penalty_paid` (boolean): Status pembayaran denda
- `penalty_paid_at` (timestamp): Tanggal pembayaran denda

## Routes

```php
GET  /penalties                          → penalties.index (Halaman utama)
POST /penalties/{borrowing}/mark-paid    → penalties.mark-paid (Tandai lunas)
POST /penalties/{borrowing}/mark-unpaid  → penalties.mark-unpaid (Batalkan)
```

## UI/UX Design

### Warna & Indikator:
- **Merah**: Denda, keterlambatan, belum dibayar
- **Hijau**: Sudah dibayar, lunas
- **Kuning**: Pending, perlu perhatian
- **Biru**: Informasi, statistik

### Responsiveness:
- Mobile-friendly design
- Grid layout yang adaptif
- Table dengan horizontal scroll untuk mobile

## Tips Penggunaan

1. **Monitoring Rutin**: Cek dashboard denda secara berkala
2. **Follow Up**: Hubungi peminjam dengan denda outstanding
3. **Dokumentasi**: Catat bukti pembayaran sebelum tandai lunas
4. **Laporan**: Export data untuk laporan keuangan bulanan
5. **Audit**: Review denda yang sudah dibayar secara periodik

## Future Enhancements (Opsional)

- Export laporan denda ke Excel/PDF
- Notifikasi email untuk denda outstanding
- Integrasi payment gateway
- Riwayat perubahan status pembayaran
- Grafik trend pembayaran denda
- Reminder otomatis untuk peminjam

---

**Catatan**: Fitur ini sudah terintegrasi penuh dengan sistem peminjaman dan pengembalian alat.
