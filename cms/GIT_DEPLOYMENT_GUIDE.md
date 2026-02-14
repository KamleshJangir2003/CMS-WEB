# Git Clone Production Deployment Guide

## Step 1: Server pe Git Clone
```bash
cd /path/to/your/domain/folder
git clone https://github.com/your-username/kwikster-web.git
cd kwikster-web/cms
```

## Step 2: Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Edit .env file with production settings
nano .env
```

## Step 3: Install Dependencies
```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

## Step 4: Laravel Setup
```bash
# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

## Step 5: Web Server Configuration

### For Apache (.htaccess in public folder):
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [L]
```

### Document Root should point to:
`/path/to/kwikster-web/cms/public`

## Step 6: Future Updates
```bash
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Important Notes:
- Never commit .env file to git
- Always backup database before updates
- Test on staging first
- Keep sensitive data in .env only