start: ## Start the project
	docker-compose up -d --remove-orphans --no-recreate

stop: ## Stop the project
	docker-compose down

bash: ## Enter the app container with bash
	docker-compose exec app bash

emulate: ## Enter the app container and emulate parking
	docker-compose exec app php app emulate

composer-install: ## Run Unit tests
	docker-compose exec app composer install

unit: ## Run Unit tests
	docker-compose exec app vendor/bin/phpunit .

run: start composer-install unit emulate ##Run all
