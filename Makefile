.PHONY: run up stop composer-install

NAME ?= rest-control-lib
CONSOLE := docker-compose -p $(NAME) -f ./Docker/docker.yml

start:
	@-$(CONSOLE) up -d
stop:
	@-$(CONSOLE) stop
build:
	$(CONSOLE) build --pull
	make composer-install
composer-install:
	@-$(CONSOLE) run --service-ports --workdir="/app" --rm cli composer install