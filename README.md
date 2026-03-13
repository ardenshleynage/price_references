<p align="center">
    <img src="public/images/bx--bxs-smile.png" width="100" alt="Price References Logo">
</p>

<h1 align="center">Price References</h1>

<p align="center">
    <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php" alt="PHP">
    <img src="https://img.shields.io/badge/Laravel-11-F05340?style=flat&logo=laravel" alt="Laravel">
    <img src="https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat&logo=bootstrap" alt="Bootstrap">
    <img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License">
</p>

## About

Price References is a Laravel-based admin panel for managing products, categories, branches, and users with role-based access control. Built with a focus on simplicity and user experience.

## Features

- **Role-Based Access Control**: Three user roles with specific permissions
- **Fake Delete System**: Active, Trash, and Permanently Deleted states
- **Responsive Design**: Optimized for mobile (400px) and desktop
- **Dark Mode**: Full theme support across the application
- **Advanced Search**: Search across all entities
- **Pagination**: Custom pagination with Bootstrap styling
- **Hosting Ready**: Can run on Raspberry Pi with CasaOS

## User Roles

| Role        | ID  | Access Level                          |
| ----------- | --- | ------------------------------------- |
| Super Admin | 1   | Full access to all features           |
| Admin       | 2   | Manage products, categories, branches |
| Reader      | 3   | View-only access                      |

## Tech Stack

- **Backend**: Laravel 11
- **Frontend**: Bootstrap 5, SCSS, Vanilla JavaScript
- **Build Tool**: Vite
- **Database**: MySQL/MariaDB
- **Server**: PHP Built-in Server / Nginx

## Requirements

- PHP 8.2+
- MySQL 5.7+ or MariaDB 10.3+
- Node.js 18+
- Composer 2+

## Installation

1. **Clone the repository**

    ```bash
    git clone <repository-url>
    cd price_references
    ```

2. **Install dependencies**

    ```bash
    composer install
    npm install
    ```

3. **Configure environment**

    ```bash
    cp .env.example .env
    ```

4. **Generate key and setup database**

    ```bash
    php artisan key:generate
    php artisan migrate
    ```

5. **Build assets**

    ```bash
    npm run build
    ```

6. **Run the server**
    ```bash
    php artisan serve
    ```

## Default Routes

- `/` - Home (redirects based on role)
- `/login` - Authentication
- `/super_admin_home` - Super Admin Dashboard
- `/admins_home` - Admin Dashboard
- `/readers_home` - Reader Dashboard

## Hosting on Raspberry Pi

The application can be hosted on a Raspberry Pi 4B with CasaOS:

- Configure CasaOS to use port 3000 (or another port)
- Use Tailscale for remote access
- Point your domain to the Raspberry Pi IP

## Security

- All routes are protected by authentication middleware
- Role-based access control prevents unauthorized access
- CSRF protection on all forms
- Session-based authentication with remember token support

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
