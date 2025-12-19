# Installation Guide - PHP Version

Panduan lengkap instalasi Hidrodinamika Link Profile (PHP Version)

## Prerequisites

Pastikan Anda sudah memiliki:
- ✅ PHP 7.4+ installed
- ✅ MySQL/MariaDB installed
- ✅ Web server (Apache/Nginx/XAMPP/WAMP)
- ✅ Akses ke terminal/command line

## Step-by-Step Installation

### 1. Persiapan Database

#### Menggunakan MySQL Command Line

```bash
# Login ke MySQL
mysql -u root -p

# Buat database
CREATE DATABASE hidrodinamika_links CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Keluar dari MySQL
exit;

# Import schema
mysql -u root -p hidrodinamika_links < database/schema.sql
```

#### Menggunakan phpMyAdmin

1. Buka phpMyAdmin di browser
2. Klik "New" untuk buat database baru
3. Nama: `hidrodinamika_links`
4. Collation: `utf8mb4_unicode_ci`
5. Klik "Import" tab
6. Pilih file `database/schema.sql`
7. Klik "Go"

### 2. Konfigurasi Database

Edit file `includes/config.php`:

```php
define('DB_HOST', 'localhost');     // Host database
define('DB_PORT', 3306);            // Port MySQL (default 3306)
define('DB_NAME', 'hidrodinamika_links');  // Nama database
define('DB_USER', 'root');          // Username MySQL
define('DB_PASS', '');              // Password MySQL
```

### 3. Setup Web Server

#### Menggunakan XAMPP (Windows)

1. Copy folder project ke `C:\xampp\htdocs\`
2. Rename folder menjadi `hidrodinamika`
3. Start Apache dan MySQL dari XAMPP Control Panel
4. Buka browser: `http://localhost/hidrodinamika/`

#### Menggunakan WAMP (Windows)

1. Copy folder project ke `C:\wamp64\www\`
2. Rename folder menjadi `hidrodinamika`
3. Start WAMP server
4. Buka browser: `http://localhost/hidrodinamika/`

#### Menggunakan PHP Built-in Server (Development)

```bash
cd "d:\Hidrodinamika Link Profile"
php -S localhost:8000
```

Buka browser: `http://localhost:8000/`

### 4. Verifikasi Instalasi

1. **Test Public Page**
   - Buka: `http://localhost/hidrodinamika/`
   - Harus muncul halaman link profile

2. **Test Login**
   - Buka: `http://localhost/hidrodinamika/login.php`
   - Login dengan:
     - Username: `admin`
     - Password: `admin123`

3. **Test Admin Panel**
   - Setelah login, harus masuk ke admin panel
   - Coba edit profile
   - Coba tambah link baru

## Troubleshooting

### Error: "Database connection failed"

**Solusi:**
1. Pastikan MySQL service running
2. Check credentials di `includes/config.php`
3. Pastikan database sudah dibuat

### Error: "Call to undefined function mysqli_connect()"

**Solusi:**
```bash
# Enable mysqli extension
# Edit php.ini, uncomment:
extension=mysqli
extension=pdo_mysql

# Restart web server
```

### Error: "Permission denied"

**Solusi (Linux/Mac):**
```bash
chmod -R 755 .
chmod -R 644 *.php
```

### Error: "Session could not be started"

**Solusi:**
1. Pastikan folder session writable
2. Check `session.save_path` di php.ini

### Halaman blank/putih

**Solusi:**
1. Enable error reporting di `includes/config.php`:
   ```php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ```
2. Check error log di web server

## Production Deployment

### Shared Hosting (cPanel)

1. **Upload Files**
   - Compress folder menjadi .zip
   - Upload via File Manager atau FTP
   - Extract di public_html

2. **Create Database**
   - MySQL Databases → Create New Database
   - Create MySQL User
   - Add User to Database (All Privileges)

3. **Import Schema**
   - phpMyAdmin → Import
   - Select `database/schema.sql`

4. **Update Config**
   - Edit `includes/config.php` dengan credentials baru

5. **Set Permissions**
   - File Manager → Select files → Change Permissions
   - Folders: 755
   - Files: 644

### VPS/Cloud Server

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install LAMP stack
sudo apt install apache2 mysql-server php php-mysql -y

# Enable Apache modules
sudo a2enmod rewrite
sudo systemctl restart apache2

# Clone/Upload project
cd /var/www/html
# Upload your files here

# Set permissions
sudo chown -R www-data:www-data /var/www/html
sudo chmod -R 755 /var/www/html

# Create database
sudo mysql -u root -p
CREATE DATABASE hidrodinamika_links;
exit;

# Import schema
sudo mysql -u root -p hidrodinamika_links < database/schema.sql

# Configure
nano includes/config.php
# Update credentials

# Restart Apache
sudo systemctl restart apache2
```

## Security Checklist

Sebelum production:

- [ ] Ganti default admin password
- [ ] Set `error_reporting(0)` di config.php
- [ ] Enable HTTPS
- [ ] Update database credentials
- [ ] Set proper file permissions
- [ ] Enable firewall
- [ ] Regular database backups

## Next Steps

1. ✅ Login ke admin panel
2. ✅ Ganti password default
3. ✅ Edit profile information
4. ✅ Tambah/edit links
5. ✅ Test di berbagai browser
6. ✅ Test responsive di mobile

## Support

Jika ada masalah:
1. Check error logs
2. Review troubleshooting section
3. Contact developer

---

**Selamat! Aplikasi sudah siap digunakan! 🎉**
