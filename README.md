Oro Platform Empty Application
==============================
An example of empty application built using the Oro Platform.

This repository contains application configuration settings and depends on Oro Platform.

Installation
------------

### Using Composer

[As both Symfony 2 and OroCRM use [Composer][1] to manage their dependencies, this is the recommended way to install OroCRM.]

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

    curl -s https://getcomposer.org/installer | php

- Clone http://gitlab.orocrm.com/platform-application.git Platform Application project with

    git clone http://gitlab.orocrm.com/platform-application.git

- Go to app/config folder and create parameters.yml using parameters.dist.yml as example. Update database name and credentials
- Install Platform Application dependencies with composer

    php composer.phar install

- Initialize application with install script (for Linux and Mac OS install.sh, for Windows install.bat)

[1]:  http://getcomposer.org/