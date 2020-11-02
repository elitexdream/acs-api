ACS Backend API

## Steps
1. Install vendor

   ``` sh
   composer install
   ```

2. Migrate database
   ``` sh
   php artisan migrate
   ```

3. Setup .env file

    ``` sh
    DB_CONNECTION=sqlsrv
    DB_HOST=
    DB_DATABASE=
    DB_USERNAME=
    DB_PASSWORD=

    PASSPORT_GRANT_TYPE=password
    PASSPORT_CLIENT_SECRET={client secret}
    PASSPORT_CLIENT_ID={client id}
    ```
4. Run the server
    ``` sh
    php artisan serve
    ```