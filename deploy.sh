#!/bin/bash
set -e

# Define deployment target directory
TARGET_DIR="/home/billway/rickdevelopment"

echo "============================================="
echo "  Starting automated deploy to $TARGET_DIR   "
echo "  Time: $(date)"
echo "============================================="

# Change to the application directory
cd "$TARGET_DIR"

# 1. Put the application in maintenance mode
echo "---> Putting application in maintenance mode..."
php artisan down || true

# 2. Fetch latest changes and reset to main branch
echo "---> Pulling latest changes from git..."
git fetch origin main
git reset --hard origin/main

# 3. Install/update Composer dependencies
echo "---> Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# 4. Install/update Node dependencies and compile assets
echo "---> Installing NPM dependencies and building assets..."
npm install
npm run build

# 5. Run database migrations
echo "---> Running database migrations..."
php artisan migrate --force

# 6. Rebuild config/route caches
echo "---> Rebuilding Laravel optimizations..."
php artisan optimize:clear
php artisan optimize

# 7. Bring the application back online
echo "---> Bringing application back online..."
php artisan up

echo "============================================="
echo "  Deployment completed successfully!         "
echo "============================================="
