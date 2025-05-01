# DrHome - Laravel Vue Starter Kit

This is a Laravel project with Vue.js frontend using Inertia.js for seamless integration.

## Prerequisites

Before you begin, ensure you have the following installed:

- PHP 8.2 or higher
- Composer (PHP package manager)
- Node.js (LTS version recommended)
- npm (comes with Node.js)
- MySQL 5.7+ or MariaDB 10.3+

## Installation Steps

1. Clone the repository:

```bash
git clone https://github.com/aryayudh06/DrHome
cd DrHome
```

2. Install PHP dependencies:

```bash
composer install
```

3. Install JavaScript dependencies:

```bash
npm install
```

4. Create environment file:

```bash
cp .env.example .env
```

5. Configure your database:

    - Open `.env` file
    - Update the following database settings with your MySQL credentials:
        ```
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=drhome
        DB_USERNAME=your_mysql_username
        DB_PASSWORD=your_mysql_password
        ```

6. Generate application key:

```bash
php artisan key:generate
```

7. Run migrations:

```bash
php artisan migrate
```

## Running the Development Server

To start the development server, run:

```bash
composer run dev
```

This command will start:

- Laravel development server
- Queue listener
- Vite development server

The application will be available at `http://localhost:8000`

## Additional Information

- The project uses Laravel 12 with Vue 3
- Frontend assets are built using Vite
- Inertia.js is used for seamless integration between Laravel and Vue
- Tailwind CSS is used for styling
- TypeScript is used for type safety
- MySQL is used as the database system

## Development Tools

- ESLint for JavaScript/TypeScript linting
- Prettier for code formatting
- PHPUnit for testing
- Laravel Pint for PHP code style

## Troubleshooting

If you encounter database connection issues:

1. Verify your MySQL service is running
2. Check your MySQL credentials in the .env file
3. Ensure the database exists and is accessible
4. Try connecting to MySQL using your credentials to verify they work
5. Check if your MySQL user has proper permissions

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
