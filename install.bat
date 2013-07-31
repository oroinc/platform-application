echo OFF
set ENV=prod
if "%1" NEQ "" (
    set ENV=%1
)
php app/console-framework doctrine:schema:create --env %ENV%
php app/console-framework oro:search:create-index --env %ENV%
php app/console-framework doctrine:fixture:load --no-debug --no-interaction --env %ENV%
php app/console-framework oro:acl:load --env %ENV%
php app/console-framework oro:navigation:init --env %ENV%
php app/console-framework oro:entity-config:update --env %ENV%
php app/console-framework assets:install web --env %ENV%
php app/console assetic:dump --env %ENV%
php app/console oro:assetic:dump
