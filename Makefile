dc := docker-compose
server:
	sudo $(dc) up -d 
down:
	sudo $(dc) down

	