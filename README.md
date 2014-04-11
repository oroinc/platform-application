Oro Platform Empty Application
==============================

An example of an empty application using the Oro Platform.

This repository contains application configuration settings and depends on Oro Platform. It can be used as a starting point to build applications using the oro Platform.

## Requirements

Oro Platform is a Symfony 2 based application with the following requirements:

* PHP 5.4.4 or above
* PHP 5.4.4 or above with command line interface
* PHP Extensions
    * GD
    * Mcrypt
    * JSON
    * ctype
    * Tokenizer
    * SimpleXML
    * PCRE
    * ICU
* MySQL 5.1 or above

## Installation instructions

### Using Composer

As both Symfony 2 and Oro Platform use [Composer][2] to manage their dependencies, this is the recommended way to install the Oro Platform.

If you don't have Composer yet, download it and follow the instructions on
http://getcomposer.org/ or just run the following command:

```bash
    curl -s https://getcomposer.org/installer | php
```

- Clone https://github.com/orocrm/platform-application.git Platform Application project with

```bash
    git clone https://github.com/orocrm/platform-application.git
```

- Make sure that you have [NodeJS][4] installed

- Install OroCRM dependencies with composer. If installation process seems too slow you can use "--prefer-dist" option.
  Go to crm-application folder and run composer installation:

```bash
php composer.phar install --prefer-dist
```

- Create the database with the name specified on previous step (default name is "bap_standard").

- Install application and admin user with Installation Wizard by opening install.php in the browser or from CLI:

```bash  
php app/console oro:install --env prod
```

- Enable WebSockets messaging

```bash
php app/console clank:server --env prod
```

- Configure crontab or scheduled tasks execution to run the command below every minute:

```bash
php app/console oro:cron --env prod
```
 
**Note:** ``app/console`` is a path from project root folder. Please make sure you are using full path for crontab configuration or if you running console command from other location.

## Installation notes

Installed PHP Accelerators must be compatible with Symfony and Doctrine (support DOCBLOCKs)

Using MySQL 5.6 on HDD is potentially risky because of performance issues

Recommended configuration for this case:

    innodb_file_per_table = 0

And ensure that timeout has default value

    wait_timeout = 28800

See [Optimizing InnoDB Disk I/O][3] for more

## Package Manager Configuration

Github OAuth token should be configured in package manager settings


[1]:  http://symfony.com/doc/2.3/book/installation.html
[2]:  http://getcomposer.org/
[3]:  http://dev.mysql.com/doc/refman/5.6/en/optimizing-innodb-diskio.html
[4]:  http://nodejs.org/
