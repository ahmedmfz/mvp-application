# People Management API (Laravel 12)

A modular Laravel 12 API for managing users with:
- **nwidart/laravel-modules** (User · Activity · Message · Statistic modules)
- **Events & Listeners** (activity logging · welcome messaging · daily statistics)
- **Centralized exceptions** via `bootstrap/app.php -> withExceptions(...)`
- **OpenAPI/Swagger** docs
- **Queued** activity, statistics, messages & bulk imports

## Requirements

- PHP 8.4+
- MySQL 8+ or PostgreSQL 14+
- Composer 2+

## Quick Start

```bash
# 1) Clone
git clone https://github.com/ahmedmfz/mvp-application.git
cd mvp-application

# 2) Install deps
composer install

# 3) Env
cp .env.example .env
php artisan key:generate

# 4) DB & migrations
# set DB_* in .env first
php artisan migrate --seed


# 5) Queue (required for activity, statistics, messages & bulk imports)
php artisan queue:work --queue=bulk,default

# 6) Serve API
php artisan serve
```

### Swagger / OpenAPI
- Visit: `/api/documentation` (Here you can Show and Test Apis)
- Rebuild docs:
```bash
php artisan l5-swagger:generate
```

## Environment

Example `.env` essentials:
```dotenv
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=people_management
DB_USERNAME=root
DB_PASSWORD=secret

QUEUE_CONNECTION=database
CACHE_DRIVER=file
SESSION_DRIVER=file
```


## Project Structure (high level)

```
bootstrap/app.php                        # Laravel 12 app config (exceptions wired here)
Modules/
│
├─ User/                                 # User management + bulk onboarding
│  ├─ app/
│  │  ├─ Events/
│  │  │  ├─ UserCreated.php
│  │  │  ├─ UserUpdated.php
│  │  │  └─ UserDeleted.php
│  │  ├─ Http/
│  │  │  ├─ Controllers/
│  │  │  │  └─ Api/
│  │  │  │     └─ UsersController.php
│  │  │  └─ Requests/
│  │  │     └─ Api/
│  │  │        ├─ StoreUserRequest.php
│  │  │        ├─ StoreBulkUserRequest.php
│  │  │        └─ UpdateUserRequest.php
│  │  ├─ Jobs/
│  │  │  └─ BulkUsersChunkJob.php        # Queued chunk processor for bulk imports
│  │  ├─ Models/
│  │  │  └─ User.php
│  │  ├─ Providers/
│  │  │  ├─ UsersServiceProvider.php
│  │  │  ├─ EventServiceProvider.php
│  │  │  └─ RouteServiceProvider.php
│  │  ├─ Services/
│  │  │  ├─ UserService.php
│  │  │  └─ BulkUserOnboardingService.php
│  │  └─ Transformers/
│  │     └─ UserResource.php
│  ├─ database/
│  │  ├─ migrations/
│  │  │  └─ ..._create_bulk_imports_table.php
│  │  └─ seeders/
│  │     └─ UsersDatabaseSeeder.php
│  └─ routes/
│     ├─ api/
│     │  └─ v1.php
│     └─ web.php
│
├─ Activity/                             # Logs user actions via event listeners
│  ├─ app/
│  │  ├─ Enums/
│  │  │  └─ ActionTypeEnum.php
│  │  ├─ Listeners/
│  │  │  └─ LogUserActivityListener.php  # Queued; reacts to User events
│  │  ├─ Models/
│  │  │  └─ Activity.php
│  │  ├─ Providers/
│  │  │  ├─ ActivityServiceProvider.php
│  │  │  ├─ EventServiceProvider.php
│  │  │  └─ RouteServiceProvider.php
│  │  └─ Services/
│  │     └─ ActivityLogger.php
│  ├─ database/
│  │  ├─ migrations/
│  │  │  └─ ..._create_activities_table.php
│  │  └─ seeders/
│  │     └─ ActivityDatabaseSeeder.php
│  └─ routes/
│     ├─ api.php
│     └─ web.php
│
├─ Message/                              # Sends welcome messages on user creation
│  ├─ app/
│  │  ├─ Enums/
│  │  │  ├─ MessageStatusEnum.php
│  │  │  └─ MessageTypeEnum.php
│  │  ├─ Http/
│  │  │  └─ Controllers/
│  │  │     └─ MessageController.php
│  │  ├─ Listeners/
│  │  │  └─ WelcomeMessageListener.php   # Queued; fires on UserCreated
│  │  ├─ Models/
│  │  │  └─ Message.php
│  │  ├─ Providers/
│  │  │  ├─ MessageServiceProvider.php
│  │  │  ├─ EventServiceProvider.php
│  │  │  └─ RouteServiceProvider.php
│  │  └─ Services/
│  │     └─ MessageService.php
│  ├─ database/
│  │  ├─ migrations/
│  │  │  └─ ..._create_messages_table.php
│  │  └─ seeders/
│  │     └─ MessageDatabaseSeeder.php
│  └─ routes/
│     ├─ api.php
│     └─ web.php
│
└─ Statistic/                            # Tracks daily aggregated stats
   ├─ app/
   │  ├─ Enums/
   │  │  └─ DailyStatCounter.php
   │  ├─ Listeners/
   │  │  └─ UpdateDailyStatsListener.php # Queued; reacts to User events
   │  ├─ Models/
   │  │  └─ Statistic.php
   │  ├─ Providers/
   │  │  ├─ StatisticServiceProvider.php
   │  │  ├─ EventServiceProvider.php
   │  │  └─ RouteServiceProvider.php
   │  └─ Services/
   │     └─ DailyStatisticsService.php
   ├─ database/
   │  ├─ migrations/
   │  │  └─ ..._create_statistics_table.php
   │  └─ seeders/
   │     └─ StatisticDatabaseSeeder.php
   └─ routes/
      ├─ api.php
      └─ web.php
```


## Architecture & Decisions

- **Modules (nwidart)** to isolate each domain — User, Activity, Message, Statistic — into independent, self-contained units (models, events, listeners, services, routes).
- **Service** to keep controllers thin and business logic testable.
- **Events/Listeners**
  - `UserCreated` fired after create.
  - `UserUpdated` fired after update.
  - `UserDeleted` fired after delete.
- **Exception handling** in `bootstrap/app.php` using `->withExceptions()`:
  - JSON for API via `shouldRenderJsonWhen()`
  - 404 (route/model), 405, 401, 403, 422 unified responses.
- **OpenAPI** annotations on CRUD endpoints for auto docs.
- **An `HelperResponse` is used to unify all backend API responses** (success and error) into a consistent JSON shape across the application.
- **All API FormRequests extend an abstract `BaseApiRequest`** that overrides Laravel’s default validation response shape for consistency across endpoints.

## API Overview

- `POST /api/users` — create  
- `GET /api/users/{user_id}` — show user
- `PUT /api/users/{user_id}` — update  
- `DELETE /api/users/{user_id}` — delete  
- `POST /api/users/bulk` — bulk create users


On **create** — `UserCreated` dispatched:
- `LogUserActivityListener` — logs the action (Activity module, queued)
- `WelcomeMessageListener` — sends a welcome message (Message module, queued)
- `UpdateDailyStatsListener` — increments daily stats (Statistic module, queued)

On **update** — `UserUpdated` dispatched:
- `LogUserActivityListener` — logs the action (Activity module, queued)
- `UpdateDailyStatsListener` — updates daily stats (Statistic module, queued)

On **delete** — `UserDeleted` dispatched:
- `LogUserActivityListener` — logs the action (Activity module, queued)
- `UpdateDailyStatsListener` — updates daily stats (Statistic module, queued)


## Assumptions
- User IDs are **auto-incrementing integers** (standard Laravel default); UUIDs are used only for `bulk_imports` tracking.
- **No mail/SMTP** dependency — there are no mailables in this project; messaging is handled by the Message module (DB records).
- All event listeners (`LogUserActivityListener`, `WelcomeMessageListener`, `UpdateDailyStatsListener`) implement `ShouldQueue` — **a queue worker must be running**.
- Bulk imports run on a dedicated **`bulk` queue** (`onQueue('bulk')`); ensure a worker is consuming that queue.


