set shell := ["fish", "-c"]
dc := "docker compose -f .docker/compose.yaml"
exec-php := "exec --user www-data php /bin/bash"
composer_config := 'export COMPOSER_CACHE_READ_ONLY=1;export COMPOSER_MEMORY_LIMIT=-1;'

default: install

rebuild:
    just build
    just stop
    just start

logs:
    {{dc}} {{exec-php}} -c "grep 'CRITICAL' var/log/*.log"

cc:
    {{dc}} {{exec-php}} -c "rm -rf var/cache/*"
    {{dc}} {{exec-php}} -c "bin/console cache:clear --env=prod --no-warmup"
    {{dc}} {{exec-php}} -c "bin/console cache:warmup --env=prod"

cc-dev:
    {{dc}} {{exec-php}} -c "bin/console cache:clear --no-warmup"
    {{dc}} {{exec-php}} -c "bin/console cache:warmup"

migrate:
    {{dc}} {{exec-php}} -c "bin/console doctrine:migrations:migrate  --no-interaction"

assets-install:
    {{dc}} {{exec-php}} -c "bin/console importmap:install"

assets-compile:
    {{dc}} {{exec-php}} -c "bin/console asset-map:compile"

assets-update:
    {{dc}} {{exec-php}} -c "bin/console importmap:update"

opcache:
    {{dc}} {{exec-php}} -c "php /tmp/cachetool.phar opcache:reset --fcgi=127.0.0.1:9000"

bash:
    {{dc}} {{exec-php}}

bash-root:
    {{dc}} exec php /bin/bash

icons-warm:
    {{dc}} {{exec-php}} -c "bin/console ux:icons:warm-cache"

restart-php:
    just icons-warm

phpstan:
    {{dc}} {{exec-php}} -c "php -dmemory_limit=-1 vendor/bin/phpstan analyze src/"

rector:
    {{dc}} {{exec-php}} -c "php -dmemory_limit=-1 vendor/bin/rector"

linter:
    -{{dc}} {{exec-php}} -c "php -dmemory_limit=-1 vendor/bin/php-cs-fixer fix"
    -{{dc}} {{exec-php}} -c "php -dmemory_limit=-1 vendor/bin/twig-cs-fixer lint templates/ --fix"
    -just phpstan

outdated:
    just composer-outdated
    {{dc}} {{exec-php}} -c "bin/console importmap:audit"
    {{dc}} {{exec-php}} -c "bin/console importmap:outdated"

build:
    {{dc}} build --pull

start:
    {{dc}} up --remove-orphans -d php db redis

start-dev:
    {{dc}} up --remove-orphans -d php db redis

stop:
    {{dc}} stop

database-export:
    {{dc}} exec db /usr/bin/mariadb-dump -uwork -pwork work | gzip > ./data/backup.sql.gz

database-import:
    zcat ./data/backup.sql.gz | {{dc}} exec -T db /usr/bin/mariadb -uwork -pwork work
    echo 'update config set value = null where code in ("youtube_apikey", "spotify_clientid", "spotify_clientsecret")' | {{dc}} exec -T db /usr/bin/mariadb -uwork -pwork work

database-connect:
    {{dc}} exec db /bin/bash -c "mariadb -uwork -pwork work"

composer-outdated:
    {{dc}} {{exec-php}} -c "{{composer_config}} composer outdated"

composer-install:
    {{dc}} {{exec-php}} -c "{{composer_config}} composer install --no-dev --optimize-autoloader --classmap-authoritative"

composer-update:
    {{dc}} {{exec-php}} -c "{{composer_config}} composer update -W --no-dev --optimize-autoloader --classmap-authoritative"

composer-install-dev:
    {{dc}} {{exec-php}} -c "{{composer_config}} composer install"

composer-update-dev:
    {{dc}} {{exec-php}} -c "{{composer_config}} composer update -W"

update:
    git pull
    just composer-update
    just migrate
    just assets-update
    just assets-compile
    just restart-php

update-dev:
    git pull
    just composer-update-dev
    just migrate
    just assets-update
    just assets-compile
    just restart-php

install:
    git pull
    just composer-install
    just migrate
    just assets-install
    just assets-compile
    just cc
    just restart-php

install-dev:
    git pull
    just composer-install-dev
    just migrate
    just assets-install
    just assets-compile
    just stop
    just start-dev