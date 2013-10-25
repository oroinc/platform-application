#!/bin/sh
ENV="prod"
if [ $1 ]
then
    ENV="$1"
fi

php app/console-framework oro:entity-extend:clear --env $ENV
php app/console-framework doctrine:schema:drop --force --full-database --env $ENV
php app/console-framework doctrine:schema:create --env $ENV
php app/console-framework doctrine:fixture:load --no-debug --no-interaction --env $ENV
php app/console-framework doctrine:fixtures:load --fixtures=vendor/oro/platform/src/Oro/Bundle/TestFrameworkBundle/Fixtures/ --append --no-debug --no-interaction --env $ENV
php app/console-framework oro:navigation:init --env $ENV
php app/console-framework oro:entity-config:init --env $ENV
php app/console-framework oro:entity-extend:init --env $ENV
php app/console-framework oro:entity-extend:update-config --env $ENV
php app/console-framework doctrine:schema:update --env $ENV --force
php app/console-framework oro:search:create-index --env $ENV
php app/console-framework assets:install web --env $ENV
php app/console-framework assetic:dump --env $ENV
php app/console-framework oro:assetic:dump --env $ENV
php app/console-framework oro:translation:dump --env $ENV
php app/console-framework oro:requirejs:build --env $ENV
