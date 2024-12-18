#!make
include .env

up:
	docker compose up -d

restart:
	./vendor/bin/sail down && ./vendor/bin/sail up -d

rebuild:
	docker compose up -d --build

down:
	docker compose down

ps:
	docker ps

bash:
	docker exec -ti ${APP_DIR}-laravel bash

key:
	docker exec -ti ${APP_DIR}-laravel php artisan key:generate

package-discover:
	docker exec -ti ${APP_DIR}-laravel php artisan package:discover

seed:
	docker exec -ti ${APP_DIR}-laravel php artisan db:seed

migrate:
	docker exec -ti ${APP_DIR}-laravel php artisan migrate

migrate-refresh:
	docker exec -ti ${APP_DIR}-laravel php artisan migrate:refresh

install:
	docker exec -ti ${APP_DIR}-laravel composer install

composer-update:
	docker exec -ti ${APP_DIR}-laravel composer update

local-update: install migrate

mysql:
	docker exec -ti ${APP_DIR}-mysql mysql -u${DB_USERNAME} -p${DB_PASSWORD} ${APP_DIR}

refresh:
	docker exec -ti ${APP_DIR}-laravel php artisan migrate:refresh --seed

pint-diff:
	docker exec -ti ${APP_DIR}-laravel ./vendor/bin/pint --test

pint:
	docker exec -ti ${APP_DIR}-laravel ./vendor/bin/pint

composer-test:
	docker exec -ti ${APP_DIR}-laravel composer test

tinker:
	docker exec -ti ${APP_DIR}-laravel php artisan tinker
