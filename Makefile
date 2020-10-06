#-----------------------------------------------------------
# Docker
#-----------------------------------------------------------

# Wake up docker containers
up:
	docker-compose up -d

# Shut down docker containers
down:
	docker-compose down

# Shut down docker containers and remove db volume
remove-volume:
	docker-compose down -v

# Show a status of each container
status:
	docker-compose ps

# Status alias
s: status

# Show logs of each container
logs:
	docker-compose logs

# Build and up docker containers
build:
	docker-compose up -d --build

# Build containers with no cache option
build-no-cache:
	docker-compose build --no-cache

# Build and up docker containers
rebuild: down build

# Run terminal of the php container
php:
	docker-compose exec php bash

# Install npm
install-npm:
	docker-compose exec php bash npm install

#-----------------------------------------------------------
# Create Laravel Project
#-----------------------------------------------------------

create-project:
	docker-compose exec php composer create-project "laravel/laravel=6.8" .
	docker-compose exec php composer require doctrine/dbal
	docker-compose exec php composer require --dev squizlabs/php_codesniffer

#-----------------------------------------------------------
# Install
#-----------------------------------------------------------

aws-sdk-php:
	docker-compose exec php composer require aws/aws-sdk-php

passport:
	docker-compose exec php composer require laravel/passport

#-----------------------------------------------------------
# PHP Code Sniffer
#-----------------------------------------------------------

# PHP Code Sniffer
sniffer:
	docker-compose exec php ./vendor/bin/phpcs --standard=phpcs.xml ./

# PHP Code Sniffer Rewrite
sniffer-rewrite:
	docker-compose exec php ./vendor/bin/phpcbf --standard=phpcs.xml ./