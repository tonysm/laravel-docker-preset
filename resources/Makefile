.PHONY: ps up down test fresh

up:
	docker-compose up -d

down:
	docker-compose down

ps:
	docker-compose ps

docker-test:
	docker-compose exec app php vendor/bin/phpunit

test: up docker-test

docker-fresh:
	docker-compose exec app php artisan migrate:fresh

fresh: up docker-fresh


default: ps
