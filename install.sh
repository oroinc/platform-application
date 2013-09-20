#!/bin/sh
ENV="prod"
if [ $1 ]
then
    ENV="$1"
fi

php app/console-framework doctrine:schema:create --env $ENV
php app/console-framework doctrine:fixture:load --no-debug --no-interaction --env $ENV
php app/console-framework oro:navigation:init --env $ENV
php app/console-framework oro:entity-config:update --env $ENV
php app/console-framework oro:entity-extend:init --env $ENV
php app/console-framework oro:entity-extend:create --env $ENV
php app/console-framework cache:clear --env $ENV
php app/console-framework doctrine:schema:update --env $ENV --force
php app/console-framework oro:search:create-index --env $ENV
php app/console-framework assets:install web --env $ENV
php app/console-framework assetic:dump --env $ENV
php app/console-framework oro:assetic:dump
php app/console-framework oro:translation:dump
