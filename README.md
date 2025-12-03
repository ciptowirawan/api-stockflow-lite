# API StockFlow Lite

Laravel 12 backend API for inventory management system.

## Tech Stack

- Laravel 12
- PHP 8.2
- PostgreSQL 15 (Docker)
- Docker & Docker Compose

## Getting Started

### Prerequisites

- Docker and Docker Compose installed
- Git

### Installation

#### 1. Clone the repository
```bash
git clone <repository-url>
cd api-stockflow-lite
```

#### 2. Create and configure environment file
```bash
cp .env.example .env
```

**Important:** Update `.env` with these values for Docker:

```env
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=secret
```

#### 3. Start Docker containers (database first)
```bash
docker compose up -d db
```

Wait a few seconds for PostgreSQL to initialize, then:

#### 4. Run first-time setup (Do this BEFORE starting the app)

```bash
# Build the app container (without starting it)
docker compose build app

# Run setup commands
docker compose run --rm app php artisan key:generate
docker compose run --rm app php artisan passport:keys
docker compose run --rm app php artisan passport:install
docker compose run --rm app php artisan passport:client --password
docker compose run --rm app php artisan db:seed
```

#### 5. Start the application
```bash
docker compose up -d app
```

## Daily Usage

### Start the application
```bash
docker compose up -d
```

### Stop the application
```bash
docker compose down
```

### Rebuild after changes
If you modify `composer.json` or `Dockerfile`:
```bash
docker compose up -d --build
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
