# DATABASE STRUCTURE - EQUIPMENT LENDING SYSTEM

---

## DATA STRUCTURE TABLES

### Struktur Data User
| Nama | Tipe Data | Keterangan |
|------|-----------|------------|
| id | BIGINT UNSIGNED | Primary Key, Unique ID untuk setiap user |
| username | VARCHAR(50) | Nama username untuk login |
| email | VARCHAR(255) | Email address user |
| password | VARCHAR(255) | Password terenkripsi (bcrypt) |
| role | ENUM | Roles: admin, staff, borrower |
| created_at | TIMESTAMP | Tanggal user dibuat |
| updated_at | TIMESTAMP | Tanggal terakhir diupdate |

### Struktur Data Categories
| Nama | Tipe Data | Keterangan |
|------|-----------|------------|
| id | BIGINT UNSIGNED | Primary Key, Unique ID untuk kategori |
| name | VARCHAR(100) | Nama kategori alat |
| created_at | TIMESTAMP | Tanggal kategori dibuat |
| updated_at | TIMESTAMP | Tanggal terakhir diupdate |

### Struktur Data Equipment
| Nama | Tipe Data | Keterangan |
|------|-----------|------------|
| id | BIGINT UNSIGNED | Primary Key, Unique ID untuk alat |
| name | VARCHAR(255) | Nama alat |
| category_id | BIGINT UNSIGNED | Foreign Key, menghubungkan ke tabel categories |
| status | VARCHAR(50) | Status alat (selalu 'available') |
| stock | INT | Jumlah total alat yang ada |
| available_stock | INT | Jumlah alat yang tersedia untuk dipinjam |
| image | VARCHAR(255) | Path gambar alat (optional) |
| description | TEXT | Deskripsi alat (optional) |
| created_at | TIMESTAMP | Tanggal alat ditambahkan |
| updated_at | TIMESTAMP | Tanggal terakhir diupdate |

### Struktur Data Borrowings
| Nama | Tipe Data | Keterangan |
|------|-----------|------------|
| id | BIGINT UNSIGNED | Primary Key, Unique ID untuk transaksi peminjaman |
| user_id | BIGINT UNSIGNED | Foreign Key, menghubungkan ke user yang meminjam |
| equipment_id | BIGINT UNSIGNED | Foreign Key, menghubungkan ke alat yang dipinjam |
| borrow_date | DATE | Tanggal user meminjam alat |
| requested_return_date | DATE | Tanggal pengembalian yang diminta |
| actual_return_date | DATE | Tanggal pengembalian aktual (nullable) |
| status | ENUM | Status: pending, approved, rejected, returned |
| approved_by | BIGINT UNSIGNED | Foreign Key, user yang menyetujui (staff/admin) |
| notes | TEXT | Catatan peminjaman (optional) |
| return_condition | VARCHAR(50) | Kondisi saat dikembalikan: baik, rusak, hilang |
| damage_notes | TEXT | Catatan kerusakan (jika rusak) |
| repair_cost | DECIMAL(10,2) | Biaya perbaikan (jika rusak) |
| late_days | INT | Jumlah hari keterlambatan |
| late_fee | DECIMAL(10,2) | Denda keterlambatan (Rp 5,000/hari) |
| total_penalty | DECIMAL(10,2) | Total denda (late_fee + repair_cost) |
| penalty_paid | BOOLEAN | Status pembayaran denda |
| penalty_paid_at | TIMESTAMP | Tanggal denda dibayar (nullable) |
| created_at | TIMESTAMP | Tanggal transaksi dibuat |
| updated_at | TIMESTAMP | Tanggal terakhir diupdate |

### Struktur Data Activity Logs
| Nama | Tipe Data | Keterangan |
|------|-----------|------------|
| id | BIGINT UNSIGNED | Primary Key, Unique ID untuk log aktivitas |
| user_id | BIGINT UNSIGNED | Foreign Key, user yang melakukan aktivitas |
| action | VARCHAR(255) | Nama aksi yang dilakukan |
| description | TEXT | Deskripsi detail aktivitas (optional) |
| created_at | TIMESTAMP | Tanggal aktivitas terjadi |
| updated_at | TIMESTAMP | Tanggal terakhir diupdate |

---

## RELATIONSHIPS (Foreign Keys)

### Equipment Table
- `category_id` → `categories.id` (ON DELETE CASCADE)

### Borrowings Table
- `user_id` → `users.id` (ON DELETE CASCADE)
- `equipment_id` → `equipment.id` (ON DELETE CASCADE)
- `approved_by` → `users.id` (ON DELETE SET NULL)

### Activity Logs Table
- `user_id` → `users.id` (ON DELETE SET NULL)

---

## INDEXES

### Users Table
- PRIMARY KEY: `id`
- UNIQUE INDEX: `username`
- UNIQUE INDEX: `email`
- INDEX: `role`

### Categories Table
- PRIMARY KEY: `id`
- UNIQUE INDEX: `name`

### Equipment Table
- PRIMARY KEY: `id`
- INDEX: `category_id`
- INDEX: `status`
- INDEX: `name`

### Borrowings Table
- PRIMARY KEY: `id`
- INDEX: `user_id`
- INDEX: `equipment_id`
- INDEX: `status`
- INDEX: `borrow_date`
- INDEX: `actual_return_date`

### Activity Logs Table
- PRIMARY KEY: `id`
- INDEX: `user_id`
- INDEX: `created_at`

---

**Document Version**: 1.0  
**Last Updated**: February 2026  
**Database**: MySQL / SQLite


---

## DDL QUERIES (Data Definition Language)

### Query 1: CREATE TABLE users
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'staff', 'borrower') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_role (role)
);
```

### Query 2: CREATE TABLE categories
```sql
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_name (name)
);
```

### Query 3: CREATE TABLE equipment
```sql
CREATE TABLE equipment (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    status VARCHAR(50) DEFAULT 'available',
    stock INT DEFAULT 1,
    available_stock INT DEFAULT 1,
    image VARCHAR(255) NULL,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_category (category_id),
    INDEX idx_status (status),
    INDEX idx_name (name)
);
```

### Query 4: CREATE TABLE borrowings
```sql
CREATE TABLE borrowings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    equipment_id BIGINT UNSIGNED NOT NULL,
    borrow_date DATE NOT NULL,
    requested_return_date DATE NOT NULL,
    actual_return_date DATE NULL,
    status ENUM('pending', 'approved', 'rejected', 'returned') DEFAULT 'pending',
    approved_by BIGINT UNSIGNED NULL,
    notes TEXT NULL,
    return_condition VARCHAR(50) NULL,
    damage_notes TEXT NULL,
    repair_cost DECIMAL(10,2) DEFAULT 0.00,
    late_days INT DEFAULT 0,
    late_fee DECIMAL(10,2) DEFAULT 0.00,
    total_penalty DECIMAL(10,2) DEFAULT 0.00,
    penalty_paid BOOLEAN DEFAULT FALSE,
    penalty_paid_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (equipment_id) REFERENCES equipment(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_equipment (equipment_id),
    INDEX idx_status (status),
    INDEX idx_borrow_date (borrow_date),
    INDEX idx_return_date (actual_return_date)
);
```

### Query 5: CREATE TABLE activity_logs
```sql
CREATE TABLE activity_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    action VARCHAR(255) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_created (created_at)
);
```

### Query 6: ALTER TABLE - Add column to equipment
```sql
ALTER TABLE equipment 
ADD COLUMN location VARCHAR(100) NULL AFTER description;
```

### Query 7: DROP TABLE - Remove temporary table
```sql
DROP TABLE IF EXISTS temp_imports;
```

---

## PENJELASAN DDL QUERIES

**DDL (Data Definition Language)** adalah perintah SQL untuk mendefinisikan struktur database:

1. **CREATE TABLE** - Membuat tabel baru
2. **ALTER TABLE** - Mengubah struktur tabel (tambah/hapus kolom)
3. **DROP TABLE** - Menghapus tabel
4. **TRUNCATE TABLE** - Menghapus semua data dalam tabel
5. **CREATE INDEX** - Membuat index untuk performa
6. **DROP INDEX** - Menghapus index

Query di atas mencakup:
- 5 CREATE TABLE (users, categories, equipment, borrowings, activity_logs)
- 1 ALTER TABLE (menambah kolom location)
- 1 DROP TABLE (menghapus tabel temporary)

