# 🚀 Production Deployment Guide

## Prerequisites
- PHP >= 8.2
- MySQL/MariaDB
- Composer

## Deployment Steps

### 1. Clone & Install
```bash
git clone <your-repo-url>
cd WebLaravel
composer install --no-dev --optimize-autoloader
```

### 2. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and configure:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# TMDB API Key (optional for production, only needed if you want to add more content)
# TMDB_API_KEY=your_tmdb_api_key
```

### 3. Database Setup
```bash
# Run migrations
php artisan migrate --force

# Seed all data (categories, admin users, videos, seasons, episodes)
php artisan db:seed --force
```

This will seed:
- **27 Categories** (Movie & TV genres)
- **2 Admin Users**:
  - Email: `admin@projectN` / Password: `password`
  - Email: `irvanbayu@admin.com` / Password: `password`
- **66 Videos** (Movies & TV Series)
- **All Seasons & Episodes** for TV Series

### 4. Optimize for Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. Set Permissions
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 6. Web Server Configuration

#### Apache
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /path/to/WebLaravel/public

    <Directory /path/to/WebLaravel/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/laravel-error.log
    CustomLog ${APACHE_LOG_DIR}/laravel-access.log combined
</VirtualHost>
```

#### Nginx
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/WebLaravel/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Post-Deployment

### Change Admin Passwords
**IMPORTANT**: Change default admin passwords after deployment:
1. Login as admin
2. Go to Profile
3. Change password from default `password` to something secure

### Add More Content
To add more movies/TV series:
1. Login as admin
2. Go to Admin > Help Guide for step-by-step instructions
3. Or visit `/admin/help` for documentation

## Troubleshooting

### Seeding Fails
If seeding fails, try:
```bash
php artisan migrate:fresh --force
php artisan db:seed --force
```

### File Not Found Errors
Make sure storage is linked:
```bash
php artisan storage:link
```

### 500 Errors
Check logs:
```bash
tail -f storage/logs/laravel.log
```

Clear cache:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Security Checklist
- [ ] Change default admin passwords
- [ ] Set `APP_DEBUG=false` in production
- [ ] Use strong `APP_KEY`
- [ ] Configure SSL/HTTPS
- [ ] Set proper file permissions (755 for directories, 644 for files)
- [ ] Keep Composer dependencies updated
- [ ] Enable firewall and fail2ban
- [ ] Regular database backups

## Updates
To update the application:
```bash
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

**Need help?** Check `/admin/help` for admin documentation.
