# Hidrodinamika Link Profile - PHP Version

Website Linktree-style untuk Hidrodinamika dengan sistem autentikasi dan database MySQL.

## Features

- 🔒 Secure PHP Authentication
- 💾 MySQL Database Backend
- 🌓 Dark/Light Mode
- 📱 Fully Responsive
- ⚡ AJAX-powered Admin Panel
- 🎨 Modern UI/UX

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher / MariaDB 10.2+
- Apache/Nginx web server
- mod_rewrite enabled (for clean URLs)

## Installation

### 1. Setup Database

```bash
# Login to MySQL
mysql -u root -p

# Create database and import schema
mysql -u root -p < database/schema.sql
```

### 2. Configure Database Connection

Edit `includes/config.php` and update database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'hidrodinamika_links');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### 3. Setup Web Server

#### Apache (with .htaccess)

Make sure mod_rewrite is enabled:
```bash
sudo a2enmod rewrite
sudo service apache2 restart
```

#### Nginx

Add to your server block:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### 4. Set Permissions

```bash
chmod 755 includes/
chmod 644 includes/*.php
chmod 755 assets/
```

### 5. Access the Application

- Public Page: `http://localhost/`
- Admin Login: `http://localhost/login.php`

## Default Credentials

```
Username: admin
Password: admin123
```

**⚠️ IMPORTANT**: Change these credentials after first login!

## File Structure

```
├── index.php              # Public link page
├── login.php              # Admin login
├── admin.php              # Admin panel
├── logout.php             # Logout handler
├── includes/              # PHP includes
│   ├── config.php         # Configuration
│   ├── Database.php       # DB connection
│   ├── auth.php           # Authentication
│   └── functions.php      # Helper functions
├── models/                # Data models
│   ├── User.php
│   ├── Profile.php
│   └── Link.php
├── assets/                # Static assets
│   ├── css/
│   │   └── styles.css
│   ├── js/
│   │   └── admin.js
│   └── images/
│       └── logo-hdi.png
└── database/
    ├── schema.sql         # Database schema
    └── README.md          # Database docs
```

## Usage

### Admin Panel

1. Login at `/login.php`
2. Edit profile information
3. Add/Edit/Delete links
4. Toggle link visibility
5. Changes are saved to database

### Public Page

- Displays all active links
- Click tracking (analytics)
- Theme toggle (saved in cookies)

## Database Schema

### Tables

- `users` - Admin users
- `profile` - Profile information
- `links` - Link items
- `sessions` - Login sessions (optional)
- `analytics` - Click tracking
- `settings` - Application settings

See `database/README.md` for detailed schema documentation.

## Security Features

- ✅ Password hashing with bcrypt
- ✅ SQL injection prevention (prepared statements)
- ✅ XSS protection (htmlspecialchars)
- ✅ Session security
- ✅ CSRF protection ready
- ✅ Input validation

## Deployment

### Shared Hosting (cPanel)

1. Upload files via FTP/File Manager
2. Create MySQL database via cPanel
3. Import `database/schema.sql`
4. Update `includes/config.php`
5. Done!

### VPS/Dedicated Server

1. Clone repository
2. Setup Apache/Nginx
3. Create database
4. Configure permissions
5. Update config.php

### Recommended Hosts

- Niagahoster
- Hostinger
- Rumahweb
- DigitalOcean (VPS)

## Troubleshooting

### Database Connection Error

- Check credentials in `includes/config.php`
- Verify MySQL service is running
- Check database exists

### Permission Denied

```bash
chmod -R 755 .
chmod -R 644 *.php
```

### Session Issues

- Check PHP session configuration
- Verify session directory is writable

## Maintenance

### Backup Database

```bash
mysqldump -u root -p hidrodinamika_links > backup_$(date +%Y%m%d).sql
```

### Update Admin Password

```php
// Run this in PHP or add to a script
$newPassword = password_hash('new_password', PASSWORD_BCRYPT);
// Update in database
```

## License

© 2025 Hidrodinamika. All rights reserved.

## Support

For issues or questions, contact your developer.
