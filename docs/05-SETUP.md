# Setup & Installation

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0+
- Laravel 11+

## Installation Steps

### 1. Clone & Install Dependencies
```bash
git clone <repository>
cd GameBook
composer install
npm install
```

### 2. Environment Setup
```bash
cp .env.example .env
```

Edit `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gamebook
DB_USERNAME=root
DB_PASSWORD=

# For WebSockets (Reverb)
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=gamebook
REVERB_APP_KEY=your-key
REVERB_APP_SECRET=your-secret
REVERB_HOST=127.0.0.1
REVERB_PORT=8080

APP_TIMEZONE=Africa/Casablanca
```

### 3. Database
```bash
php artisan migrate
php artisan db:seed  # Optional: seed sample data
```

### 4. Run Application
```bash
# Development
npm run dev
php artisan serve

# Production
npm run build
```

### 5. Create Admin User
```bash
php artisan make:admin email@example.com
```

Or manually in database:
```sql
INSERT INTO model_has_permissions (permission_id, model_type, model_id)
SELECT id, 'App\Models\User', 1
FROM permissions WHERE name = 'admin';
```

## Key Commands

| Command | Description |
|---------|-------------|
| `php artisan migrate` | Run migrations |
| `php artisan db:seed` | Seed database |
| `php artisan serve` | Start dev server |
| `php artisan check-table-status` | Run status checker |

## Scheduled Tasks

Add to crontab (or use Laravel scheduler):
```
* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
```

Runs `CheckTableStatus` every minute to:
- End expired sessions
- Update table statuses
- Handle ready reservations

## WebSocket Server (Reverb)

```bash
php artisan reverb:start
```

Or use Laravel Forge/etc for production.

## Permissions

```bash
# Storage directories
chmod -R 775 storage bootstrap/cache
```

## Troubleshooting

### WebSocket not working
- Ensure REVERB_* config is correct
- Run `php artisan config:clear`
- Check Reverb server is running

### Session countdown not updating
- Check browser console for errors
- Verify Echo.js is listening to `table-status` channel
- Clear view cache: `php artisan view:clear`

### Table status not updating
- Run `php artisan check-table-status` manually
- Check database for active sessions
- Verify scheduled task is running