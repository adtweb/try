#!make
include .env

up:
	docker compose up -d

build:
	docker compose up -d --build

down:
	docker compose down

ps:
	docker ps

bash:
	docker exec -ti ${APP_DIR}-laravel-1 bash

key:
	docker exec -ti ${APP_DIR}-laravel-1 php artisan key:generate

package-discover:
	docker exec -ti ${APP_DIR}-laravel-1 php artisan package:discover

seed:
	docker exec -ti ${APP_DIR}-laravel-1 php artisan db:seed

migrate:
	docker exec -ti ${APP_DIR}-laravel-1 php artisan migrate

migrate-refresh:
	docker exec -ti ${APP_DIR}-laravel-1 php artisan migrate:refresh

install:
	docker exec -ti ${APP_DIR}-laravel-1 composer install

composer-update:
	docker exec -ti ${APP_DIR}-laravel-1 composer update

local-update: install migrate

refresh:
	docker exec -ti ${APP_DIR}-laravel-1 php artisan migrate:refresh --seed

pint-diff:
	docker exec -ti ${APP_DIR}-laravel-1 ./vendor/bin/pint --test

pint:
	docker exec -ti ${APP_DIR}-laravel-1 ./vendor/bin/pint

composer-test:
	docker exec -ti ${APP_DIR}-laravel-1 composer test

tinker:
	docker exec -ti ${APP_DIR}-laravel-1 php artisan tinker
