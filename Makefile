dc := docker-compose
server:
	sudo $(dc) up -d 
down:
	sudo $(dc) down

vendor/bin/php-cs-fixer fix --using-cache=no
./vendor/bin/phpcs src/
vendor/bin/phpstan analyse src/