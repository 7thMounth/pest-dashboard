# Pest Dashboard

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Pest Dashboard is a web application for testing and monitoring API endpoints with configurable requests, headers, and concurrency levels. Built with Laravel, it provides an intuitive interface for running tests and analyzing results.

## Features

- **API Testing**: Test any REST API endpoint with different HTTP methods (GET, POST, PUT, DELETE, PATCH)
- **Concurrent Testing**: Configure concurrency levels to simulate multiple simultaneous requests
- **Custom Headers**: Add custom request headers for API authentication and content negotiation
- **Request Body**: Send JSON payloads with your API requests
- **Response Analysis**: View detailed test results including response times and status codes
- **Export Results**: Export test results for further analysis

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL 5.7+ or MariaDB 10.3+
- Node.js 16+ and NPM (for frontend assets)

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/pest-dashboard.git
   cd pest-dashboard
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install JavaScript dependencies:
   ```bash
   npm install
   ```

4. Copy the environment file and generate application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Configure your database settings in the `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. Run database migrations:
   ```bash
   php artisan migrate
   ```

7. Build frontend assets:
   ```bash
   npm run build
   ```

8. Start the development server:
   ```bash
   php artisan serve
   ```

9. Visit `http://localhost:8000` in your browser.

## Usage

1. Navigate to the dashboard
2. Click "New Test"
3. Enter the API endpoint URL (e.g., `api.example.com/users` or `192.168.1.1:3000/api`)
4. Select the HTTP method
5. Set the concurrency level
6. Add any required headers (e.g., `Content-Type: application/json`)
7. Add a request body if needed (for POST/PUT/PATCH requests)
8. Click "Run Test"
9. View the test results in the dashboard

## License

This project is open-sourced under the [MIT License](https://opensource.org/licenses/MIT).

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
