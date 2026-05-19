# PMS Laravel API

Primary backend for Task & Project Management System (Laravel 10 + Sanctum + PostgreSQL).

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
# Create PostgreSQL database: pms_db
php artisan migrate --seed
php artisan serve
```

API runs at `http://localhost:8000`. Start Django overdue service on port 8001 before task status updates.

## Test credentials

| Role  | Email           | Password  |
|-------|-----------------|-----------|
| Admin | admin@pms.test  | password  |
| User  | user@pms.test   | password  |

## API endpoints

| Method | Endpoint | Auth | Admin |
|--------|----------|------|-------|
| POST | /api/register | - | - |
| POST | /api/login | - | - |
| GET | /api/me | ✓ | - |
| GET | /api/projects | ✓ | - |
| GET | /api/projects/{id} | ✓ | - |
| POST | /api/projects | ✓ | ✓ |
| GET | /api/tasks | ✓ | - |
| POST | /api/tasks | ✓ | ✓ |
| PATCH | /api/tasks/{id} | ✓ | assignee/admin |
| GET | /api/users | ✓ | ✓ |
