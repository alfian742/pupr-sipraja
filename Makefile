up:
	docker compose up -d --build

down:
	docker compose down

restart:
	docker compose restart

logs:
	docker compose logs -f

app:
	docker compose exec app bash

composer-install:
	docker compose exec app composer install

key:
	docker compose exec app php artisan key:generate

migrate:
	docker compose exec app php artisan migrate

fresh:
	docker compose exec app php artisan migrate:fresh --seed

clear:
	docker compose exec app php artisan optimize:clear

storage-link:
	docker compose exec app php artisan storage:link

build-assets:
	docker compose run --rm vite npm run build

queue-restart:
	docker compose exec app php artisan queue:restart
