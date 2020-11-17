# ACS Backend REST API

ACS backend REST API powered by laravel

### Tech
* [Laravel 7.2](https://laravel.com) - PHP MVC framework

### Installation

Download code from github
``` sh
    $ git clone https://github.com/MachineCDN/acs-api.git
```
Install libraries
``` sh
    $ cd acs-api
    $ composer install
```
Create a database
    
Config envirenment variables in .env file
- Create a new .env file from .env.example file
- Set variables
    * DB_CONNECTION={database driver - mysql/pgsql/sqlite/sqlsrv}
    * DB_HOST={hosting server that hosts database}
    * DB_DATABASE={database name created above}
    * DB_USERNAME={username of database connection}
    * DB_PASSWORD={password of database connection}

Create tables and populate basic information
``` sh
    $ php artisan migrate
    $ php artisan db:seed
```

Create personal access client or install passport
``` sh
    $ php artisan passport:client --personal
```