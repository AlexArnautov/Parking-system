start: ## Start the project
	@echo ""
	@echo "==================== Start Container ========================"
	@echo ""
	@docker-compose up -d --remove-orphans --no-recreate

stop:
	@docker-compose down

bash: ## Enter the app container with bash
	@docker-compose exec app bash

emulate: ## Enter the app container and emulate parking
	@echo ""
	@echo "==================== Application ============================"
	@echo ""
	@docker-compose exec app php app emulate

composer-install: ## Run Unit tests
	@echo ""
	@echo "==================== Composer Install ======================="
	@echo ""
	@docker-compose exec app composer install

unit: ## Run Unit tests
	@echo ""
	@echo "==================== Unit Tests ============================="
	@echo ""
	@docker-compose exec app vendor/bin/phpunit --no-coverage;

run: start composer-install unit emulate ##Run all

