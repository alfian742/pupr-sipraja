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
	docker compose exec app composer install --no-dev --optimize-autoloader

key:
	docker compose exec app php artisan key:generate --force

migrate:
	docker compose exec app php artisan migrate --force

fresh:
	docker compose exec app php artisan migrate:fresh --seed --force

clear:
	docker compose exec app php artisan optimize:clear

optimize:
	docker compose exec app php artisan optimize

storage-link:
	docker compose exec app php artisan storage:link

build-assets:
	docker run --rm -v "$(PWD)":/app -w /app node:22-alpine sh -lc "npm ci && npm run build"

queue-restart:
	docker compose exec app php artisan queue:restart
