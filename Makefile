.DEFAULT_GOAL := up

# --------------------- DOCKER COMPOSE -------------------
up:
	docker compose up -d

down:
	docker compose down

# --------------------- APP -------------------

install:
	docker compose exec hanviet_system_php composer install && docker compose exec hanviet_system_php php artisan key:generate

permission:
	sudo chmod -R 777 bootstrap public storage

build:
	docker compose exec wedding_system_node npm run build

run:
	docker compose exec wedding_system_node npm run dev

cache:
	docker compose exec hanviet_system_php php artisan cache:clear && docker compose exec hanviet_system_php php artisan config:clear && docker compose exec hanviet_system_php php artisan config:cache