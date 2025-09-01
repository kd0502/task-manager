#!/bin/sh
set -e

cd /var/www/task-manager

if [ ! -d "vendor" ]; then
  echo "📦 Vendor folder not found. Running composer install..."
  composer install --no-interaction --optimize-autoloader
else
  echo "🔄 Vendor folder exists. Updating dependencies..."
  composer update --no-interaction --optimize-autoloader
fi

echo "⏳ Waiting for database..."
until nc -z db 3306; do
  sleep 2
done
echo "✅ Database is ready!"

run_migrations() {
  ENV=$1
  echo "⚙️ Checking migrations for $ENV..."

  if [ "$RESET_DB" = "true" ]; then
    echo "⚠️ RESET_DB=true → Dropping and recreating $ENV database..."
    php bin/console doctrine:database:drop --force --env=$ENV || true
    php bin/console doctrine:database:create --if-not-exists --env=$ENV

    # Reset migration metadata
    php bin/console doctrine:migrations:sync-metadata-storage --env=$ENV --no-interaction || true
  else
    # Ensure database exists
    php bin/console doctrine:database:create --if-not-exists --env=$ENV || true

    # Ensure migration metadata table exists
    php bin/console doctrine:migrations:sync-metadata-storage --env=$ENV --no-interaction || true
  fi

  # 👉 Check if migration files exist, generate first one if not
  if [ ! "$(ls -A migrations/*.php 2>/dev/null)" ]; then
    echo "📜 No migrations found → generating initial migration..."
    php bin/console doctrine:migrations:diff --env=$ENV || true
  fi

  # Run pending migrations
  echo "🚀 Running migrations for $ENV env..."
  php bin/console doctrine:migrations:migrate --no-interaction --env=$ENV
}

# Run for dev
run_migrations dev

# Run for test
run_migrations test

# Run the main container command (php-fpm)
exec "$@"
