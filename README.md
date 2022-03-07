## Prerequisite

-   PHP >= 7.x
-   MySQL >= 8.x
-   Composer >= v1.10.x

## Technology Stack

-   Laravel
-   Inertiajs
-   Vue3
-   Tailwindcss
-   MySQL

## Setup locally

-   git clone https://github.com/joppx25/task-management.git
-   cd task-management
-   cp .env.example .env
-   `php artisan key:generate`
-   Create a database to your local name it `task`

```bash
  composer install
  npm install
  php artisan migrate
```

## Access locally

```bash
 -  php artisan serve
 -  http://localhost:8000
 -  Register account in http://localhost:8000/register
```