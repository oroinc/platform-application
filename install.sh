#!/bin/sh
php app/console-framework doctrine:database:create
php app/console-framework doctrine:schema:create
php app/console-framework oro:search:create-index
php app/console-framework doctrine:fixture:load --no-debug --no-interaction
php app/console-framework oro:acl:load
php app/console-framework oro:navigation:init
php app/console-framework assets:install web
php app/console-framework assetic:dump
php app/console-framework oro:assetic:dump
