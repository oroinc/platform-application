=======================

### General

  * Pull changes from repository
```bash
git pull
git checkout <VERSION TO UPGRADE>
```
  * Upgrade composer dependency
```bash
php composer.phar install --prefer-dist
```
  * Disable APC, OpCache, other code accelerators
  * Remove old caches and assets
```bash
rm -rf app/cache/*
rm -rf web/js/*
rm -rf web/css/*
```
  * Upgrade to 3.0.0 version
    * To sucessfully upgrade to 3.0.0 version which uses Symfony 3 you need to replace all form alias by their respective FQCN's in entity configs and embedded forms. Use the following script to find out which values should be changed.
```bash
php app/oro-form-alias-checker
```

  * Upgrade platform
```bash
php bin/console oro:platform:update --env=prod

  