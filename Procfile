web: vendor/bin/heroku-php-apache2 public/
worker: /bin/sh -c "while [ true ]; do (php /app/artisan schedule:run --verbose --no-interaction &); sleep 60; done"
release: php artisan migrate --force
