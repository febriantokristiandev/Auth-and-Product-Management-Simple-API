# Laravel Quick Guide

## 1. Install Composer

### **Windows:**
- Download and run [Composer Setup](https://getcomposer.org/Composer-Setup.exe).

### **macOS/Linux:**
1. Run:
    ```bash
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    ```
2. Verify installation:
    ```bash
    composer --version
    ```

## 2. Artisan Commands

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
