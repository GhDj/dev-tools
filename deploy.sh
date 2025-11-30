#!/bin/bash

# Dev Tools - Shared Hosting Deployment Script
# Run this script after uploading files to your shared hosting

echo "=== Dev Tools Deployment Script ==="
echo ""

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;")
echo "PHP Version: $PHP_VERSION"

# Check if composer is available
if command -v composer &> /dev/null; then
    echo "Composer found: $(composer --version)"
else
    echo "ERROR: Composer not found. Please install composer or use composer.phar"
    echo "You can download it from: https://getcomposer.org/download/"
    exit 1
fi

# Install dependencies (production only)
echo ""
echo "Installing production dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Create storage directories if they don't exist
echo ""
echo "Creating storage directories..."
mkdir -p storage/app/public
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set permissions
echo ""
echo "Setting permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Copy environment file if it doesn't exist
if [ ! -f .env ]; then
    echo ""
    echo "Creating .env file from .env.example..."
    cp .env.example .env

    # Generate application key
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Clear and cache configuration
echo ""
echo "Optimizing application..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "=== Deployment Complete ==="
echo ""
echo "Next steps:"
echo "1. Update .env with your domain in APP_URL"
echo "2. Ensure your web root points to the 'public' folder"
echo "3. If using subdirectory, update APP_URL accordingly"
echo ""
echo "Your Dev Tools are ready at: $(grep APP_URL .env | cut -d '=' -f2)"
