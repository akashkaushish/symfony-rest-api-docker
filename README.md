# symfony-rest-api-docker-and-redis
Rest Apis Development with Symfony 6 and docker 


# Installation Guide

1. Clone the code
2. docker compose build
3. docker compose up -d
4. docker compose down

# Another Commands
1. PHPSTAN: docker compose exec app sh -c "vendor/bin/phpstan analyse"
2. docker compose exec app composer phpcs
3. docker compose exec app composer phpcbf
4. docker compose exec app composer phpunit
