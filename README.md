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
- SQLite 3.8.8+ (Built-in database, no separate installation needed)
- Node.js 16+ and NPM (for frontend assets)

## Installation

1. Clone the repository and navigate to the project directory:
   ```bash
   git clone https://github.com/yourusername/pest-dashboard.git
   cd pest-dashboard
   ```

2. Install dependencies:
   ```bash
   # Install PHP dependencies
   composer install
   
   # Install JavaScript dependencies
   npm install
   ```

3. Set up the environment:
   ```bash
   # Copy the example environment file
   cp .env.example .env
   
   # Generate application key
   php artisan key:generate
   ```

4. Configure the database (SQLite is used by default):
   ```bash
   # Create the SQLite database file
   touch database/database.sqlite
   
   # Update .env to use SQLite
   echo "DB_CONNECTION=sqlite" >> .env
   echo "DB_DATABASE=$(pwd)/database/database.sqlite" >> .env
   ```

5. Run database migrations:
   ```bash
   php artisan migrate
   ```

6. Build the frontend assets:
   ```bash
   npm run build
   ```

7. Start the development server:
   ```bash
   php artisan serve
   ```

8. Open your browser and visit: [http://localhost:8000](http://localhost:8000)

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
