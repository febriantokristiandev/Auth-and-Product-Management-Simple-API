# Quick Guide

## 1. Install Composer

1. **Navigate to Backend Directory:**
    ```bash
    cd backend
    ```

2. **Windows:**
    - Download and run [Composer Setup](https://getcomposer.org/Composer-Setup.exe).

3. **macOS/Linux:**
    1. Run:
        ```bash
        curl -sS https://getcomposer.org/installer | php
        sudo mv composer.phar /usr/local/bin/composer
        ```
    2. Verify installation:
        ```bash
        composer --version
        ```

## 2. Prepare Database

1. **Create Database:**

    - **MySQL:**
        1. Masuk ke MySQL menggunakan command line atau tool seperti phpMyAdmin.
        2. Jalankan perintah SQL berikut untuk membuat database:
            ```sql
            CREATE DATABASE magnifistore;
            ```

2. **Configure `.env`:**
    - Pastikan file `.env` di folder `/backend` memiliki konfigurasi berikut:
      ```env
      DB_CONNECTION=mysql
      DB_HOST=127.0.0.1
      DB_PORT=3306
      DB_DATABASE=magnifistore
      DB_USERNAME=root
      DB_PASSWORD=
      ```

## 3. Artisan Commands

- **Run Tests:**
    ```bash
    php artisan test
    ```

- **Start Development Server:**
    ```bash
    php artisan serve
    ```

- **Run Migrations:**
    ```bash
    php artisan migrate
    ```

## 4. Postman Documentation

File dokumentasi Postman dapat ditemukan di: `Magnifistore API.postman_collection.json`.
