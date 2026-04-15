# Travel Destination Manager

REST API built with Symfony 7, PHP 8.2, and MySQL for managing travel destinations.

## Setup

```bash
composer install
```

Update `.env`:

```env
DATABASE_URL="mysql://root:@127.0.0.1:3306/travel_destination_manager?serverVersion=8.0&charset=utf8mb4"
```

```bash
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
php -S localhost:8000 -t public
```

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /destinations | Create destination |
| GET | /destinations | List all |
| GET | /destinations/{id} | Get single |
| PUT | /destinations/{id} | Update |
| DELETE | /destinations/{id} | Delete |
| GET | /destinations/search | Search with filters |

### Search (all filters optional)

```
GET /destinations/search?activity=diving&max_budget=1500&travel_month=June
```

### Sample Payload

```json
{
  "name": "Bali",
  "activities": ["diving", "hiking"],
  "average_cost": 1200,
  "best_travel_months": ["June", "July"]
}
```

## Run Tests

```bash
php bin/phpunit tests/Unit/DestinationServiceTest.php
```

## Architecture

Follows SOLID principles — Controller (HTTP) → Service (Logic) → Repository (DB)
