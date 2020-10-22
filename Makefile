#!make
dc := docker-compose
# define standard colors
BLACK        := $(shell tput -Txterm setaf 0)
RED          := $(shell tput -Txterm setaf 1)
GREEN        := $(shell tput -Txterm setaf 2)
YELLOW       := $(shell tput -Txterm setaf 3)
LIGHTPURPLE  := $(shell tput -Txterm setaf 4)
PURPLE       := $(shell tput -Txterm setaf 5)
BLUE         := $(shell tput -Txterm setaf 6)
WHITE        := $(shell tput -Txterm setaf 7)

RESET := $(shell tput -Txterm sgr0)

TARGET_COLOR := $(BLUE)
POUND = \#
.PHONY: no_targets__ info help build deploy doc
	no_targets__:
.DEFAULT_GOAL := help
colors: ## show all the colors
	@echo "${BLACK}BLACK${RESET}"
	@echo "${RED}RED${RESET}"
	@echo "${GREEN}GREEN${RESET}"
	@echo "${YELLOW}YELLOW${RESET}"
	@echo "${LIGHTPURPLE}LIGHTPURPLE${RESET}"
	@echo "${PURPLE}PURPLE${RESET}"
	@echo "${BLUE}BLUE${RESET}"
	@echo "${WHITE}WHITE${RESET}"

up: ## Up Container
	sudo $(dc) up -d 
down: ## Down Container
	sudo $(dc) down
vendor: composer.lock ## Install vendor
	composer install
migration: up ##  Migration
	sudo $(dc)  exec -u www-data php php bin/console make:migration
migrate: up   ##  Migrate
	sudo $(dc) exec -u www-data php php bin/console doctrine:migrations:migrate
encore-dev: up   ##  encore dev
	sudo $(dc)  exec -u www-data php yarn encore dev
encore-prod: up  ##  encore prod
	sudo $(dc)  exec -u www-data php yarn encore production
encore-watch: up  ##  encore watch
	sudo $(dc)  exec -u www-data php yarn encore dev --watch
cache: up      ##  cache clear
	sudo $(dc) 	exec -u www-data php php bin/console cac:c
fixtures: up  ##  Load fixtures
	sudo $(dc)  exec -u www-data php php bin/console doctrine:fixtures:load
install-app: vendor   ##  install application
	sudo $(dc) exec -u www-data php yarn install
	sudo $(dc) exec - exec -u www-data php yarn encore production
phpcs: up ## executes coding standards
	sudo $(dc)  exec -u www-data php php ./vendor/bin/php-cs-fixer fix --using-cache=no
	sudo $(dc)  exec -u www-data php php ./vendor/bin/phpcs src/
phpstan: ## executes php analizers
	sudo $(dc)  exec -u www-data php php ./vendor/bin/phpstan analyse src/
phpmd: ## executes coding standards in dry run mode PHP Mess Detector applique certaines règles pour vérifier la qualité
	sudo $(dc)  exec -u www-data php php ./vendor/bin/phpmd src/ text phpmd.xml
.PHONY: psalm
psalm: ## execute psalm analyzer
	sudo $(dc)  exec -u www-data php php  ./vendor/bin/psalm --show-info=false
.PHONY: logs
logs: 	##  look for 's' service logs, make s=php logs
		$(dc) logs -f $(s)
.PHONY: help
help:
	@echo ""
	@echo "    ${BLACK}:: ${RED}Self-documenting Makefile${RESET} ${BLACK}::${RESET}"
	@echo ""
	@echo "Document targets by adding '$(POUND)$(POUND) comment' after the target"
	@echo ""
	@echo "${BLACK}-----------------------------------------------------------------${RESET}"
	@grep -E '^[a-zA-Z_0-9%-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "${TARGET_COLOR}%-30s${RESET} %s\n", $$1, $$2}'
