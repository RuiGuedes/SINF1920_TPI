sed -i -- 's/DB_HOST=localhost/DB_HOST=postgres/g' .env
cat .env

docker-compose up -d
docker-compose -f docker-compose.yml exec webserver composer install
docker-compose -f docker-compose.yml exec webserver php artisan cache:clear
docker-compose -f docker-compose.yml exec webserver php artisan config:clear
docker-compose -f docker-compose.yml exec webserver php artisan route:clear
# docker-compose -f docker-compose.yml exec webserver php artisan db:seed
docker-compose -f docker-compose.yml exec webserver php artisan config:cache
docker-compose -f docker-compose.yml exec webserver php artisan route:cache
docker-compose -f docker-compose.yml exec webserver php artisan serve --host 0.0.0.0

