# GameBook - Game Center Reservation System

A Laravel-based reservation system for game centers with real-time session management, game switching, and admin dashboard.

## Documentation

| File | Description |
|------|-------------|
| [docs/01-DATABASE.md](01-DATABASE.md) | Database schema, tables, relationships |
| [docs/02-LOGIC.md](02-LOGIC.md) | Application logic, business rules |
| [docs/03-FEATURES.md](03-FEATURES.md) | Complete feature list |
| [docs/04-ROUTES.md](04-ROUTES.md) | API routes and endpoints |
| [docs/05-SETUP.md](05-SETUP.md) | Installation and configuration |

## Quick Overview

- **Tech Stack**: Laravel 11 + Vite + Alpine.js + Reverb WebSockets
- **Timezone**: Africa/Casablanca (Morocco)
- **Database**: MySQL
- **Auth**: Laravel Breeze (Spatie for roles)

## Key Features

- Real-time table status (available/booked/ready/in_progress)
- Session management with live countdown
- Game switching with High-Water Mark pricing
- Conflict prevention for reservations
- Admin dashboard with full control
- WebSocket real-time updates