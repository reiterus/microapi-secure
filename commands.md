# Makefile commands

#### Local
- make phpunit: run `./vendor/phpunit/phpunit/phpunit`
- make phpfixer: run `./vendor/bin/php-cs-fixer fix`
- make phpstan: run `./vendor/bin/phpstan analyse`
- make server: run `cd public/ && php -S 127.0.0.1:8008`
- make remover: run `rm -rf var/ && rm -rf vendor/`

#### Docker
- make docker-start: run `docker-compose up -d --build && docker-compose exec api composer install`
- make docker-restart: run `rm -rf var/ && rm -rf vendor/ && docker-compose up -d --build && docker-compose exec api composer install`
- make docker-build: run `docker-compose up -d --build`
- make docker-install: run `docker-compose exec api composer install`
- make docker-down: run `docker-compose down`
- make docker-rm: run `docker rm $(docker ps -aq) -f`
- make docker-rmi: run `docker rmi $(docker images -aq) -f`