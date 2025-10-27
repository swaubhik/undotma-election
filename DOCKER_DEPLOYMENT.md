# Docker Production Deployment Guide

## Overview

This guide covers deploying the UN Brahma College Election application using Docker with Nginx and PHP-FPM.

---

## 📦 Docker Architecture

```
┌─────────────────────────────────────────┐
│         Docker Compose Network          │
├─────────────────────────────────────────┤
│                                         │
│  ┌──────────────┐    ┌────────────┐   │
│  │   Nginx      │───→│  PHP-FPM   │   │
│  │  (Port 80)   │    │  (Port 9000)   │
│  └──────────────┘    └────────────┘   │
│                            │           │
│                            ↓           │
│                      ┌──────────────┐  │
│                      │   SQLite DB  │  │
│                      │   (Storage)  │  │
│                      └──────────────┘  │
│                                         │
└─────────────────────────────────────────┘
```

### Services

1. **Nginx** - Web server & reverse proxy

    - Listens on port 80/443
    - Forwards requests to PHP-FPM
    - Serves static files
    - Handles SSL/TLS

2. **PHP-FPM** - Application server

    - Processes PHP requests
    - Runs migrations & commands
    - Manages application logic

3. **SQLite** - Database
    - Persistent storage
    - Single file database
    - No separate server needed

---

## 🚀 Quick Start

### 1. Build the Docker Image

```bash
# Build the image
docker-compose build

# Or build with no cache
docker-compose build --no-cache
```

### 2. Start the Services

```bash
# Start all services
docker-compose up -d

# View logs
docker-compose logs -f

# View specific service logs
docker-compose logs -f app
docker-compose logs -f nginx
```

### 3. Access the Application

```bash
# Access the application
http://localhost

# Stop services
docker-compose down

# Stop and remove volumes
docker-compose down -v
```

---

## 📝 Configuration Files

### Directory Structure

```
docker/
├── nginx/
│   ├── conf.d/
│   │   └── default.conf      # Nginx configuration
│   └── ssl/                  # SSL certificates (optional)
├── php/
│   ├── php.ini               # PHP configuration
│   └── www.conf              # PHP-FPM pool configuration
└── supervisor/
    └── supervisord.conf      # Queue workers & scheduler

.dockerignore                 # Files to ignore
docker-compose.yml            # Compose configuration
DockerFile                     # PHP-FPM image
```

### Files Overview

#### **DockerFile**

-   Base image: `php:8.2-fpm`
-   Installs dependencies
-   Compiles PHP extensions
-   Runs migrations
-   Creates storage link

#### **docker-compose.yml**

-   Defines services: Nginx, PHP-FPM, SQLite
-   Volume mounts
-   Environment variables
-   Port mappings
-   Network configuration

#### **docker/nginx/conf.d/default.conf**

-   Nginx server configuration
-   PHP-FPM upstream
-   Security headers
-   Gzip compression
-   Static file caching
-   SSL support (commented)

#### **docker/php/php.ini**

-   Production PHP settings
-   Memory & execution limits
-   OPCache optimization
-   Security settings

#### **docker/php/www.conf**

-   PHP-FPM pool settings
-   Worker processes
-   Performance tuning

#### **docker/supervisor/supervisord.conf**

-   Queue worker configuration
-   Scheduler configuration
-   Log management

---

## 🔧 Common Commands

### Docker Compose Commands

```bash
# Start services
docker-compose up -d

# Stop services
docker-compose down

# View logs
docker-compose logs -f app

# Execute command in container
docker-compose exec app php artisan migrate

# Rebuild image
docker-compose build

# Remove everything
docker-compose down -v
```

### PHP Artisan Commands

```bash
# Run migrations
docker-compose exec app php artisan migrate

# Create cache files
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# Clear cache
docker-compose exec app php artisan cache:clear

# Tinker shell
docker-compose exec app php artisan tinker

# Run tests
docker-compose exec app php artisan test
```

### Database Commands

```bash
# Access SQLite database
docker-compose exec app sqlite3 database/database.sqlite

# Seed database
docker-compose exec app php artisan db:seed

# Reset database
docker-compose exec app php artisan migrate:refresh --seed
```

---

## 🔒 Security Configuration

### Environment Variables

Create `.env` file with production settings:

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:xxxxx

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite

MAIL_MAILER=log
CACHE_STORE=database

# MSG91 Configuration
MSG91_WIDGET_ID=your_widget_id
MSG91_AUTH_KEY=your_auth_key
MSG91_ACCESS_TOKEN=your_access_token
```

### Security Headers

Nginx automatically adds these headers:

```
X-Frame-Options: SAMEORIGIN
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
Referrer-Policy: no-referrer-when-downgrade
```

### File Permissions

-   Application: `www-data:www-data`
-   Storage directory: `755`
-   SQLite database: `666`
-   Bootstrap cache: `755`

---

## 📊 Performance Tuning

### Nginx Optimization

```
# Gzip compression enabled
# Static file caching: 1 year
# Buffer sizes optimized
# Timeouts configured: 60s
```

### PHP-FPM Optimization

```
# Max workers: 20
# Start servers: 5
# Min spare: 2
# Max spare: 10
# Max requests: 500
```

### OPCache Optimization

```
# Memory: 256MB
# Max files: 20,000
# Validate timestamps: disabled
# Revalidate frequency: 0
```

### Application Optimization

```bash
# Cache Laravel configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

---

## 🔄 Scaling Configuration

### Multiple PHP Workers

Edit `docker-compose.yml`:

```yaml
app:
    replicas: 3 # Run 3 PHP containers
```

### Load Balancing

Nginx automatically load balances to all PHP containers.

### Queue Workers

Edit `docker/supervisor/supervisord.conf`:

```
numprocs = 5  # Run 5 queue workers
```

---

## 🚨 Troubleshooting

### Container won't start

```bash
# Check logs
docker-compose logs app

# Check image build
docker-compose build --no-cache

# Verify configuration
docker-compose config
```

### Permission denied errors

```bash
# Fix file permissions
docker-compose exec app chown -R www-data:www-data /var/www/html
```

### Database connection error

```bash
# Check database file exists
docker-compose exec app ls -la database/database.sqlite

# Run migrations
docker-compose exec app php artisan migrate
```

### Nginx 502 Bad Gateway

```bash
# Check PHP-FPM status
docker-compose ps

# Check PHP-FPM logs
docker-compose logs app

# Restart services
docker-compose restart
```

---

## 🔐 SSL/TLS Setup

### Using Let's Encrypt

1. Generate certificate:

```bash
certbot certonly --standalone -d yourdomain.com
```

2. Copy certificates to `docker/nginx/ssl/`

3. Uncomment HTTPS server block in `default.conf`

4. Rebuild and restart:

```bash
docker-compose build
docker-compose up -d
```

### Self-Signed Certificate (Testing)

```bash
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout docker/nginx/ssl/key.pem \
  -out docker/nginx/ssl/cert.pem
```

---

## 📈 Monitoring

### Health Check

```bash
# Check container health
docker-compose ps

# Specific service health
docker inspect election-app
```

### View Logs

```bash
# Real-time logs
docker-compose logs -f

# Specific service
docker-compose logs -f nginx
docker-compose logs -f app

# Follow last 100 lines
docker-compose logs -f --tail=100
```

### Performance Monitoring

```bash
# CPU and memory usage
docker stats

# Container processes
docker-compose top app
```

---

## 🧪 Testing

### Run Tests in Container

```bash
# Run all tests
docker-compose exec app php artisan test

# Run specific test
docker-compose exec app php artisan test tests/Feature/ExampleTest.php

# Run with coverage
docker-compose exec app php artisan test --coverage
```

### Manual Testing

```bash
# Access container shell
docker-compose exec app bash

# Run artisan commands
php artisan tinker
php artisan migrate:fresh --seed
```

---

## 📦 Deployment Steps

### Production Deployment

1. **Prepare Server**

    - Install Docker & Docker Compose
    - Clone repository
    - Create `.env` file

2. **Build Image**

    ```bash
    docker-compose build --no-cache
    ```

3. **Start Services**

    ```bash
    docker-compose up -d
    ```

4. **Verify Services**

    ```bash
    docker-compose ps
    docker-compose logs app
    ```

5. **Test Application**
    ```bash
    curl http://localhost
    ```

### Zero-Downtime Deployment

```bash
# Build new image
docker-compose build

# Start new containers alongside old ones
docker-compose up -d

# Nginx automatically load balances
# Old containers continue serving traffic

# After new containers are healthy
docker-compose down
```

---

## 🛠️ Maintenance

### Update Dependencies

```bash
# Update Docker image
docker-compose build --no-cache

# Update PHP packages
docker-compose exec app composer update

# Restart services
docker-compose restart
```

### Database Backups

```bash
# Backup SQLite database
docker-compose exec app cp database/database.sqlite database/backup.sqlite

# Or copy to host
docker cp election-app:/var/www/html/database/database.sqlite ./backup.sqlite
```

### Logs Management

```bash
# View application logs
docker-compose exec app tail -f storage/logs/laravel.log

# Clear old logs
docker-compose exec app rm storage/logs/*
```

---

## 📚 Documentation

### Nginx

-   [Nginx Documentation](https://nginx.org/en/docs/)
-   [Nginx Configuration](https://nginx.org/en/docs/http/ngx_http_core_module.html)

### Docker

-   [Docker Compose Documentation](https://docs.docker.com/compose/)
-   [Docker Best Practices](https://docs.docker.com/develop/dev-best-practices/)

### Laravel

-   [Laravel Deployment](https://laravel.com/docs/deployment)
-   [Laravel Docker](https://laravel.com/docs/sail)

---

## 🎓 Best Practices

✅ Always use `--no-cache` for production builds  
✅ Set `APP_DEBUG=false` in production  
✅ Use separate `.env` files for each environment  
✅ Keep Docker images updated  
✅ Use volume mounts for configuration files  
✅ Implement health checks  
✅ Monitor container logs regularly  
✅ Use environment-specific docker-compose files  
✅ Backup database regularly  
✅ Use Docker networks for service communication

---

## 📞 Support

For issues or questions:

1. Check Docker logs: `docker-compose logs`
2. Review configuration files
3. Consult Docker documentation
4. Check Laravel documentation

---

**Version**: 1.0  
**Updated**: October 27, 2025  
**Status**: Production Ready ✅
