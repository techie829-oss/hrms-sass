#!/bin/bash
set -e

echo "🚀 Starting HRMS Deployment..."

# 1. Enter maintenance mode if possible
if [ -f artisan ]; then
    docker exec hrms_app php artisan down --render="errors::503" || true
fi

# 2. Pull latest code (Uncomment if using Git on server)
# echo "📦 Pulling latest code..."
# git pull origin main

# 3. Build containers and start in detached mode
echo "🏗️ Building and starting Docker containers..."
docker compose up -d --build

# 4. Install PHP dependencies
echo "📦 Installing Composer dependencies..."
docker exec -u root hrms_app git config --global --add safe.directory /var/www
docker exec -u root hrms_app composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# 4.5 Generate App Key if missing
echo "🔑 Generating App Key (if missing)..."
docker exec hrms_app php artisan key:generate --no-interaction --force || true

# 5. Run Migrations
echo "🔄 Running Database Migrations (PostgreSQL)..."
docker exec hrms_app php artisan migrate --force

# 6. Optimize framework and clear caches
echo "🧹 Optimizing Laravel configuration & clearing caches..."
docker exec hrms_app php artisan optimize:clear
docker exec hrms_app php artisan view:cache
docker exec hrms_app php artisan config:cache
docker exec hrms_app php artisan event:cache
docker exec hrms_app php artisan route:cache

# 7. Restart queue workers (Horizon)
echo "⚡ Restarting Horizon / Queue Workers..."
docker exec hrms_app php artisan horizon:terminate || true

# 8. Bring application up
echo "✅ Bringing application out of maintenance mode..."
docker exec hrms_app php artisan up

echo "🎉 Deployment completed successfully!"
