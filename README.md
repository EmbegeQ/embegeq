# EmbegeQ Application Skeleton

The EmbegeQ Application Skeleton is the starting point for building web applications using the EmbegeQ Framework. It provides a pre-configured directory structure and dual entry points engineered for both traditional stateless FPM environments and stateful, event-driven runtimes.

## Key Features

- **Dual Entry Points**: Out-of-the-box support for both stateless CGI/FPM ([public/index.php](public/index.php)) and stateful worker loops ([public/worker.php](public/worker.php)) designed for FrankenPHP, Swoole, or RoadRunner.
- **Bootstrapping Structure**: Clean directory separation (`app/`, `bootstrap/`, `config/`, `public/`, `routes/`, `storage/`, `tests/`).
- **Autoloading**: Fully configured PSR-4 autoloading via Composer.
- **Local Symlink Setup**: Pre-configured to symlink the local `embegeq/framework` development repository.

## Getting Started

### Prerequisites

- PHP >= 8.4
- Composer

### Installation

1. Clone or copy the skeleton project into your workspace.
2. Install the dependencies using Composer:
   ```bash
   composer install
   ```
3. Copy the template environment configuration file:
   ```bash
   copy .env.example .env
   ```
4. Update the environment variables in `.env` as needed.

### Running the Application

To run the application in a traditional stateless mode, you can start PHP's built-in web server pointing to the `public/` directory:

```bash
php -S localhost:8088 -t public
```

You can then access:
- The home page at: `http://localhost:8088/`
- The API health check endpoint at: `http://localhost:8088/api/health`

## Repository Structure

- `app/` - Contains the application core logic.
  - `Providers/` - Bootstrapping service providers like `AppServiceProvider` and `RouteServiceProvider`.
- `bootstrap/` - System bootstrap configuration and container setup.
- `config/` - Configuration files (e.g., `app.php`, `database.php`).
- `public/` - Public assets and entry points (`index.php`, `worker.php`).
- `routes/` - Route definitions (`web.php`, `api.php`).
- `storage/` - Storage directory for logs, cache, and framework metadata.
- `tests/` - Application test suites.

## Ecosystem Reference

This skeleton is powered by the [EmbegeQ Framework](https://github.com/EmbegeQ/framework).

## Security Vulnerabilities

If you discover a security vulnerability within the EmbegeQ framework or skeleton, please send an email to the security team at **security@embegeq.dev.dyzulk.com**. All security vulnerabilities will be promptly addressed.

## License

The EmbegeQ skeleton is open-sourced software licensed under the [MIT license](LICENSE.md).
