Oro Platform Empty Application
==============================
An example of empty application built using the Oro Platform.

This repository contains application configuration settings and depends on Oro Platform.

Important Note: this application is not production ready and is intended for evaluation and development only!

Installation
------------

### Using Composer

[As both Symfony 2 and Oro Platform use [Composer][2] to manage their dependencies, this is the recommended way to install Oro Platform.]

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

    curl -s https://getcomposer.org/installer | php

- Clone https://github.com/orocrm/platform-application.git Platform Application project with

    git clone https://github.com/orocrm/platform-application.git

- Make sure that you have installed Java

- Go to app/config folder and create parameters.yml using parameters.yml.dist as example. Update database name and credentials
  Alternatively parameters.yml can be created automatically on the next step when run composer install command,
  you will be able to customize all the values interactively.
  
- Install Platform Application dependencies with composer. If installation process seems too slow you can use "--prefer-dist" option.

    php composer.phar install

- Create the database (default name is "bap_standard")

- Open the BAP URL and initialize application with Install Wizard
  Alternatively with script (for Linux and Mac OS install.sh, for Windows install.bat)
  After installation you can login as application administrator using user name "admin" and password "admin".

Instant messaging between the browser and the web server
--------------------------------------------------------
To use this feature you need to configure parameters.yml websocket parameters and run server with console command

 ```bash
app/console clank:server

[1]:  http://symfony.com/doc/2.3/book/installation.html
[2]:  http://getcomposer.org/