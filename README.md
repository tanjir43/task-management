# 📋 Task Management System

Task management application with Laravel and Breeze authentication.

### Prerequisites

- PHP 8.0 or higher
- Composer
- Node.js and npm
- MySQL or compatible database

### Installation

1. **Unzip the project**

2. **Navigate to the project directory**
   ```
    cd task-management

    ## Setup .env
    cp .env.example .env

    ## Configure Database
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=
    DB_USERNAME=
    DB_PASSWORD=

    ## PHP dependencies
    composer install

    ## Frontend dependencies
    npm install

    ## Application key
    php artisan key:generate

    ## Migrations with seeder
    php artisan migrate:fresh --seed

    # Frontend asset
    npm run dev

    # Run Server 
    php artisan serve
    ```

3. **🔐 Default Credentials**
    ```
    Email: admin@admin.com
    Password: 12345678
    ```
