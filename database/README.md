# Database Documentation

## Overview

Database schema untuk Hidrodinamika Link Profile dengan support untuk MySQL dan PostgreSQL.

## Tables

### 1. users
Menyimpan data admin users yang dapat login ke admin panel.

| Column | Type | Description |
|--------|------|-------------|
| id | INT/SERIAL | Primary key |
| username | VARCHAR(50) | Username unik untuk login |
| password_hash | VARCHAR(255) | Password yang di-hash (bcrypt) |
| email | VARCHAR(100) | Email admin |
| full_name | VARCHAR(100) | Nama lengkap admin |
| is_active | BOOLEAN | Status aktif user |
| created_at | TIMESTAMP | Waktu pembuatan |
| updated_at | TIMESTAMP | Waktu update terakhir |
| last_login | TIMESTAMP | Waktu login terakhir |

**Default User:**
- Username: `admin`
- Password: `admin123`
- Email: `admin@hidrodinamika.com`

### 2. profile
Menyimpan informasi profil yang ditampilkan di halaman publik.

| Column | Type | Description |
|--------|------|-------------|
| id | INT/SERIAL | Primary key |
| name | VARCHAR(200) | Nama profil/perusahaan |
| bio | TEXT | Bio/deskripsi |
| logo_url | VARCHAR(500) | Path ke logo |
| theme | VARCHAR(20) | Theme preference (dark/light) |
| created_at | TIMESTAMP | Waktu pembuatan |
| updated_at | TIMESTAMP | Waktu update terakhir |

### 3. links
Menyimpan semua link items.

| Column | Type | Description |
|--------|------|-------------|
| id | INT/SERIAL | Primary key |
| title | VARCHAR(200) | Judul link |
| subtitle | VARCHAR(300) | Subtitle/deskripsi |
| url | VARCHAR(1000) | URL tujuan |
| icon | VARCHAR(50) | Nama icon |
| is_active | BOOLEAN | Status aktif/nonaktif |
| display_order | INT | Urutan tampilan |
| click_count | INT | Total klik |
| created_at | TIMESTAMP | Waktu pembuatan |
| updated_at | TIMESTAMP | Waktu update terakhir |

### 4. sessions
Menyimpan session admin yang sedang login.

| Column | Type | Description |
|--------|------|-------------|
| id | INT/SERIAL | Primary key |
| user_id | INT | Foreign key ke users |
| session_token | VARCHAR(255) | Token session unik |
| ip_address | VARCHAR(45) | IP address user |
| user_agent | TEXT | Browser user agent |
| expires_at | TIMESTAMP | Waktu expiry session |
| created_at | TIMESTAMP | Waktu pembuatan |

### 5. analytics
Menyimpan data klik pada setiap link untuk analytics.

| Column | Type | Description |
|--------|------|-------------|
| id | INT/SERIAL | Primary key |
| link_id | INT | Foreign key ke links |
| clicked_at | TIMESTAMP | Waktu klik |
| ip_address | VARCHAR(45) | IP address pengunjung |
| user_agent | TEXT | Browser user agent |
| referrer | VARCHAR(500) | Referrer URL |
| country | VARCHAR(100) | Negara pengunjung |
| city | VARCHAR(100) | Kota pengunjung |

### 6. settings
Menyimpan application settings.

| Column | Type | Description |
|--------|------|-------------|
| id | INT/SERIAL | Primary key |
| setting_key | VARCHAR(100) | Key setting (unique) |
| setting_value | TEXT | Value setting |
| description | VARCHAR(500) | Deskripsi setting |
| created_at | TIMESTAMP | Waktu pembuatan |
| updated_at | TIMESTAMP | Waktu update terakhir |

## Views

### v_active_links
View untuk mendapatkan semua active links dengan analytics hari ini.

### v_active_sessions
View untuk mendapatkan semua active sessions yang belum expired.

## Stored Procedures / Functions

### MySQL: sp_clean_expired_sessions()
### PostgreSQL: fn_clean_expired_sessions()
Menghapus semua expired sessions dari database.

### MySQL: sp_get_link_analytics(link_id, days)
Mendapatkan analytics untuk link tertentu dalam periode hari tertentu.

## Triggers

### tr_update_click_count
Otomatis update click_count di table links setiap ada insert di analytics.

### tr_*_updated_at (PostgreSQL only)
Otomatis update kolom updated_at saat ada perubahan data.

## Indexes

Indexes dibuat untuk optimasi query pada:
- Username dan email di table users
- Session token dan expiry di table sessions
- Link ID dan clicked_at di table analytics
- Active status dan display order di table links

## Installation

### MySQL
```bash
mysql -u root -p < database/schema.sql
```

### PostgreSQL
```bash
psql -U postgres < database/schema-postgresql.sql
```

## Security Notes

1. **Password Hashing**: Gunakan bcrypt dengan cost minimal 10
2. **Session Tokens**: Generate menggunakan cryptographically secure random
3. **SQL Injection**: Gunakan prepared statements
4. **Input Validation**: Validasi semua input sebelum insert/update

## Maintenance

### Clean Expired Sessions
**MySQL:**
```sql
CALL sp_clean_expired_sessions();
```

**PostgreSQL:**
```sql
SELECT fn_clean_expired_sessions();
```

### Get Link Analytics
**MySQL:**
```sql
CALL sp_get_link_analytics(1, 30); -- Link ID 1, last 30 days
```

## Backup

### MySQL
```bash
mysqldump -u root -p hidrodinamika_links > backup.sql
```

### PostgreSQL
```bash
pg_dump -U postgres hidrodinamika_links > backup.sql
```

## Migration

Untuk migrate dari localStorage ke database, buat script untuk:
1. Export data dari localStorage
2. Transform ke format SQL
3. Import ke database

## API Integration

Lihat file `api/` untuk REST API endpoints yang menggunakan database ini.
