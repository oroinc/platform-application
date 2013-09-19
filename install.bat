echo OFF
set ENV=prod
if "%1" NEQ "" (
    set ENV=%1
)
php app/console-framework doctrine:schema:create --env %ENV% || goto :error
php app/console-framework doctrine:fixture:load --no-debug --no-interaction --env %ENV% || goto :error
php app/console-framework oro:navigation:init --env %ENV% || goto :error
php app/console-framework oro:entity-config:update --env %ENV% || goto :error
php app/console-framework oro:entity-extend:create --env %ENV% || goto :error
php app/console-framework cache:clear --env %ENV% || goto :error
php app/console-framework doctrine:schema:update --env %ENV% --force || goto :error
php app/console-framework oro:search:create-index --env %ENV% || goto :error
php app/console-framework assets:install web --env %ENV% || goto :error
php app/console assetic:dump --env %ENV% || goto :error
php app/console oro:assetic:dump || goto :error
php app/console oro:translation:dump || goto :error
goto :EOF

:error
echo Failed with error #%ERRORLEVEL%.
exit /b %ERRORLEVEL%

