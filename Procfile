web: vendor/bin/heroku-php-apache2 public/
worker: php /app/artisan queue:listen --tries=10 --delay=20 --memory=64 --sleep=0
release: php artisan migrate
